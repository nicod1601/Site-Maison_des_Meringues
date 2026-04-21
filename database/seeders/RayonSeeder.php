<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RayonSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Créer des rayons
		DB::table('rayon')->insert([
			['id_rayon' => 1, 'nom_rayon' => 'Meringues', 'stock_total_rayon' => 0],
		]);
	}
}
