<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use App\Models\Produit;
use App\Models\Forme_Condi;
use Illuminate\Http\Request;

class PanierController extends Controller
{
	/** Quantité maximale par ligne. */
	private const QUANTITE_MAX = 99;

	// Panier de la personne en cours (compte connecté ou visiteur)
	private function getPanier(): Panier
	{
		return Panier::courant();
	}

	// Afficher le panier
	public function index()
	{
		// Lecture seule : afficher un panier vide ne crée rien en base
		$panier = Panier::courant(creer: false);

		if ($panier->exists) {
			$panier->load(
				'lignes.produit.parfum',
				'lignes.produit.forme',
				'lignes.formeCondi.conditionnement'
			);
		}

		return view('panier', compact('panier'));
	}

	// Ajouter un produit
	public function ajouter(Request $request)
	{
		$request->validate([
			'id_produit'     => 'required|exists:produit,id_produit',
			'id_forme_condi' => 'required|exists:forme_condi,id_forme_condi',
			'quantite'       => 'required|integer|min:1|max:' . self::QUANTITE_MAX,
		]);

		$idFormeCondi = (int) $request->input('id_forme_condi');
		$idProduit    = (int) $request->input('id_produit');
		$quantite     = (int) $request->input('quantite');

		$produit    = Produit::findOrFail($idProduit);
		$formeCondi = Forme_Condi::findOrFail($idFormeCondi);

		// Le produit doit être en vente et le conditionnement doit exister pour sa forme
		if (! $produit->islive() || (int) $formeCondi->id_forme !== (int) $produit->id_forme) {
			return $this->refus($request, 'Ce produit n\'est pas disponible dans ce conditionnement.');
		}

		$panier = $this->getPanier();

		$ligne = $panier->lignes()
			->where('id_produit', $idProduit)
			->where('id_forme_condi', $idFormeCondi)
			->first();

		if ($ligne) {
			$ligne->update(['quantite' => min($ligne->quantite + $quantite, self::QUANTITE_MAX)]);
		} else {
			$panier->lignes()->create([
				'id_produit'     => $idProduit,
				'id_forme_condi' => $idFormeCondi,
				'quantite'       => $quantite,
				'prix_unitaire'  => $formeCondi->prix,
			]);
		}

		// ── Réponse AJAX (boutique) ───────────────────────────────
		if ($request->ajax() || $request->wantsJson()) {
			return response()->json([
				'success'        => true,
				'total_quantite' => $panier->lignes()->sum('quantite'),
			]);
		}

		// ── Fallback classique ────────────────────────────────────
		return redirect()->back()->with('success', 'Produit ajouté au panier !');
	}

	// Modifier la quantité (uniquement une ligne de MON panier)
	public function modifier(Request $request, int $id)
	{
		$request->validate(['quantite' => 'required|integer|min:1|max:' . self::QUANTITE_MAX]);

		$ligne = $this->getPanier()->lignes()->findOrFail($id);

		// Le prix suit le catalogue : un prix changé en gestion s'applique à la mise à jour
		$ligne->update([
			'quantite'      => $request->quantite,
			'prix_unitaire' => $ligne->formeCondi->prix ?? $ligne->prix_unitaire,
		]);

		return redirect()->back()->with('success', 'Quantité mise à jour.');
	}

	// Supprimer une ligne (uniquement de MON panier)
	public function supprimer(int $id)
	{
		$this->getPanier()->lignes()->findOrFail($id)->delete();

		return redirect()->back()->with('success', 'Produit retiré du panier.');
	}

	// Vider le panier
	public function vider()
	{
		$this->getPanier()->lignes()->delete();
		return redirect()->back()->with('success', 'Panier vidé.');
	}

	// Refus homogène AJAX / formulaire classique
	private function refus(Request $request, string $message)
	{
		if ($request->ajax() || $request->wantsJson()) {
			return response()->json(['success' => false, 'message' => $message], 422);
		}

		return redirect()->back()->with('error', $message);
	}
}
