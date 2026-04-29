<?php

namespace App\Listeners;

use App\Models\Panier;
use Illuminate\Auth\Events\Login;

class TransfererPanierApresLogin
{
	public function handle(Login $event): void
	{
		$sessionId     = session()->getId();
		$panierSession = Panier::where('session_id', $sessionId)->with('lignes')->first();

		if (!$panierSession) return;

		// Panier du compte connecté
		$panierUser = Panier::firstOrCreate(['user_id' => $event->user->id]);

		// Fusionner les lignes
		foreach ($panierSession->lignes as $ligne) {
			$existant = $panierUser->lignes()
				->where('id_produit', $ligne->id_produit)
				->where('id_forme_condi', $ligne->id_forme_condi)
				->first();

			if ($existant) {
				$existant->increment('quantite', $ligne->quantite);
			} else {
				$panierUser->lignes()->create($ligne->only([
					'id_produit', 'id_forme_condi', 'quantite', 'prix_unitaire'
				]));
			}
		}

		// Supprimer le panier anonyme
		$panierSession->delete();
	}
}
