<?php

namespace App\Http\Controllers;
use App\Models\Image;
use App\Models\Produit;

class AccueilController extends Controller
{
	public function index()
	{
		$produits = Produit::with(['forme', 'parfum'])->get();
		$images   = Image::all();

		return view('index', compact('produits', 'images'));
	}
}
