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

	// Un thème a plusieurs rayons (many-to-many)
	public function rayons()
	{
		return $this->belongsToMany(
			Rayon::class,
			'rayon_theme',
			'id_theme',
			'id_rayon'
		);
	}

	// Un thème a plusieurs produits
	public function produits()
	{
		return $this->hasMany(Produit::class, 'id_theme', 'id_theme');
	}
}
