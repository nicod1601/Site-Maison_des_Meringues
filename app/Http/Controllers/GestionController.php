<?php

namespace App\Http\Controllers;

class GestionController extends Controller
{
	public function index()
	{
		// On définit les données nécessaires à la vue gestion.blade.php
		$annees = [
			['value' => '2025', 'text' => '2025'],
			['value' => '2024', 'text' => '2024'],
			['value' => '2023', 'text' => '2023'],
		];

		$fichiers = []; // Tableau vide par défaut

		return view('gestion', compact('annees', 'fichiers'));
	}
}
