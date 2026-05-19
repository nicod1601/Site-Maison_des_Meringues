<?php

namespace App\Http\Controllers;

use App\Models\Forme;
use App\Models\Conditionnement;
use App\Models\Parfum;



class ProController extends Controller
{
	public function index()
	{
		$formes = Forme::all();
		$conditionnements = Conditionnement::all();
		$parfums = Parfum::all();

		return view('pro', compact('formes', 'conditionnements', 'parfums'));
	}
}
