<?php

namespace App\Http\Controllers;

class GestionController extends Controller
{
	public function index()
	{
		$annees = [];
		$annee = date("Y");

		for($i = $annee - 5; $i < $annee; $i++)
		{
			$annees[$annee - $i] = ['value' => $i, 'text' => $i];
		}

		$type_donnee = [];

		$type_donnee = [
			['value' => "boutique", 'text' => "Boutique"]
		];

		$fichiers = [];

		return view('gestion', compact('annees','type_donnee', 'fichiers'));
	}
}
