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

		$query = Produit::with([
			'parfum',
			'forme.forme_condis.conditionnement',
			'rayons',
			'theme',
			'events',
		]);

		if ($rayonId && $rayonId !== '-1') {
			$query->whereHas('rayons', function ($q) use ($rayonId) {
				$q->where('rayon.id_rayon', $rayonId);
			});
		}

		$produits    = $query->get();
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
			'forme_condi', 'rayons', 'themes', 'events', 'boutique', 'rayonId',
			'produitsByTheme', 'produitsByEvent', 'images',
		));
	}

	public function exportProduits()
	{
		// Récupère les produits avec leurs relations
		$produits = Produit::with(['forme', 'parfum', 'rayons', 'theme'])
        ->orderBy('id_produit')
        ->get();

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Produits');

		$headers = [
			'A' => 'Nom_produit',
			'B' => 'Forme',
			'C' => 'Parfum',
			'D' => 'Description',
			'E' => 'Stock',
			'F' => 'Nouveauté',
			'G' => 'Live',
			'H' => 'Theme',
			'I' => 'Event',
			'J' => 'Specialité',
		];

		foreach ($headers as $col => $label) {
			$sheet->setCellValue($col . '1', $label);
		}

		$headerStyle = [
			'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'name' => 'Arial', 'size' => 10],
			'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFC0813A']],
			'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
			'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD4A574']]],
		];
		$sheet->getStyle('A1:J1')->applyFromArray($headerStyle);
		$sheet->getRowDimension(1)->setRowHeight(20);

		// ── Données ───────────────────────────────────────────────
		$row = 2;
		foreach ($produits as $p) {
			// Events : on prend les events via les rayons du produit
			$events = $p->rayons
				->flatMap(fn($r) => $r->events ?? collect())
				->unique('id_event')
				->pluck('nom_event')
				->implode(', ');

			$sheet->setCellValue('A' . $row, $p->nom_produit ?? '');
			$sheet->setCellValue('B' . $row, $p->forme->nom_forme ?? '');
			$sheet->setCellValue('C' . $row, $p->parfum->nom_parfum ?? '');
			$sheet->setCellValue('D' . $row, $p->description ?? '');
			$sheet->setCellValue('E' . $row, (int) ($p->quantite ?? 0));
			$sheet->setCellValue('F' . $row, $p->nouveaute ? 'oui' : 'non');
			$sheet->setCellValue('G' . $row, $p->live      ? 'oui' : 'non');
			$sheet->setCellValue('H' . $row, $p->theme->nom_theme ?? '');
			$sheet->setCellValue('I' . $row, $events);
			$sheet->setCellValue('J' . $row, $p->specialite ?? 'non');

			$bgColor = ($row % 2 === 0) ? 'FFFFF8F0' : 'FFFFFFFF';
			$rowStyle = [
				'font'    => ['name' => 'Arial', 'size' => 10],
				'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bgColor]],
				'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE8D5C0']]],
			];
			$sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray($rowStyle);

			// Stock en rouge si rupture
			if ((int)($p->quantite ?? 0) === 0) {
				$sheet->getStyle('E' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFCC3333'));
				$sheet->getStyle('E' . $row)->getFont()->setBold(true);
			}

			$row++;
		}

		// ── Largeurs colonnes ──────────────────────────────────────
		$widths = ['A' => 14, 'B' => 12, 'C' => 16, 'D' => 35, 'E' => 8, 'F' => 12, 'G' => 8, 'H' => 18, 'I' => 20, 'J' => 12];
		foreach ($widths as $col => $width) {
			$sheet->getColumnDimension($col)->setWidth($width);
		}

		// ── Figer la ligne d'en-tête ──────────────────────────────
		$sheet->freezePane('A2');

		// ── Auto-filtre ───────────────────────────────────────────
		$sheet->setAutoFilter('A1:J1');

		// ── Réponse HTTP ──────────────────────────────────────────
		$filename = 'produits_export_' . now()->format('Ymd_His') . '.xlsx';

		$writer = new Xlsx($spreadsheet);

		return response()->streamDownload(
			function () use ($writer) {
				$writer->save('php://output');
			},
			$filename,
			[
				'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'Cache-Control'       => 'max-age=0',
			]
		);
	}
}
