<?php
namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Panier;
use DansMaCulotte\Monetico\Monetico;
use DansMaCulotte\Monetico\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommandeController extends Controller
{
    // ── Page récapitulatif avant paiement ─────────────────────────
    public function index()
    {
        $panier = Panier::with([
            'lignes.produit.forme',
            'lignes.produit.parfum',
            'lignes.formeCondi.conditionnement',
        ])->where('user_id', auth()->id())->firstOrFail();

        if ($panier->lignes->isEmpty()) {
            return redirect()->route('panier.index')
                ->with('error', 'Votre panier est vide.');
        }

        return view('checkout.index', compact('panier'));
    }

    // ── Lancer le paiement Monetico ───────────────────────────────
    public function payer(Request $request)
    {
        $panier = Panier::with('lignes')
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($panier->lignes->isEmpty()) {
            return redirect()->route('panier.index');
        }

        // Créer la commande en BDD avec statut "en_attente"
        $reference = 'CMD-' . strtoupper(Str::random(8)) . '-' . time();

        Commande::create([
            'user_id'   => auth()->id(),
            'reference' => $reference,
            'montant'   => $panier->total(),
            'statut'    => 'en_attente',
        ]);

        // Préparer Monetico
        $monetico = new Monetico(
            config('services.monetico.tpe'),
            config('services.monetico.cle'),
            config('services.monetico.societe'),
        );

        $purchase = new PurchaseRequest([
            'reference'   => $reference,
            'description' => 'Commande La Maison des Meringues',
            'language'    => 'FR',
            'email'       => auth()->user()->email,
            'amount'      => $panier->total(),
            'currency'    => 'EUR',
            'dateTime'    => new \DateTime(),
            'successUrl'  => route('checkout.success'),
            'errorUrl'    => route('checkout.error'),
        ]);

        $fields = $monetico->getFields($purchase);
        $url    = Monetico::getUrl();

        return view('checkout.redirect', compact('fields', 'url'));
    }

    // ── Retour succès (côté navigateur) ───────────────────────────
    public function success(Request $request)
    {
        return view('checkout.success');
    }

    // ── Retour erreur (côté navigateur) ───────────────────────────
    public function error()
    {
        return view('checkout.error');
    }

    // ── Retour serveur Monetico (POST automatique) ─────────────────
    // C'est ici qu'on valide VRAIMENT le paiement
    public function retour(Request $request)
    {
        $monetico = new Monetico(
            config('services.monetico.tpe'),
            config('services.monetico.cle'),
            config('services.monetico.societe'),
        );

        // Vérifier la signature Monetico
        $data = $request->all();

        // Le code retour "paiement" = succès chez Monetico
        $codRetour = $request->input('code-retour', '');
        $reference = $request->input('reference', '');

        $commande = Commande::where('reference', $reference)->first();

        if ($commande && $codRetour === 'paiement') {
            // Paiement confirmé
            $commande->update([
                'statut'              => 'payee',
                'monetico_reference'  => $request->input('numauto'),
            ]);

            // Vider le panier
            $panier = Panier::where('user_id', $commande->user_id)->first();
            if ($panier) {
                $panier->lignes()->delete();
            }
        } elseif ($commande) {
            $commande->update(['statut' => 'echouee']);
        }

        // Monetico attend "OK" en réponse
        return response('OK', 200);
    }
}
