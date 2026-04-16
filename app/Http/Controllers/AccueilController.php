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
				$lien = public_path('fichier/image/meringues/mini/' . $produit->parfum->nom_parfum . '.png');
				$url = 'fichier/image/meringues/mini/' . $produit->parfum->nom_parfum . '.png';
			} else {
				$lien = public_path('fichier/image/meringues/nid/' . $produit->parfum->nom_parfum . '.png');
				$url = 'fichier/image/meringues/nid/' . $produit->parfum->nom_parfum . '.png';
			}

			if (file_exists($lien)) {
				$images[$produit->id_produit] = $url;
			}
		}

		return view('index', compact('produits', 'images'));
	}
}
