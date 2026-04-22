<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Rayon;
use App\Models\Theme;
use App\Models\Boutique;
use Illuminate\Http\Request;

class CreationController extends Controller
{
	// ── PRODUITS ────────────────────────────────────────────────────────

	public function nvproduit(Request $request, $idRayon)
	{
		$request->validate([
			'id_parfum'      => 'required|exists:parfum,id_parfum',
			'id_forme_condi' => 'required|exists:forme_condi,id_forme_condi',
			'quantite'       => 'required|integer|min:0',
		]);

		Produit::create([
			'id_parfum'       => $request->id_parfum,
			'id_forme_condi'  => $request->id_forme_condi,
			'id_rayon'        => $request->id_rayon ?? $idRayon,
			'description'     => $request->description,
			'quantite'        => $request->quantite,
		]);

		// Recalcule le stock du rayon et de la boutique
		$rayon = Rayon::find($request->id_rayon ?? $idRayon);
		if ($rayon) {
			$rayon->recalculerStock();
			$boutique = Boutique::first();
			$boutique->stock_total = Rayon::sum('stock_total_rayon');
			$boutique->save();
		}

		return redirect()->back()->with('success', 'Produit créé avec succès !');
	}

	public function destroy($id)
	{
		$produit = Produit::findOrFail($id);
		$idRayon = $produit->id_rayon;
		$produit->delete();

		// Recalcule les stocks
		$rayon = Rayon::find($idRayon);
		if ($rayon) {
			$rayon->recalculerStock();
			$boutique = Boutique::first();
			$boutique->stock_total = Rayon::sum('stock_total_rayon');
			$boutique->save();
		}

		return redirect()->back();
	}

	// ── RAYONS ───────────────────────────────────────────────────────────

	public function nvrayon(Request $request)
	{
		$request->validate([
			'nom_rayon'   => 'required|string|max:255',
			'id_boutique' => 'required|exists:boutique,id_boutique',
			'id_themes'   => 'nullable|array',
			'id_themes.*' => 'exists:theme,id_theme',
		]);

		$rayon = Rayon::create([
			'nom_rayon'         => $request->nom_rayon,
			'id_boutique'       => $request->id_boutique,
			'stock_total_rayon' => 0,
		]);

		if ($request->id_themes) {
			// Attacher les thèmes au nouveau rayon
			$rayon->themes()->sync($request->id_themes);

			// Récupérer les produits des autres rayons ayant ces thèmes
			$produits = Produit::whereIn('id_theme', $request->id_themes)
				->where('id_rayon', '!=', $rayon->id_rayon)
				->get();

			foreach ($produits as $produit) {
				// Vérifier qu'une copie n'existe pas déjà dans ce rayon
				// (même parfum + même forme_condi)
				$dejaPresent = Produit::where('id_rayon', $rayon->id_rayon)
					->where('id_parfum', $produit->id_parfum)
					->where('id_forme_condi', $produit->id_forme_condi)
					->exists();

				if (!$dejaPresent) {
					Produit::create([
						'id_forme_condi'   => $produit->id_forme_condi,
						'id_parfum'        => $produit->id_parfum,
						'id_rayon'         => $rayon->id_rayon,
						'id_theme'         => $produit->id_theme,
						'description'      => $produit->description,
						'quantite'         => $produit->quantite,
						'nouveaute'        => $produit->nouveaute,
						'live'             => $produit->live,
						'dispo_emporter'   => $produit->dispo_emporter,
						'dispo_expedition' => $produit->dispo_expedition,
					]);
				}
			}

			// Recalculer le stock du nouveau rayon
			$rayon->recalculerStock();

			// Recalculer le stock boutique
			$boutique = Boutique::first();
			$boutique->stock_total = Rayon::sum('stock_total_rayon');
			$boutique->save();
		}

		return redirect()->back()->with('success', 'Rayon créé avec succès !');
	}
	public function destroyRayon($id)
	{
		$rayon = Rayon::findOrFail($id);

		// Déplace les produits du rayon vers null avant suppression
		Produit::where('id_rayon', $id)->update(['id_rayon' => null]);

		$rayon->delete();

		// Recalcule le stock boutique
		$boutique = Boutique::first();
		$boutique->stock_total = Rayon::sum('stock_total_rayon');
		$boutique->save();

		return redirect()->back()->with('success', 'Rayon supprimé.');
	}

	// ── THÈMES ───────────────────────────────────────────────────────────

	public function nvtheme(Request $request)
	{
		$request->validate([
			'nom_theme' => 'required|string|max:255',
			'icone'     => 'nullable|string|max:10',
			'couleur'   => 'nullable|string|max:7',
		]);

		Theme::create([
			'nom_theme' => $request->nom_theme,
			'icone'     => $request->icone     ?? '🎨',
			'couleur'   => $request->couleur   ?? '#C0395A',
		]);

		return redirect()->back()->with('success', 'Thème créé avec succès !');
	}

	public function destroyTheme($id)
	{
		// Met les rayons liés à null avant suppression
		Rayon::where('id_theme', $id)->update(['id_theme' => null]);
		Theme::findOrFail($id)->delete();

		return redirect()->back()->with('success', 'Thème supprimé.');
	}
}
