<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boutique extends Model
{
	protected $table = 'boutique';

	protected $primaryKey = 'id_boutique';

	public $timestamps = false;

	protected $fillable = [
		'id_boutique',
		'nom_boutique',
		'id_rayon',
		'stock_total_boutique',
	];

	public function rayon()
	{
		return $this->belongsTo(Rayon::class, 'id_rayon', 'id_rayon');
	}
}
