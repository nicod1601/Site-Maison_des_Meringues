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
            ['nom_theme' => 'Chocolat',  'icone' => '🍫', 'couleur' => '#7A3B1E'],
            ['nom_theme' => 'Vanille',   'icone' => '🤍', 'couleur' => '#F5EDD6'],
            ['nom_theme' => 'Noël',      'icone' => '🎄', 'couleur' => '#2D6A4F'],
            ['nom_theme' => 'Printemps', 'icone' => '🌿', 'couleur' => '#93C572'],
            ['nom_theme' => 'Classique', 'icone' => '⭐', 'couleur' => '#C0395A'],
        ]);
    }
}
