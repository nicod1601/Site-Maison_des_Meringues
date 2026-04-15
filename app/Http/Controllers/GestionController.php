<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Produit;


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


		return view('gestion', compact('type_donnee', 'datas', 'stock_total', 'nom_boutique','nb_produits'));
	}
}
