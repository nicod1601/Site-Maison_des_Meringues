<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParfumSeeder extends Seeder
{
	public function run(): void
	{
		DB::table('parfum')->insert([
			['nom_parfum' => 'Ananas'],
			['nom_parfum' => 'Bergamote'],
			['nom_parfum' => 'Cerise'],
			['nom_parfum' => 'Fraise'],
			['nom_parfum' => 'Framboise'],
			['nom_parfum' => 'Litchi'],
			['nom_parfum' => 'Nature'],
			['nom_parfum' => 'Pain Epices-Am'],
			['nom_parfum' => 'Pistache'],
			['nom_parfum' => 'Passion'],
			['nom_parfum' => 'Pomme'],
			['nom_parfum' => 'Rose'],
			['nom_parfum' => 'Fève de Tonka'],
			['nom_parfum' => 'Violette'],
			['nom_parfum' => 'Café'],
			['nom_parfum' => 'Bénédictine'],
			['nom_parfum' => 'Caramel/B-salé'],
			['nom_parfum' => 'Rhubarbe'],
			['nom_parfum' => 'Tarte au Citron'],
			['nom_parfum' => 'Panettone'],
			['nom_parfum' => 'Cannelle'],
			['nom_parfum' => 'Cardemone'],
		]);
	}
}
