<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionnementSeeder extends Seeder
{
	public function run(): void
	{
		DB::table('conditionnement')->insert([
			['type' => 'sachet_de_4'],
			['type' => 'boite_de_8'],
			['type' => 'sachet_de_10'],
			['type' => 'individuel'],
			['type' => 'vrac'],
		]);
	}
}
