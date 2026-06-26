<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitFormeCondi extends Model
{
    protected $table      = 'produit_forme_condi';
    protected $primaryKey = 'id_produit_forme_condi';
    public    $timestamps = false;

    protected $fillable = [
        'id_produit',
        'id_forme_condi',
        'quantite',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit', 'id_produit');
    }

    public function formeCondi()
    {
        return $this->belongsTo(Forme_Condi::class, 'id_forme_condi', 'id_forme_condi');
    }
}