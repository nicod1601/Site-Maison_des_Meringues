<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
	use WithoutModelEvents;

	public function run(): void
	{
		$this->call([
			FormeSeeder::class,
			ConditionnementSeeder::class,
			FormeCondiSeeder::class,
			ParfumSeeder::class,
			ThemeSeeder::class,
			EventSeeder::class,
			BoutiqueSeeder::class,
			RayonSeeder::class,
			AdminUserSeeder::class,
		]);

		// Resynchronisation des séquences PostgreSQL après insertion manuelle d'IDs
		$tables = [
			['boutique',        'id_boutique'],
			['rayon',           'id_rayon'],
			['forme',           'id_forme'],
			['conditionnement', 'id_condi'],
			['parfum',          'id_parfum'],
			['forme_condi',     'id_forme_condi'],
			['theme',           'id_theme'],
			['event',           'id_event'],
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
