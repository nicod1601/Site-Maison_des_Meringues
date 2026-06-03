<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Commande;
use App\Models\CommandeLigne;

class TicketCommandeController extends Controller
{
	public function index()
	{
		$user = Auth::user();

		$commandes = Commande::with(['lignes', 'user'])
		->where('user_id', $user->id)
		->latest()
		->get();

		return view('ticket-commande', compact('commandes'));
	}

	public function destroy(int $id)
	{
		$commande = Commande::where('id_commande', $id)
			->where('user_id', Auth::id())
			->firstOrFail();

		$commande->lignes()->delete();
		$commande->delete();

		return redirect()->route('ticketCommande')
			->with('success', 'Commande supprimée avec succès.');
	}
}
