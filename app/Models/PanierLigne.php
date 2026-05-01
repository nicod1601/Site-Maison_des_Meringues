<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanierLigne extends Model
{
	protected $table = 'panier_ligne';
	protected $primaryKey = 'id_ligne';

	protected $fillable = [
		'id_panier',
		'id_produit',
		'id_forme_condi',
		'quantite',
		'prix_unitaire'
	];

	public function produit()
	{
		return $this->belongsTo(Produit::class, 'id_produit', 'id_produit');
	}

	public function formeCondi()
	{
		return $this->belongsTo(Forme_Condi::class, 'id_forme_condi', 'id_forme_condi');
	}

	public function panier()
	{
		return $this->belongsTo(Panier::class, 'id_panier', 'id_panier');
	}

	public function getListProduits(): array
	{
		$produits = [];
		foreach ($this->produit as $produit) {
			$produits[] = [
				'id_produit' => $produit->id_produit,
				'nom_produit' => $produit->nom_produit,
				'quantite' => $this->quantite,
				'prix_unitaire' => $this->prix_unitaire,
			];
		}
		return $produits;
	}
}
