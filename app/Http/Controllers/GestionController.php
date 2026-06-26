<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Produit;
use App\Models\Forme;
use App\Models\Conditionnement;
use App\Models\Parfum;
use App\Models\Forme_Condi;
use App\Models\Rayon;
use App\Models\Theme;
use App\Models\Event;
use App\Models\Image;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class GestionController extends Controller
{
	public function index(Request $request)
	{
		$datas = session('import_preview', null);
		session()->forget('import_preview');

		$boutique     = Boutique::first();
		$stock_total  = $boutique->stock_total;
		$nom_boutique = $boutique->nom_boutique;

		$rayons = Rayon::with('events')->get();
		$themes = Theme::all();
		$events = Event::all();

		$rayonId = $request->query('rayon');

		// Résoudre l'objet $rayon actif (null si "tous les rayons")
		$rayon = ($rayonId && $rayonId !== '-1')
			? $rayons->firstWhere('id_rayon', $rayonId)
			: null;

		$query = Produit::with([
			'parfum',
			'forme.forme_condis.conditionnement',
			'rayons',
			'theme',
			'events',
			'stocks',
		]);

		if ($rayon) {
			$query->whereHas('rayons', function ($q) use ($rayonId) {
				$q->where('rayon.id_rayon', $rayonId);
			});
		}

		$produits    = $query->orderBy('id_produit')->get();
		$nb_produits = $produits->count();

		$formes           = Forme::all();
		$conditionnements = Conditionnement::all();
		$parfums          = Parfum::all();
		$forme_condi      = Forme_Condi::with(['forme', 'conditionnement'])->get();

		$produitsByTheme = $themes->mapWithKeys(fn($theme) => [
			$theme->id_theme => Produit::where('id_theme', $theme->id_theme)->count()
		]);

		$produitsByEvent = $events->mapWithKeys(fn($event) => [
			$event->id_event => $event->produits()->count()
		]);

		$images = Image::with([
			'produit',
			'formeCondi.forme',
			'formeCondi.conditionnement',
		])->paginate(20);

		return view('gestion', compact(
			'datas', 'stock_total', 'nom_boutique', 'nb_produits',
			'produits', 'formes', 'conditionnements', 'parfums',
			'forme_condi', 'rayons', 'themes', 'events', 'boutique',
			'rayonId', 'rayon',
			'produitsByTheme', 'produitsByEvent', 'images',
		));
	}

	public function exportProduits()
	{
		$produits = Produit::with([
			'forme.forme_condis.conditionnement',
			'parfum',
			'rayons',
			'theme',
			'stocks',
		])
			->orderBy('id_produit')
			->get();

		$conditionnementTypes = Conditionnement::orderBy('id_condi')->pluck('type');

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Produits');

		// ── En-têtes fixes ───────────────────────────────────────
		$headers = [
			'A' => 'Nom_produit',
			'B' => 'Forme',
			'C' => 'Parfum',
			'D' => 'Description',
			'E' => 'Nouveauté',
			'F' => 'Live',
			'G' => 'Theme',
			'H' => 'Event',
			'I' => 'Specialité',
		];

		// ── En-têtes dynamiques : une colonne de stock par conditionnement ──
		$stockCols = []; // type => lettre de colonne
		$col = 'J';
		foreach ($conditionnementTypes as $type) {
			$headers[$col]    = 'Stock_' . $type;
			$stockCols[$type] = $col;
			$col++;
		}

		// ── Colonne informative, ignorée à l'import ──────────────
		$headers[$col]  = 'Stock_total';
		$colStockTotal  = $col;
		$lastCol        = $col;

		foreach ($headers as $colLetter => $label) {
			$sheet->setCellValue($colLetter . '1', $label);
		}

		$headerStyle = [
			'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'name' => 'Arial', 'size' => 10],
			'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFC0813A']],
			'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
			'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD4A574']]],
		];
		$sheet->getStyle("A1:{$lastCol}1")->applyFromArray($headerStyle);
		$sheet->getRowDimension(1)->setRowHeight(20);

		// ── Données ───────────────────────────────────────────────
		$row = 2;
		foreach ($produits as $p) {

			$sheet->setCellValue('A' . $row, $p->nom_produit ?? '');
			$sheet->setCellValue('B' . $row, $p->forme->nom_forme ?? '');
			$sheet->setCellValue('C' . $row, $p->parfum->nom_parfum ?? '');
			$sheet->setCellValue('D' . $row, $p->description ?? '');
			$sheet->setCellValue('E' . $row, $p->nouveaute ? 'oui' : 'non');
			$sheet->setCellValue('F' . $row, $p->live      ? 'oui' : 'non');
			$sheet->setCellValue('G' . $row, $p->theme->nom_theme ?? '');
			$sheet->setCellValue('H' . $row, $p->events()->pluck('nom_event')->implode(', ') ?? ' ');
			$sheet->setCellValue('I' . $row, $p->special     ? 'oui' : 'non');

			// Stock par conditionnement : vide si ce conditionnement n'existe
			// pas pour la forme du produit, sinon la quantité (0 par défaut).
			foreach ($stockCols as $type => $colLetter) {
				$fc = $p->forme?->forme_condis->first(
					fn($fc) => $fc->conditionnement->type === $type
				);

				$valeur = '';
				if ($fc) {
					$valeur = $p->stocks->firstWhere('id_forme_condi', $fc->id_forme_condi)?->quantite ?? 0;
				}

				$sheet->setCellValue($colLetter . $row, $valeur);
			}

			$sheet->setCellValue($colStockTotal . $row, (int) ($p->quantite ?? 0));

			$bgColor = ($row % 2 === 0) ? 'FFFFF8F0' : 'FFFFFFFF';
			$rowStyle = [
				'font'    => ['name' => 'Arial', 'size' => 10],
				'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bgColor]],
				'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE8D5C0']]],
			];
			$sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($rowStyle);

			if ((int) ($p->quantite ?? 0) === 0) {
				$sheet->getStyle($colStockTotal . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFCC3333'));
				$sheet->getStyle($colStockTotal . $row)->getFont()->setBold(true);
			}

			$row++;
		}

		// ── Largeurs colonnes ──────────────────────────────────────
		$widths = ['A' => 14, 'B' => 12, 'C' => 16, 'D' => 35, 'E' => 12, 'F' => 8, 'G' => 18, 'H' => 20, 'I' => 12];
		foreach ($widths as $colLetter => $width) {
			$sheet->getColumnDimension($colLetter)->setWidth($width);
		}
		foreach ($stockCols as $colLetter) {
			$sheet->getColumnDimension($colLetter)->setWidth(16);
		}
		$sheet->getColumnDimension($colStockTotal)->setWidth(13);

		$sheet->freezePane('A2');
		$sheet->setAutoFilter("A1:{$lastCol}1");

		$filename = 'produits_export_' . now()->format('Ymd_His') . '.xlsx';
		$writer   = new Xlsx($spreadsheet);

		return response()->streamDownload(
			function () use ($writer) {
				$writer->save('php://output');
			},
			$filename,
			[
				'Content-Type'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'Cache-Control' => 'max-age=0',
			]
		);
	}
}
