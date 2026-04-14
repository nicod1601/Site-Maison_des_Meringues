<?php

namespace App\Http\Controllers;

class GestionController extends Controller
{
	public function index()
	{
		/*$annees = [];
		$annee = date("Y");

		for($i = $annee - 5; $i < $annee; $i++)
		{
			$annees[$annee - $i] = ['value' => $i, 'text' => $i];
		}*/

		$type_donnee = [];

		$type_donnee = [
			['value' => "produits", 'text' => "Produits"],
			['value' => "formes", 'text' => "Formes"],
			['value' => "conditionnements", 'text' => "Conditionnements"]
		];

		$fichiers = [];

		return view('gestion', compact('type_donnee', 'fichiers'));
	}
}
