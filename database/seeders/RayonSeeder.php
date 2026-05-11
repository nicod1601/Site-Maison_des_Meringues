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
				'nom_rayon'        => 'Base',
				'id_boutique'      => 1,
				'stock_total_rayon'=> 0,
				'live_rayon'       => DB::raw('false'),
			 ],
		 ]);
	 }
}
