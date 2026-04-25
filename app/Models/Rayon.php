<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rayon extends Model
{
    protected $table      = 'rayon';
    protected $primaryKey = 'id_rayon';
    public    $timestamps = false;

    protected $fillable = [
        'nom_rayon',
        'id_boutique',
        'stock_total_rayon',
    ];

    // Un rayon appartient à une boutique
    public function boutique()
    {
        return $this->belongsTo(Boutique::class, 'id_boutique', 'id_boutique');
    }

    // Un rayon est lié à un ou plusieurs events (many-to-many)
    // → c'est l'event qui donne le contexte occasionnel du rayon (Noël, Printemps…)
    public function events()
    {
        return $this->belongsToMany(
            Event::class,
            'rayon_event',
            'id_rayon',
            'id_event'
        );
    }

    // Un rayon contient plusieurs produits (many-to-many)
    public function produits()
    {
        return $this->belongsToMany(
            Produit::class,
            'produit_rayon',
            'id_rayon',
            'id_produit'
        );
    }

    // Recalcule et sauvegarde le stock du rayon
    public function recalculerStock(): void
    {
        $this->stock_total_rayon = $this->produits()->sum('quantite');
        $this->save();
    }

    // ✅ Ajouté : lie au rayon tous les produits éligibles à ses events
    // À appeler après création d'un rayon ou après modification de rayon_event
    public function syncProduitsDepuisEvents(): void
    {
        $eventIds = $this->events()->pluck('event.id_event')->toArray();

        if (empty($eventIds)) {
            return; // Rayon sans event → on ne touche à rien
        }

        $produits = Produit::whereHas('events', function ($q) use ($eventIds) {
            $q->whereIn('event.id_event', $eventIds);
        })->get();

        foreach ($produits as $produit) {
            $produit->rayons()->syncWithoutDetaching([$this->id_rayon]);
        }

        $this->recalculerStock();
    }
}
