<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table      = 'image';
    protected $primaryKey = 'id_image';
    public    $timestamps = false;

    protected $fillable = [
        'id_produit',
        'id_forme_condi',
        'url',
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
