<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoutiqueSeeder extends Seeder
{
	public function run(): void
	{
		DB::table('boutique')->insert([
			[
				'id_boutique'  => 1,
				'nom_boutique' => 'Maison des Meringues',
				'stock_total'  => 0,
			],
		]);
	}
}

