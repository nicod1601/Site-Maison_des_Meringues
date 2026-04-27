<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Rayon;
use App\Models\Theme;
use App\Models\Produit;
use Illuminate\Http\Request;

class ShopController extends Controller
{
	/**
	 * Page principale de la boutique.
	 *
	 * URL : /shop/{id_boutique}
	 * Paramètres GET optionnels :
	 *   - rayon  : id_rayon  → filtre les produits à un seul rayon
	 *   - theme  : id_theme  → filtre par thème (dans le rayon sélectionné)
	 *   - filtre : nouveaute | emporter | expedition | dispo
	 *   - tri    : prix_asc | prix_desc | nom_asc | nouveautes
	 */
	public function index(Request $request, int $id_boutique)
	{
		// ── 1. Boutique ───────────────────────────────────────────
		$boutique = Boutique::with('rayons')->findOrFail($id_boutique);

		// ── 2. Rayons de cette boutique (pour la nav) ─────────────
		$rayons = Rayon::where('id_boutique', $id_boutique)
			->withCount('produits')
			->get();

		// ── 3. Rayon actif ────────────────────────────────────────
		$rayonActif = null;
		if ($request->filled('rayon')) {
			$rayonActif = Rayon::where('id_rayon', $request->rayon)
				->where('id_boutique', $id_boutique)
				->firstOrFail();
		}

		// ── 4. Construction de la requête produits ────────────────
		$query = Produit::with([
				'theme',
				'forme.forme_condis.conditionnement', // ✅ chemin correct via forme
				'parfum',
			])
			->where('live', true);

		// Filtre par rayon
		if ($rayonActif) {
			$query->whereHas('rayons', fn($q) =>
				$q->where('rayon.id_rayon', $rayonActif->id_rayon)
			);
		} else {
			// Tous les rayons de cette boutique
			$query->whereHas('rayons', fn($q) =>
				$q->where('rayon.id_boutique', $id_boutique)
			);
		}

		// Filtre par thème
		if ($request->filled('theme')) {
			$query->where('id_theme', $request->theme);
		}

		// Filtres rapides
		switch ($request->filtre) {
			case 'nouveaute':
				$query->where('nouveaute', true);
				break;
			case 'emporter':
				$query->where('dispo_emporter', true);
				break;
			case 'expedition':
				$query->where('dispo_expedition', true);
				break;
			case 'dispo':
				$query->where('quantite', '>', 0);
				break;
		}

		// Tri SQL (sauf prix, géré en PHP après le get())
		switch ($request->tri) {
			case 'nom_asc':
				$query->orderBy('nom_produit', 'asc');
				break;
			case 'nouveautes':
				$query->orderByDesc('nouveaute')->orderBy('nom_produit');
				break;
			default:
				$query->orderBy('nom_produit', 'asc');
		}

		$produits = $query->get();

		// ✅ Tri par prix en PHP (le prix vient du premier forme_condi de la forme)
		// produit.id_forme_condi n'existe pas → on trie après le get()
		if (in_array($request->tri, ['prix_asc', 'prix_desc'])) {
			$produits = $produits->sortBy(
				fn($p) => $p->forme?->forme_condis->first()?->prix ?? PHP_INT_MAX,
				SORT_REGULAR,
				$request->tri === 'prix_desc'
			)->values();
		}

		$totalProduits = $produits->count();

		// ── 5. Regroupement par thème ─────────────────────────────
		$produitsParTheme = $produits->groupBy(fn($p) => $p->id_theme ?? 0);

		$themesAffiches = collect();

		// D'abord les produits avec thème (triés par nom_theme)
		$themes = Theme::whereIn('id_theme', $produits->pluck('id_theme')->filter()->unique())
			->orderBy('nom_theme')
			->get();

		foreach ($themes as $theme) {
			$theme->produits_affiches = $produitsParTheme->get($theme->id_theme, collect());
			$themesAffiches->push($theme);
		}

		// Ensuite les produits sans thème
		$sanTheme = $produitsParTheme->get(0, collect());
		if ($sanTheme->isNotEmpty()) {
			$themesAffiches->push((object)[
				'id_theme'          => null,
				'nom_theme'         => 'Autres créations',
				'icone'             => null,
				'couleur'           => null,
				'produits_affiches' => $sanTheme,
			]);
		}

		// ── 6. Thèmes disponibles pour le filtre ──────────────────
		$themesDisponibles = $themes;

		return view('shop', compact(
			'boutique',
			'rayons',
			'rayonActif',
			'themesAffiches',
			'themesDisponibles',
			'totalProduits',
			'produits'
		));
	}
}
