<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Forme;
use App\Models\Conditionnement;

class FormeCondiSeeder extends Seeder
{
	public function run(): void
	{
		$formes = Forme::all();
		$conditionnements = Conditionnement::all();

		$data = [];

		foreach ($formes as $forme) {
			foreach ($conditionnements as $condi) {

				if ($forme->nom_forme === "Mini")
				{
					if($condi->type === "sachet_de_10")
					{
						$data[] = [
							'id_forme' => $forme->id_forme,
							'id_condi' => $condi->id_condi,
							'prix' => 7,
						];
					}

					if($condi->type === "individuel")
					{
						$data[] = [
							'id_forme' => $forme->id_forme,
							'id_condi' => $condi->id_condi,
							'prix' => 0.80,
						];
					}

				}
				else
				{
					if($condi->type === "individuel")
					{
						$data[] = [
							'id_forme' => $forme->id_forme,
							'id_condi' => $condi->id_condi,
							'prix' => 1.50,
						];
					}

					if($condi->type === "sachet_de_4")
					{
						$data[] = [
							'id_forme' => $forme->id_forme,
							'id_condi' => $condi->id_condi,
							'prix' => 6,
						];
					}

					if($condi->type === "boite_de_8")
					{
						$data[] = [
							'id_forme' => $forme->id_forme,
							'id_condi' => $condi->id_condi,
							'prix' => 10,
						];
					}
				}
			}
		}
		DB::table('forme_condi')->insert($data);
	}
}
