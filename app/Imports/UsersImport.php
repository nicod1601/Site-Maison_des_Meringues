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
		$rayon = Rayon::findOrFail($this->idRayon);
		$produitIds = $rayon->produits()->pluck('produit.id_produit')->toArray();


		// Supprimer SEULEMENT les produits orphelins (non liés à d'autres rayons)
		foreach ($produitIds as $produitId) {
			$produit = Produit::find($produitId);
			if ($produit && $produit->rayons()->count() === 0) {
				$produit->delete();
			}
		}

		$boutique = Boutique::first();

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

			// Colonne G (index 6) : nom du thème (optionnel)
			$nomTheme = trim($row[6] ?? '');
			$idTheme  = null;

			if ($nomTheme !== '') {
				$theme = DB::table('theme')
					->where('nom_theme', $nomTheme)
					->first();
				if ($theme) {
					$idTheme = $theme->id_theme;

					// Attacher le thème au rayon sans dupliquer
					$rayon->themes()->syncWithoutDetaching([$theme->id_theme]);
				}
			}

			// Créer le produit sans id_rayon
			$produit = Produit::create([
				'id_forme_condi'   => $forme_condi->id_forme_condi,
				'id_parfum'        => $parfum->id_parfum,
				'id_theme'         => $idTheme,
				'description'      => $description,
				'quantite'         => $quantite,
				'nouveaute'        => $nouveaute,
				'live'             => $live,
				'dispo_emporter'   => false,
				'dispo_expedition' => $expedition,
			]);

			// Lier au rayon courant via la table pivot
            $produit->rayons()->attach($this->idRayon);

            // Si le produit a un thème, le lier aussi à tous les autres rayons
            // qui ont ce thème (pour ne pas casser les liens existants)
            if ($idTheme !== null) {
                $autresRayons = \App\Models\Rayon::whereHas('themes', function ($q) use ($idTheme) {
                    $q->where('theme.id_theme', $idTheme);
                })->where('id_rayon', '!=', $this->idRayon)->get();

                foreach ($autresRayons as $autreRayon) {
                    $produit->rayons()->syncWithoutDetaching([$autreRayon->id_rayon]);
                }
            }
		}

		// Mettre à jour les stocks
		$rayon->recalculerStock();
		$boutique->stock_total = Rayon::sum('stock_total_rayon');
		$boutique->save();
	}
}
