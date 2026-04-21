<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rayon extends Model
{
	protected $table = 'rayon';
	protected $primaryKey = 'id_rayon';
	public $timestamps = false;

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

	// Un rayon a plusieurs produits
	public function produits()
	{
		return $this->hasMany(Produit::class, 'id_rayon', 'id_rayon');
	}

	// Recalcule et sauvegarde le stock du rayon
	public function recalculerStock(): void
	{
		$this->stock_total_rayon = $this->produits()->sum('quantite');
		$this->save();
	}
}
