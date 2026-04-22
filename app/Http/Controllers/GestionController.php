<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Produit;
use App\Models\Forme;
use App\Models\Conditionnement;
use App\Models\Parfum;
use App\Models\Forme_Condi;
use App\Models\Rayon;


class GestionController extends Controller
{
	public function index()
	{
		$type_donnee = [
			['value' => 'produits',         'text' => 'Produits'],
			['value' => 'formes',           'text' => 'Formes'],
			['value' => 'conditionnements', 'text' => 'Conditionnements'],
		];

		$datas = session('import_preview', null);
		session()->forget('import_preview');

		// Boutique
		$boutique     = Boutique::first();
		$stock_total  = $boutique->stock_total;   // ← colonne renommée
		$nom_boutique = $boutique->nom_boutique;

		// Produits
		$produits    = Produit::with(['parfum', 'forme_condi.forme', 'forme_condi.conditionnement'])->get();
		$nb_produits = $produits->count();

        // Rayons pour le select
        $rayons = Rayon::all();

		// Données annexes
		$formes           = Forme::all();
		$conditionnements = Conditionnement::all();
		$parfums          = Parfum::all();
		$forme_condi      = Forme_Condi::with(['forme', 'conditionnement'])->get();

		return view('gestion', compact(
			'type_donnee',
			'datas',
			'stock_total',
			'nom_boutique',
			'nb_produits',
			'produits',
			'formes',
			'conditionnements',
			'parfums',
			'forme_condi',
            'rayons',
            'boutique',
		));
	}
}
