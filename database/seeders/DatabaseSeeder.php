<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
	use WithoutModelEvents;

	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$this->call([
			FormeSeeder::class,
			ConditionnementSeeder::class,
			FormeCondiSeeder::class,
			ParfumSeeder::class,
			BoutiqueSeeder::class,
			RayonSeeder::class,
            ThemeSeeder::class,

		]);

		$tables = [
			['rayon',           'id_rayon'],
			['boutique',        'id_boutique'],
			['forme',           'id_forme'],
			['conditionnement', 'id_condi'],
			['parfum',          'id_parfum'],
			['forme_condi',     'id_forme_condi'],
			['produit',         'id_produit'],
		];

		foreach ($tables as [$table, $col]) {
			DB::statement("SELECT setval(
				pg_get_serial_sequence('{$table}', '{$col}'),
				COALESCE((SELECT MAX({$col}) FROM {$table}), 1)
			)");
		}
	}
}
