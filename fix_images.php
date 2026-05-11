<?php
// Placez ce fichier à la racine du projet : php fix_images.php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

$images = DB::table('image')->get();

foreach ($images as $img) {
    $url = $img->url;

    // Décoder les accents corrompus (latin1 → utf8)
    $urlFixed = mb_convert_encoding($url, 'UTF-8', 'ISO-8859-1');

    // Décomposer le chemin
    // Format : fichier/image/meringues/{forme}/{condi}/{fichier}
    $parts    = explode('/', $urlFixed);
    $fichier  = array_pop($parts);   // ex: Café.jpg
    $condi    = array_pop($parts);   // ex: boite_de_8
    $forme    = array_pop($parts);   // ex: Nid
    $prefix   = implode('/', $parts); // fichier/image/meringues

    // Normaliser dossiers et nom de fichier
    $formeSlug  = Str::slug($forme);   // nid
    $condiSlug  = Str::slug($condi);   // boite-de-8

    $ext        = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
    $baseName   = pathinfo($fichier, PATHINFO_FILENAME);
    $fileSlug   = Str::slug($baseName) . '.' . $ext; // cafe.jpg

    $newUrl     = "{$prefix}/{$formeSlug}/{$condiSlug}/{$fileSlug}";

    // Chemins physiques
    $oldPath    = public_path($urlFixed);
    $newPath    = public_path($newUrl);
    $newDir     = dirname($newPath);

    // Créer le dossier si besoin
    if (!is_dir($newDir)) {
        mkdir($newDir, 0755, true);
    }

    // Renommer le fichier sur le disque
    if (file_exists($oldPath) && $oldPath !== $newPath) {
        rename($oldPath, $newPath);
        echo "✅ Renommé : {$urlFixed} → {$newUrl}\n";
    } elseif (!file_exists($oldPath)) {
        echo "⚠️  Fichier introuvable : {$oldPath}\n";
    } else {
        echo "⏭️  Déjà correct : {$newUrl}\n";
    }

    // Mettre à jour la BDD
    if ($url !== $newUrl) {
        DB::table('image')
            ->where('id_image', $img->id_image)
            ->update(['url' => $newUrl]);
    }
}

echo "\n✅ Migration terminée.\n";
