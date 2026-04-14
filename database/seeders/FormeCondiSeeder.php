<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormeCondiSeeder extends Seeder
{
	public function run(): void
    {
        DB::table('forme_condi')->insert([

            // MINI
            [
                'id_forme' => 1, // Mini
                'id_condi' => 2, // boite_de_8
                'prix' => 8.99,
            ],
            [
                'id_forme' => 1,
                'id_condi' => 1, // sachet_de_4
                'prix' => 4.50,
            ],

            // NID
            [
                'id_forme' => 2, // Nid
                'id_condi' => 2, // boite_de_8
                'prix' => 9.99,
            ],
            [
                'id_forme' => 2,
                'id_condi' => 1,
                'prix' => 2.00,
            ],
        ]);
    }
}
