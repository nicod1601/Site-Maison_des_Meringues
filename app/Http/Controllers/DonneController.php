<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Produit;
use App\Models\Forme;
use App\Models\Conditionnement;
use App\Models\Parfum;
use App\Models\Forme_Condi;

class DonneController extends Controller
{
	public function index()
	{
		$boutique = Boutique::first();

		$produits = Produit::all();
		$formes = Forme::all();
		$conditionnements = Conditionnement::all();
		$parfums = Parfum::all();
		$forme_condi = Forme_Condi::all();

		return view('data', compact(
			'boutique',
			'produits',
			'formes',
			'conditionnements',
			'parfums',
			'forme_condi'
		));
	}
}
