<?php
namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Panier;
use App\Models\PendingCheckout;
use DansMaCulotte\Monetico\Monetico;
use DansMaCulotte\Monetico\Resources\BillingAddressResource;
use DansMaCulotte\Monetico\Requests\PurchaseRequest;
use DansMaCulotte\Monetico\Responses\PurchaseResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

		// ── Photo du panier : prix recalculés depuis le catalogue ──────
		// (on ne fait pas confiance au prix figé dans la ligne du panier)
		$panier->load(['lignes.produit.forme', 'lignes.produit.parfum']);

		$lignes = [];
		$total  = 0;

		foreach ($panier->lignes as $ligne) {
			$prix = (float) $ligne->formeCondi->prix;
			$total += $prix * $ligne->quantite;

			$lignes[] = [
				'id_ligne'         => $ligne->id_ligne,
				'designation'      => ($ligne->produit->forme->nom_forme ?? 'Meringue') . ' — ' . ($ligne->produit->parfum->nom_parfum ?? ''),
				'sous_designation' => $ligne->formeCondi->conditionnement->type ?? null,
				'quantite'         => $ligne->quantite,
				'prix_unitaire'    => $prix,
			];
		}

		$montant   = number_format($total, 2, '.', '');
		$reference = 'CMD-' . strtoupper(Str::random(8)) . '-' . time();

		PendingCheckout::create([
			'reference'      => $reference,
			'user_id'        => auth()->id(),
			'montant'        => $montant,
			'mode_livraison' => $request->mode_livraison,
			'lignes'         => $lignes,
		]);

		// ── Préparer Monetico ──────────────────────────────────────────
		$monetico = $this->monetico();

		$user = auth()->user();

		// L'utilisateur n'a qu'un champ « name » : on le sépare en prénom / nom
		[$prenom, $nom] = array_pad(explode(' ', trim($user->name), 2), 2, null);

		$purchase = new PurchaseRequest([
			'reference'   => $reference,
			'description' => 'Commande La Maison des Meringues',
			'language'    => 'FR',
			'email'       => $user->email,
			'amount'      => $montant,
			'currency'    => 'EUR',
			'dateTime'    => new \DateTime(),
			'successUrl'  => route('checkout.success'),
			'errorUrl'    => route('checkout.error'),
		]);

		$purchase->setBillingAddress(new BillingAddressResource([
			'firstName'    => $prenom ?: 'Client',
			'lastName'     => $nom ?: ($prenom ?: 'Client'),
			'addressLine1' => $user->adresse ?: '1 rue inconnue',
			'city'         => $user->ville ?: 'Paris',
			'postalCode'   => $user->code_postal ?: '75000',
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
	// Route exemptée de CSRF (bootstrap/app.php) : c'est le sceau MAC qui prouve
	// que la requête vient bien de Monetico.
	public function retour(Request $request)
	{
		// 1. Seuls les paiements acceptés nous intéressent
		//    (« payetest » n'existe qu'en mode test). Rien à créer pour le reste :
		//    on acquitte simplement pour que Monetico ne réessaie pas.
		$codesAcceptes = config('services.monetico.test_mode') ? ['paiement', 'payetest'] : ['paiement'];

		if (! in_array($request->input('code-retour'), $codesAcceptes, true)) {
			return $this->acquittement();
		}

		// 2. Authenticité : sceau MAC (empêche de forger un faux retour de paiement)
		try {
			$response = new PurchaseResponse($request->all());

			if (! $this->monetico()->validate($response)) {
				Log::warning('Monetico : sceau invalide', ['ip' => $request->ip(), 'reference' => $request->input('reference')]);
				return response('Sceau invalide', 400);
			}
		} catch (\Throwable $e) {
			Log::warning('Monetico : retour illisible — ' . $e->getMessage(), ['ip' => $request->ip()]);
			return response('Requête invalide', 400);
		}

		// 3. Création de la commande : une seule fois, même si Monetico rappelle
		DB::transaction(function () use ($response) {
			$pending = PendingCheckout::where('reference', $response->reference)->lockForUpdate()->first();

			if (! $pending) {
				return; // déjà traité (ou référence inconnue)
			}

			// 4. Le montant payé doit correspondre au montant attendu
			$montantPaye = round((float) preg_replace('/[^0-9.]/', '', str_replace(',', '.', $response->amount)), 2);

			if (abs($montantPaye - (float) $pending->montant) > 0.001) {
				Log::error('Monetico : montant incohérent', [
					'reference' => $pending->reference,
					'attendu'   => $pending->montant,
					'recu'      => $response->amount,
				]);
				return;
			}

			$commande = Commande::create([
				'user_id'            => $pending->user_id,
				'reference'          => $pending->reference,
				'montant'            => $pending->montant,
				'mode_livraison'     => $pending->mode_livraison,
				'statut'             => 'payee',
				'monetico_reference' => $response->authNumber,
			]);

			$lignes = $pending->lignes ?? [];

			foreach ($lignes as $ligne) {
				$commande->lignes()->create([
					'designation'      => $ligne['designation'],
					'sous_designation' => $ligne['sous_designation'],
					'quantite'         => $ligne['quantite'],
					'prix_unitaire'    => $ligne['prix_unitaire'],
				]);
			}

			// On ne vide que les lignes réellement payées
			Panier::where('user_id', $pending->user_id)->first()
				?->lignes()
				->whereIn('id_ligne', collect($lignes)->pluck('id_ligne'))
				->delete();

			$pending->delete();
		});

		return $this->acquittement();
	}

	// Réponse attendue par Monetico pour considérer le retour comme reçu
	private function acquittement()
	{
		return response("version=2\ncdr=0\n", 200)->header('Content-Type', 'text/plain');
	}

	private function monetico(): Monetico
	{
		return new Monetico(
			config('services.monetico.tpe'),
			config('services.monetico.cle'),
			config('services.monetico.societe'),
		);
	}
}
