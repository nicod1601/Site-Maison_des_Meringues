<?php

namespace App\Http\Controllers;
use App\Models\Produit;

class AccueilController extends Controller
{
	public function index()
	{
		$produits = Produit::all();
		$lien = "";
		$images = [];
		$trouve = false;


		//images
		foreach ($produits as $produit) {
			if ($produit->forme_condi->forme->nom_forme == 'Mini') {
				$lien = 'fichier/image/meringues/mini/' . $produit->parfum->nom_parfum . '.png';
				$trouve = true;
			} else {
				$lien = 'fichier/image/meringues/nid/' . $produit->parfum->nom_parfum . '.png';
				$trouve = true;
			}

			if($trouve === true){
				$images[$produit->id_produit] = $lien;
				$trouve = false;
				$lien = "";
			}
		}

		return view('index', compact('produits', 'images'));
	}
}
