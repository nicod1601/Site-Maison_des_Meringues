<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
	protected $table = 'produit';

	protected $primaryKey = 'id_produit';

	public $timestamps = false;

	protected $fillable = [
		'id_forme',
		'id_parfum',
        'description',
		'quantite',
		'nouveaute',
		'live',
	];
}
