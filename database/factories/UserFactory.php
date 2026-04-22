<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use App\Models\Produit;
use App\Models\Rayon;
use App\Models\Boutique;

class UsersImport implements ToCollection
{
	private $idRayon;

	public function __construct(int $idRayon)
	{
		$this->idRayon = $idRayon;
	}

	public function collection(Collection $rows)
	{
		// Supprimer tous les produits existants du rayon avant d'importer
		Produit::query()->where('id_rayon', $this->idRayon)->delete();

		$stock    = 0;
		$boutique = Boutique::first();
		$rayon    = Rayon::findOrFail($this->idRayon);

		foreach ($rows as $index => $row) {

			if ($index === 0) continue; // ignorer l'en-tête

			$forme = DB::table('forme')
				->where('nom_forme', $row[0])
				->first();

			if (!$forme) continue;

			$forme_condi = DB::table('forme_condi')
				->where('id_forme', $forme->id_forme)
				->first();

			$parfum = DB::table('parfum')
				->where('nom_parfum', $row[1])
				->first();

			if (!$forme_condi || !$parfum) continue;

			$description = $row[2] ?? 'Aucune description';
			$quantite    = (int) ($row[3] ?? 0);
			$nouveaute   = strtolower($row[4] ?? '') === 'oui';
			$live        = strtolower($row[5] ?? '') === 'oui';
			$expedition  = in_array(strtolower($forme->nom_forme), ['mini']);

			// ── Colonne G (index 6) : nom du thème (optionnel) ──
			$nomTheme = trim($row[6] ?? '');
			if ($nomTheme !== '') {
				$theme = DB::table('theme')
					->where('nom_theme', $nomTheme)
					->first();

				// Met à jour le thème du rayon si trouvé et pas encore défini
				if ($theme && $rayon->id_theme === null) {
					$rayon->id_theme = $theme->id_theme;
					$rayon->save();
				}
			}

			$stock += $quantite;

			Produit::create([
				'id_forme_condi'   => $forme_condi->id_forme_condi,
				'id_parfum'        => $parfum->id_parfum,
				'id_rayon'         => $this->idRayon,
				'description'      => $description,
				'quantite'         => $quantite,
				'nouveaute'        => $nouveaute,
				'live'             => $live,
				'dispo_emporter'   => false,
				'dispo_expedition' => $expedition,
			]);
		}

		// Mettre à jour les stocks
		$rayon->stock_total_rayon = $stock;
		$rayon->save();

		$boutique->stock_total = Rayon::sum('stock_total_rayon');
		$boutique->save();
	}
}
