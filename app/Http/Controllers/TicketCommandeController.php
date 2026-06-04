<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Commande;

class TicketCommandeController extends Controller
{
	// ── Liste des commandes ───────────────────────────────────────────
	public function index()
	{
		$user    = Auth::user();
		$isAdmin = $user->isAdmin();

		// L'admin voit TOUTES les commandes, le client seulement les siennes
		$query = Commande::with(['lignes', 'user'])->latest();

		if (! $isAdmin) {
			$query->where('user_id', $user->id);
		}

		$commandes = $query->get();

		return view('ticket-commande', compact('commandes', 'isAdmin'));
	}

	// ── Admin : marquer une commande comme terminée ───────────────────
	public function terminer(int $id)
	{
		$user = Auth::user();

		// Sécurité : seul un admin peut terminer
		abort_unless($user->isAdmin(), 403);

		$commande = Commande::findOrFail($id);
		$commande->update(['statut' => 'terminee']);

		return back()->with('success', 'Commande #' . $commande->reference . ' marquée comme terminée.');
	}

	// ── (suppression désactivée pour les clients) ─────────────────────
	// La route destroy est conservée mais uniquement pour les admins
	public function destroy(int $id)
	{
		abort_unless(Auth::user()->isAdmin(), 403, 'Action non autorisée.');

		$commande = Commande::findOrFail($id);

		$commande->lignes()->delete();
		$commande->delete();

		return redirect()->route('ticketCommande')
			->with('success', 'Commande supprimée avec succès.');
	}
}
