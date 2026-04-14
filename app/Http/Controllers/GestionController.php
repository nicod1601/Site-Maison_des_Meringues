<?php

namespace App\Http\Controllers;

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

		return view('gestion', compact('type_donnee', 'datas'));
	}
}
