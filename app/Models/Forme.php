<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forme extends Model
{
	protected $table = 'forme';

	protected $primaryKey = 'id_forme';

	public $timestamps = false;

	protected $fillable = [
        'id_forme',
        'nom_forme',
	];
}
