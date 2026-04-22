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

		$produit = Produit::create([
			'id_parfum'       => $request->id_parfum,
			'id_forme_condi'  => $request->id_forme_condi,
			'id_theme'        => $request->id_theme ?? null,
			'description'     => $request->description,
			'quantite'        => $request->quantite,
		]);

		// Lier au rayon via la table pivot
		$idRayonFinal = $request->id_rayon ?? $idRayon;
		$produit->rayons()->attach($idRayonFinal);

		// Recalcule le stock du rayon et de la boutique
		$rayon = Rayon::find($idRayonFinal);
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

		// Récupérer tous les rayons liés avant suppression
		$rayonIds = $produit->rayons()->pluck('rayon.id_rayon');

		// Suppression (cascade supprime aussi produit_rayon)
		$produit->delete();

		// Recalculer les stocks de tous les rayons concernés
		foreach ($rayonIds as $rayonId) {
			$rayon = Rayon::find($rayonId);
			if ($rayon) $rayon->recalculerStock();
		}

		$boutique = Boutique::first();
		$boutique->stock_total = Rayon::sum('stock_total_rayon');
		$boutique->save();

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

			// Lier les produits existants qui ont ces thèmes
			$produits = Produit::whereIn('id_theme', $request->id_themes)->get();

			foreach ($produits as $produit) {
				// Vérifier que le produit n'est pas déjà lié à ce rayon
				if (!$produit->rayons()->where('rayon.id_rayon', $rayon->id_rayon)->exists()) {
					$produit->rayons()->attach($rayon->id_rayon);
				}
			}

			$rayon->recalculerStock();

			$boutique = Boutique::first();
			$boutique->stock_total = Rayon::sum('stock_total_rayon');
			$boutique->save();
		}

		return redirect()->back()->with('success', 'Rayon créé avec succès !');
	}

	public function destroyRayon($id)
	{
		$rayon = Rayon::findOrFail($id);

		// Détacher tous les produits du rayon (supprime les lignes pivot)
		$rayon->produits()->detach();

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
			'icone'     => $request->icone   ?? '🎨',
			'couleur'   => $request->couleur ?? '#C0395A',
		]);

		return redirect()->back()->with('success', 'Thème créé avec succès !');
	}

	public function destroyTheme($id)
	{
		// Détacher les rayons liés avant suppression
		$theme = Theme::findOrFail($id);
		$theme->rayons()->detach();
		$theme->delete();

		return redirect()->back()->with('success', 'Thème supprimé.');
	}
}
