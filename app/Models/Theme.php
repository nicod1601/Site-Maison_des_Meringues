<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $table      = 'theme';
    protected $primaryKey = 'id_theme';
    public    $timestamps = false;

    protected $fillable = [
        'nom_theme',
        'icone',
        'couleur',
    ];

    // Un thème regroupe plusieurs produits — sert à trier/filtrer les produits
    // Note : le thème n'appartient pas au rayon, il appartient au produit
    public function produits()
    {
        return $this->hasMany(Produit::class, 'id_theme', 'id_theme');
    }
}
