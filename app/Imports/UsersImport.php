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
		$stock = 0;

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

			$produit = Produit::create([
				'id_forme' => $forme->id_forme,
				'id_parfum' => $parfum->id_parfum,
				'description' => $description,
				'quantite' => $quantite,
				'nouveaute' => $nouveaute,
				'live' => $live,
			]);

			$stock += $quantite;

			DB::table('boutique')->insert([
				'id_produit' => $produit->id_produit,
				'dispo_emporter' => true,
				'dispo_expedition' => false,
				'stock_total' => $stock,
			]);
		}
	}
}
