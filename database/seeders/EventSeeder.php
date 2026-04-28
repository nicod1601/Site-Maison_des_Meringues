<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('event')->insert([
            ['nom_event' => 'Printemps',    'icone' => '🌸', 'couleur' => '#A8D5A2'],
            ['nom_event' => 'Noël',         'icone' => '🎄', 'couleur' => '#C0392B'],
            ['nom_event' => 'Anniversaire', 'icone' => '🎂', 'couleur' => '#F7C948'],
        ]);
    }
}
