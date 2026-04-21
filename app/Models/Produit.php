<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
	protected $table = 'produit';
	protected $primaryKey = 'id_produit';
	public $timestamps = false;

	protected $fillable = [
		'id_forme_condi',
		'id_parfum',
		'id_rayon',
		'description',
		'quantite',
		'nouveaute',
		'live',
		'dispo_emporter',
		'dispo_expedition',
	];

	// Un produit appartient à un rayon (nullable)
	public function rayon()
	{
		return $this->belongsTo(Rayon::class, 'id_rayon', 'id_rayon');
	}

	public function forme_condi()
	{
		return $this->belongsTo(Forme_Condi::class, 'id_forme_condi', 'id_forme_condi');
	}

	public function parfum()
	{
		return $this->belongsTo(Parfum::class, 'id_parfum', 'id_parfum');
	}

	public function tous_conditionnements()
	{
		return Forme_Condi::where('id_forme', $this->forme_condi->id_forme)
			->with('conditionnement')
			->get();
	}

	public function isnouveaute(): bool { return (bool) $this->nouveaute; }
	public function islive(): bool      { return (bool) $this->live; }
	public function isdispo_emporter(): bool   { return (bool) $this->dispo_emporter; }
	public function isdispo_expedition(): bool { return (bool) $this->dispo_expedition; }
}
