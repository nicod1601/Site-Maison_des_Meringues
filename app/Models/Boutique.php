<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boutique extends Model
{
	protected $table = 'boutique';
	protected $primaryKey = 'id_boutique';
	public $timestamps = false;

	protected $fillable = [
		'nom_boutique',
		'stock_total',
	];

	// Une boutique a plusieurs rayons
	public function rayons()
	{
		return $this->hasMany(Rayon::class, 'id_boutique', 'id_boutique');
	}

	// Tous les produits de la boutique (via les rayons)
	public function produits()
	{
		return $this->hasManyThrough(
			Produit::class,
			Rayon::class,
			'id_boutique',
			'id_rayon',
			'id_boutique',
			'id_rayon'
		);
	}
}
