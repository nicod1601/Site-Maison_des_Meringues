<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Produit;

class UsersImport implements ToCollection
{
	public function collection(Collection $rows)
	{
		foreach ($rows as $row) {
			Produit::create([
				'id_forme' => $row[0],
				'id_condi' => $row[1],
				'id_parfum' => $row[2],
				'quantite' => $row[3],
				'nouveaute' => $row[4],
				'live' => $row[5],
			]);
		}
	}
}
