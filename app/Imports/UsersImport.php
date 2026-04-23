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
		/*foreach ($produitIds as $produitId) {
			$produit = Produit::find($produitId);
			if ($produit && $produit->rayons()->count() === 0) {
				$produit->delete();
			}
		}*/

		//supprimer tous les produits liés à ce rayon
		/*foreach ($produitIds as $produitId) {
			$produit = Produit::find($produitId);

            // vérifier que le produit existe dans un autre rayon si il existe dans un autre rayon alors ne pas le supprimer
            if ($produit)
                $rayonsCount = $produit->rayons()->count();
            else
            	$rayonsCount = 0;

			if ($produit && $rayonsCount === 0) {
				$produit->delete();
			}
		}*/

        // je veux supprimer tous les produits qui sont liés à se rayon cependant
        // si un produit est lié à un autre rayon alors je ne veux pas le supprimer
        // mais je veux supprimer la relation entre le produit et le rayon actuel
        foreach ($produitIds as $produitId) {
            $produit = Produit::find($produitId);
            if ($produit) {
                $rayonsCount = $produit->rayons()->count();

                if ($rayonsCount === 1) {
                    $produit->delete();
                } else {
                    $produit->rayons()->detach($this->idRayon);
                }
            }
        }

		$boutique = Boutique::first();

		foreach ($rows as $index => $row) {

			if ($index === 0) continue; // ignorer l'en-tête

			$nomProduit = trim($row[0] ?? '');
			if ($nomProduit === '') continue;

			$forme = DB::table('forme')
				->where('nom_forme', $row[1])
				->first();

			if (!$forme) continue;

			$forme_condi = DB::table('forme_condi')
				->where('id_forme', $forme->id_forme)
				->first();

			$parfum = DB::table('parfum')
				->where('nom_parfum', $row[2])
				->first();

			if (!$forme_condi || !$parfum) continue;

			$description = $row[3] ?? 'Aucune description';
			$quantite    = (int) ($row[4] ?? 0);
			$nouveaute   = strtolower($row[5] ?? '') === 'oui';
			$live        = strtolower($row[6] ?? '') === 'oui';
			$expedition  = in_array(strtolower($forme->nom_forme), ['mini']);

			$nomTheme = trim($row[7] ?? '');
			$idTheme  = null;

			if ($nomTheme !== '') {
				$theme = DB::table('theme')
					->where('nom_theme', $nomTheme)
					->first();
				if ($theme) {
					$idTheme = $theme->id_theme;

					$rayon->themes()->syncWithoutDetaching([$theme->id_theme]);
				}
			}

			$produit = Produit::create([
				'nom_produit'      => $nomProduit,
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
			$produit->rayons()->attach($this->idRayon);

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
