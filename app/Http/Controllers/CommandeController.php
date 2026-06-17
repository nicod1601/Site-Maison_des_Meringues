<?php
namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Panier;
use DansMaCulotte\Monetico\Monetico;
use DansMaCulotte\Monetico\Resources\BillingAddressResource;
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

		// Vérification expédition
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

		// ── Juste une référence unique, on stocke en session ──────────
		$reference = 'CMD-' . strtoupper(Str::random(8)) . '-' . time();

		\DB::table('pending_checkouts')->insert([
			'reference'      => $reference,
			'user_id'        => auth()->id(),
			'montant'        => $panier->total(),
			'mode_livraison' => $request->mode_livraison,
			'created_at'     => now(),
			'updated_at'     => now(),
		]);

		session([
			'monetico_reference'    => $reference,
			'monetico_user_id'      => auth()->id(),
			'monetico_mode_livraison' => $request->mode_livraison,
		]);

		// ── Préparer Monetico ──────────────────────────────────────────
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

		$purchase->setBillingAddress(new BillingAddressResource([
			'firstName'    => auth()->user()->prenom ?? 'Client',
			'lastName'     => auth()->user()->nom ?? 'Client',
			'addressLine1' => auth()->user()->adresse ?? '1 rue inconnue',
			'city'         => auth()->user()->ville ?? 'Paris',
			'postalCode'   => auth()->user()->code_postal ?? '75000',
			'country'      => 'FR',
		]));

		$fields = $monetico->getFields($purchase);

		$url = config('services.monetico.test_mode')
			? 'https://p.monetico-services.com/test/paiement.cgi'
			: 'https://p.monetico-services.com/paiement.cgi';

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
		$codRetour = $request->input('code-retour', '');
		$reference = $request->input('reference', '');

		if ($codRetour !== 'paiement') {
			return response('OK', 200);
		}

		$pending = \DB::table('pending_checkouts')->where('reference', $reference)->first();

		if (! $pending) {
			return response('OK', 200);
		}

		$panier = Panier::with([
			'lignes.produit.forme',
			'lignes.produit.parfum',
			'lignes.formeCondi.conditionnement',
		])->where('user_id', $pending->user_id)->first();

		if (! $panier) {
			return response('OK', 200);
		}

		$commande = Commande::create([
			'user_id'            => $pending->user_id,
			'reference'          => $reference,
			'montant'            => $pending->montant,
			'mode_livraison'     => $pending->mode_livraison,
			'statut'             => 'payee',
			'monetico_reference' => $request->input('numauto'),
		]);

		foreach ($panier->lignes as $ligne) {
			$commande->lignes()->create([
				'designation'      => $ligne->produit->forme->nom_forme . ' — ' . $ligne->produit->parfum->nom_parfum,
				'sous_designation' => $ligne->formeCondi->conditionnement->type,
				'quantite'         => $ligne->quantite,
				'prix_unitaire'    => $ligne->prix_unitaire,
			]);
		}

		$panier->lignes()->delete();

		// Nettoie la table temporaire
		\DB::table('pending_checkouts')->where('reference', $reference)->delete();

		return response('OK', 200);
	}
}
