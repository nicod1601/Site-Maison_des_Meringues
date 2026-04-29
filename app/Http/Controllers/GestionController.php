<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Produit;
use App\Models\Forme;
use App\Models\Conditionnement;
use App\Models\Parfum;
use App\Models\Forme_Condi;
use App\Models\Rayon;
use App\Models\Theme;
use App\Models\Event;
use Illuminate\Http\Request;

class GestionController extends Controller
{
	public function index(Request $request)
	{
		$datas = session('import_preview', null);
		session()->forget('import_preview');

		$boutique     = Boutique::first();
		$stock_total  = $boutique->stock_total;
		$nom_boutique = $boutique->nom_boutique;

		$rayons = Rayon::with('events')->get();
		$themes = Theme::all();
		$events = Event::all();

		$rayonId = $request->query('rayon');

		$query = Produit::with([
			'parfum',
			'forme.forme_condis.conditionnement',
			'rayons',
			'theme',
			'events',
		]);

		if ($rayonId && $rayonId !== '-1') {
			$query->whereHas('rayons', function ($q) use ($rayonId) {
				$q->where('rayon.id_rayon', $rayonId);
			});
		}

		$produits    = $query->get();
		$nb_produits = $produits->count();

		$formes           = Forme::all();
		$conditionnements = Conditionnement::all();
		$parfums          = Parfum::all();
		$forme_condi      = Forme_Condi::with(['forme', 'conditionnement'])->get();

		$produitsByTheme = $themes->mapWithKeys(function ($theme) {
			return [
				$theme->id_theme => Produit::where('id_theme', $theme->id_theme)->count()
			];
		});

		$produitsByEvent = $events->mapWithKeys(function ($event) {
			return [
				$event->id_event => $event->produits()->count()
			];
		});

		return view('gestion', compact(
			'datas', 'stock_total', 'nom_boutique', 'nb_produits',
			'produits', 'formes', 'conditionnements', 'parfums',
			'forme_condi', 'rayons', 'themes', 'events', 'boutique', 'rayonId',
			'produitsByTheme', 'produitsByEvent',
		));
	}
}
