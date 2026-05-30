<?php

namespace App\Listeners;

use App\Models\Panier;
use Illuminate\Auth\Events\Login;

class TransfererPanierApresLogin
{
    public function handle(Login $event): void
    {
        // ← CORRECTION : on cherche TOUS les paniers session anonymes
        // (session()->getId() peut avoir changé après regenerate() au login)
        $paniersSession = Panier::whereNotNull('session_id')
            ->whereNull('user_id')
            ->with('lignes')
            ->get();

        if ($paniersSession->isEmpty()) return;

        // Panier du compte connecté
        $panierUser = Panier::firstOrCreate(['user_id' => $event->user->id]);

        foreach ($paniersSession as $panierSession) {
            foreach ($panierSession->lignes as $ligne) {
                $existant = $panierUser->lignes()
                    ->where('id_produit',     $ligne->id_produit)
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

            // Supprimer le panier anonyme après fusion
            $panierSession->lignes()->delete();
            $panierSession->delete();
        }
    }
}
