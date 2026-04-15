<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use App\Models\Produit;

class UsersImport implements ToCollection
{
	public function collection(Collection $rows)
	{
        //reset des produits
        $listProduit = Produit::all();
        foreach ($listProduit as $produit) {
            $produit->delete();
        }


		$stock = 0;
		$id_boutique = 1;

		foreach ($rows as $index => $row) {

			if ($index === 0) continue; // header

			$forme = DB::table('forme')
				->where('nom_forme', $row[0])
				->first();

			$parfum = DB::table('parfum')
				->where('nom_parfum', $row[1])
				->first();

			if (!$forme || !$parfum) continue;

			$description = $row[2] ?? 'Aucune description';

			$quantite = $row[3] ?? 0;

			$nouveaute = strtolower($row[4]) === 'oui' ? true : false;
			$live = strtolower($row[5]) === 'oui' ? true : false;

			$expedition = $forme->nom_forme === "Mini" or $forme->nom_forme === "mini";

			$stock = $stock + $quantite;


			$produit = Produit::create([
				'id_forme' => $forme->id_forme,
				'id_parfum' => $parfum->id_parfum,
				'description' => $description,
				'quantite' => $quantite,
				'nouveaute' => $nouveaute,
				'live' => $live,
				'dispo_emporter' => false,
				'dispo_expedition' => $expedition,
			]);
		}

		//mettre à jour la boutique
		DB::table('boutique')->where('id_boutique', $id_boutique)
			->update(['stock_total' => $stock]);
	}
}
