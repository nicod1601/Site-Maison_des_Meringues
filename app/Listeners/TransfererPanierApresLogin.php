<?php

namespace App\Listeners;

use App\Models\Panier;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;

class TransfererPanierApresLogin
{
    public function handle(Login $event): void
    {
        // Uniquement le panier de CE visiteur (jeton en session), jamais ceux des autres.
        $jeton = session(Panier::SESSION_KEY);

        if (! $jeton) {
            return;
        }

        $panierSession = Panier::where('session_id', $jeton)
            ->whereNull('user_id')
            ->with('lignes')
            ->first();

        session()->forget(Panier::SESSION_KEY);

        if (! $panierSession) {
            return;
        }

        DB::transaction(function () use ($panierSession, $event) {
            $panierUser = Panier::firstOrCreate(['user_id' => $event->user->id]);

            foreach ($panierSession->lignes as $ligne) {
                $existant = $panierUser->lignes()
                    ->where('id_produit',     $ligne->id_produit)
                    ->where('id_forme_condi', $ligne->id_forme_condi)
                    ->first();

                if ($existant) {
                    $existant->update([
                        'quantite' => min($existant->quantite + $ligne->quantite, 99),
                    ]);
                } else {
                    $panierUser->lignes()->create($ligne->only([
                        'id_produit', 'id_forme_condi', 'quantite', 'prix_unitaire',
                    ]));
                }
            }

            $panierSession->lignes()->delete();
            $panierSession->delete();
        });
    }
}
