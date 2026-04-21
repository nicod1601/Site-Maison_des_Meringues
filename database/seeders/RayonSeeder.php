<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RayonSeeder extends Seeder
{
	 public function run(): void
	 {
		 DB::table('rayon')->insert([
			 [
				 'id_rayon'         => 1,
				 'nom_rayon'        => 'Meringues',
				 'id_boutique'      => 1,   // ← pointe vers la boutique
				 'stock_total_rayon'=> 0,
			 ],
		 ]);
	 }
}
