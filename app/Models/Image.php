<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
	protected $table = 'image';

	protected $primaryKey = 'id_image';

	public $timestamps = false;

	protected $fillable = [
		'id_image',
		'id_produit',
        'id_forme_condi',
		'url',
	];
}
