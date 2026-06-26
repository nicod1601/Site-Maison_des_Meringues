<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
	protected $table      = 'produit';
	protected $primaryKey = 'id_produit';
	public    $timestamps = false;

	protected $fillable = [
		'nom_produit',
		'id_forme',
		'id_parfum',
		'id_theme',
		'description',
		'quantite',
		'nouveaute',
		'live',
		'dispo_emporter',
		'dispo_expedition',
		'special',
		'nouveaute_since',
	];

	protected $casts = [
		'nouveaute'        => 'boolean',
		'live'             => 'boolean',
		'dispo_emporter'   => 'boolean',
		'dispo_expedition' => 'boolean',
		'nouveaute_since'  => 'datetime',
        'special'          => 'boolean',
	];

	public function rayons()
	{
		return $this->belongsToMany(
			Rayon::class,
			'produit_rayon',
			'id_produit',
			'id_rayon'
		);
	}

	public function events()
	{
		return $this->belongsToMany(
			Event::class,
			'produit_event',
			'id_produit',
			'id_event'
		);
	}

	public function theme()
	{
		return $this->belongsTo(Theme::class, 'id_theme', 'id_theme')->withDefault();
	}

	public function forme()
	{
		return $this->belongsTo(Forme::class, 'id_forme');
	}

	public function parfum()
	{
		return $this->belongsTo(Parfum::class, 'id_parfum', 'id_parfum');
	}

	// ── Stock par conditionnement ──────────────────────────────────

	public function stocks()
	{
		return $this->hasMany(ProduitFormeCondi::class, 'id_produit', 'id_produit');
	}

	/**
	 * Stock disponible pour un conditionnement (forme_condi) précis.
	 */
	public function stockPour(int $idFormeCondi): int
	{
		if ($this->relationLoaded('stocks')) {
			return $this->stocks->firstWhere('id_forme_condi', $idFormeCondi)?->quantite ?? 0;
		}

		return (int) $this->stocks()->where('id_forme_condi', $idFormeCondi)->value('quantite');
	}

	/**
	 * Recalcule le stock total = somme des stocks par conditionnement.
	 * À appeler après toute modification de $this->stocks.
	 */
	public function recalculerStock(): void
	{
		$this->quantite = $this->stocks()->sum('quantite');
		$this->save();
	}

	public function image($id_forme_condi)
	{
		$image = Image::where('id_produit', $this->id_produit)
			->where('id_forme_condi', $id_forme_condi)
			->first();

		return $image->url ?? 'fichier/image/meringues/oups.png';
	}

	public function isnouveaute(): bool        { return (bool) $this->nouveaute; }
	public function islive(): bool             { return (bool) $this->live; }
	public function isdispo_emporter(): bool   { return (bool) $this->dispo_emporter; }
	public function isdispo_expedition(): bool { return (bool) $this->dispo_expedition; }
}