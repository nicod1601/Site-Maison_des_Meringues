<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $table      = 'produit';
    protected $primaryKey = 'id_produit';
    public    $timestamps = false;

    protected $fillable = [
        'nom_produit',
        'id_forme_condi',
        'id_parfum',
        'id_theme',           // nullable — le thème est optionnel, c'est un indicateur de tri
        'description',
        'quantite',
        'nouveaute',
        'live',
        'dispo_emporter',
        'dispo_expedition',
    ];

    // Un produit appartient à plusieurs rayons (many-to-many)
    public function rayons()
    {
        return $this->belongsToMany(
            Rayon::class,
            'produit_rayon',
            'id_produit',
            'id_rayon'
        );
    }

    // Un produit est éligible à plusieurs events (many-to-many)
    // → l'event indique dans quel(s) rayon(s) événementiel(s) ce produit peut apparaître
    // → une fois dans le rayon, il est trié/filtré par son thème
    public function events()
    {
        return $this->belongsToMany(
            Event::class,
            'produit_event',
            'id_produit',
            'id_event'
        );
    }

    // Un produit peut avoir un thème (optionnel) — sert uniquement au tri et au filtrage
    public function theme()
    {
        return $this->belongsTo(Theme::class, 'id_theme', 'id_theme')->withDefault();
    }

    public function forme_condi()
    {
        return $this->belongsTo(Forme_Condi::class, 'id_forme_condi', 'id_forme_condi');
    }

    public function parfum()
    {
        return $this->belongsTo(Parfum::class, 'id_parfum', 'id_parfum');
    }

    // Retourne tous les conditionnements disponibles pour la même forme que ce produit
    public function tous_conditionnements()
    {
        return Forme_Condi::where('id_forme', $this->forme_condi->id_forme)
            ->with('conditionnement')
            ->get();
    }

    public function isnouveaute(): bool        { return (bool) $this->nouveaute; }
    public function islive(): bool             { return (bool) $this->live; }
    public function isdispo_emporter(): bool   { return (bool) $this->dispo_emporter; }
    public function isdispo_expedition(): bool { return (bool) $this->dispo_expedition; }
}
