<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	protected $table      = 'event';
	protected $primaryKey = 'id_event';
	public    $timestamps = true;

	protected $fillable = [
		'nom_event',
		'icone',
		'couleur',
	];

	public function rayons()
	{
		return $this->belongsToMany(
			Rayon::class,
			'rayon_event',
			'id_event',
			'id_rayon'
		);
	}

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
