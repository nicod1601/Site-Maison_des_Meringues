<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rayon extends Model
{
	protected $table = 'rayon';

	protected $primaryKey = 'id_rayon';

	public $timestamps = false;

	protected $fillable = [
		'id_rayon',
		'nom_rayon',
		'id_produit',
		'stock_total_rayon',
	];

	public function produits()
	{
		return $this->hasMany(Produit::class, 'id_produit', 'id_produit');
	}
}
