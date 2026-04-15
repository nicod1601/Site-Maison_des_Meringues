<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parfum extends Model
{
	protected $table = 'parfum';

	protected $primaryKey = 'id_parfum';

	public $timestamps = false;

	protected $fillable = [
		'id_parfum',
		'nom_parfum',
	];
}
