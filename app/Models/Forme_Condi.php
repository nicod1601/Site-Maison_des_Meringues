<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forme_Condi extends Model
{
	protected $table = 'forme_condi';
	public $timestamps = false;

	protected $fillable = [
		'id_forme',
		'id_condi',
		'prix',
	];


	public function forme()
	{
		return $this->belongsTo(Forme::class, 'id_forme');
	}

	public function conditionnement()
	{
		return $this->belongsTo(Conditionnement::class, 'id_condi');
	}
}
