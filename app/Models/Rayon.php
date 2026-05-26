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

	public function boutique()
	{
		return $this->belongsTo(Boutique::class, 'id_boutique', 'id_boutique');
	}

	public function produits()
	{
		return $this->belongsToMany(
			Produit::class,
			'produit_rayon',
			'id_rayon',
			'id_produit'
		);
	}

	public function recalculerStock(): void
	{
		$this->stock_total_rayon = $this->produits()->sum('quantite');
		$this->save();
	}

	public function syncProduitsDepuisEvents(): void
	{
		$eventIds = $this->events()->pluck('event.id_event')->toArray();

		if (empty($eventIds)) {
			$this->produits()->detach();
			$this->recalculerStock();
			return;
		}

		$produitsEligiblesIds = Produit::whereHas('events', function ($q) use ($eventIds) {
			$q->whereIn('event.id_event', $eventIds);
		})->pluck('id_produit')->toArray();

		$this->produits()->sync($produitsEligiblesIds);

		$this->recalculerStock();
	}
	public function islive(): bool
	{
		return $this->live_rayon;
	}

	public function toggleLive(bool $live): void
	{
		$this->live_rayon = $live;
		$this->save();
	}

	public function events()
	{
		return $this->belongsToMany(Event::class, 'rayon_event', 'id_rayon', 'id_event');
	}
}
