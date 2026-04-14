<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormeSeeder extends Seeder
{
	public function run(): void
	{
		DB::table('forme')->insert([
			['nom_forme' => 'Mini'],
			['nom_forme' => 'Nid'],
		]);
	}
}
