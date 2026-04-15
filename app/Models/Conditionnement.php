<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conditionnement extends Model
{
	protected $table = 'conditionnement';

	protected $primaryKey = 'id_condi';

	public $timestamps = false;

	protected $fillable = [
        'id_condi',
        'type',
	];
}
