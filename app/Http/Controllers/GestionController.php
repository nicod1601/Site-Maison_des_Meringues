<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Produit;
use App\Models\Forme;
use App\Models\Conditionnement;
use App\Models\Parfum;
use App\Models\Forme_Condi;


class GestionController extends Controller
{
	public function index()
	{
		$type_donnee = [
			['value' => "produits",         'text' => "Produits"],
			['value' => "formes",           'text' => "Formes"],
			['value' => "conditionnements", 'text' => "Conditionnements"],
		];

		$datas = session('import_preview', null);

		session()->forget('import_preview');

        //Boutique Info
		$boutique     = Boutique::first();
		$stock_total  = $boutique->stock_total;
		$nom_boutique = $boutique->nom_boutique;

        //Produit Info
        $produits = Produit::all();
        $nb_produits = $produits->count();

        // partie donnee
		$produits = Produit::all();
		$formes = Forme::all();
		$conditionnements = Conditionnement::all();
		$parfums = Parfum::all();
		$forme_condi = Forme_Condi::all();


		return view('gestion', compact('type_donnee', 'datas', 'stock_total', 'nom_boutique','nb_produits',
            'produits',
            'formes',
            'conditionnements',
            'parfums',
            'forme_condi'
        ));
	}
}
