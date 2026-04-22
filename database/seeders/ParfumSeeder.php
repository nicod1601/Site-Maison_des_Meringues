<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParfumSeeder extends Seeder
{
	public function run(): void
	{
		DB::table('parfum')->insert([
			['nom_parfum' => 'Chocolat'],
			['nom_parfum' => 'Fraise'],
			['nom_parfum' => 'Citron'],
			['nom_parfum' => 'Vanille'],
			['nom_parfum' => 'Caramel'],
            ['nom_parfum' => 'Epice'],
		]);
	}
}
