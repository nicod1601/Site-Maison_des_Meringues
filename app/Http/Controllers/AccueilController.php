<?php

namespace App\Http\Controllers;
use App\Models\Image;
use App\Models\Produit;

class AccueilController extends Controller
{
	public function index()
	{
		$produits = Produit::all();
		$lien = "";
		$images = Image::all();
		$trouve = false;

		return view('index', compact('produits', 'images'));
	}
}
