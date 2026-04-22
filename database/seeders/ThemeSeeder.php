<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('theme')->insert([
            ['nom_theme' => 'Fleurs',    'icone' => '🌸', 'couleur' => '#E8839A'],
            ['nom_theme' => 'Fruits',    'icone' => '🍓', 'couleur' => '#E85D4A'],
            ['nom_theme' => 'Desserts',  'icone' => '🍰', 'couleur' => '#C9A84C'],
            ['nom_theme' => 'Epice', 'icone' => '🥮', 'couleur' => '#A569BD'],
        ]);
    }
}
