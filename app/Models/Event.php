<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table      = 'event';
    protected $primaryKey = 'id_event';
    public    $timestamps = true; // ✅ Activé pour created_at et updated_at

    protected $fillable = [
        'nom_event',
        'icone',
        'couleur',
    ];

    // Un event est lié à plusieurs rayons (many-to-many)
    // → un rayon "Noël" appartient à l'event Noël
    public function rayons()
    {
        return $this->belongsToMany(
            Rayon::class,
            'rayon_event',
            'id_event',
            'id_rayon'
        );
    }

    // Un event regroupe plusieurs produits éligibles (many-to-many)
    // → un produit éligible à Noël peut apparaître dans le rayon Noël, trié par son thème
    public function produits()
    {
        return $this->belongsToMany(
            Produit::class,
            'produit_event',
            'id_event',
            'id_produit'
        );
    }
}
