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
			'lignes.formeCondi.forme',
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
		$request->validate([
			'mode_livraison' => ['required', 'in:livraison,expedition'],
		]);

		$panier = Panier::with([
			'lignes.formeCondi.forme',
			'lignes.formeCondi.conditionnement',
		])->where('user_id', auth()->id())->firstOrFail();

		if ($panier->lignes->isEmpty()) {
			return redirect()->route('panier.index');
		}

		// ── Vérification côté serveur si expédition demandée ──────
		if ($request->mode_livraison === 'expedition') {
			$expeditionAutorisee = $panier->lignes->every(function ($ligne) {
				$nomForme  = strtolower($ligne->formeCondi->forme->nom_forme ?? '');
				$typeCondi = strtolower($ligne->formeCondi->conditionnement->type ?? '');
				return str_contains($nomForme, 'mini') && $typeCondi === 'individuel';
			});

			if (! $expeditionAutorisee) {
				return back()->with('error', 'L\'expédition postale n\'est disponible que pour les meringues mini individuelles.');
			}
		}

		// ── Créer la commande en BDD ───────────────────────────────
		$reference = 'CMD-' . strtoupper(Str::random(8)) . '-' . time();

		$commande = Commande::create([
			'user_id'        => auth()->id(),
			'reference'      => $reference,
			'montant'        => $panier->total(),
			'mode_livraison' => $request->mode_livraison,
			'statut'         => 'en_attente',
		]);

		// ── Créer les lignes de commande ───────────────────────────────
		foreach ($panier->lignes as $ligne) {
			$commande->lignes()->create([
				'designation'     => $ligne->produit->forme->nom_forme . ' — ' . $ligne->produit->parfum->nom_parfum,
				'sous_designation'=> $ligne->formeCondi->conditionnement->type,
				'quantite'        => $ligne->quantite,
				'prix_unitaire'   => $ligne->prix_unitaire,
			]);
		}

		// ── Préparer Monetico ──────────────────────────────────────
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
	public function retour(Request $request)
	{
		$monetico = new Monetico(
			config('services.monetico.tpe'),
			config('services.monetico.cle'),
			config('services.monetico.societe'),
		);

		$codRetour = $request->input('code-retour', '');
		$reference = $request->input('reference', '');

		$commande = Commande::where('reference', $reference)->first();

		if ($commande && $codRetour === 'paiement') {
			$commande->update([
				'statut'             => 'payee',
				'monetico_reference' => $request->input('numauto'),
			]);

			// Vider le panier
			$panier = Panier::where('user_id', $commande->user_id)->first();
			if ($panier) {
				$panier->lignes()->delete();
			}
		} elseif ($commande) {
			$commande->update(['statut' => 'echouee']);
		}

		return response('OK', 200);
	}
}
