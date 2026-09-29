@include('templet.header', [
	'titre' => 'Finaliser ma commande',
	'note'  => 'Vérifiez votre commande avant le paiement',
	'title' => 'Checkout',
])

@vite('resources/css/checkout.css')
@include('checkout._icons')

@php
	$user = auth()->user();

	// Expédition postale : uniquement si TOUT le panier est en mini individuel
	$expeditionAutorisee = $panier->lignes->every(function ($ligne) {
		$formeCondi = $ligne->formeCondi;
		$nomForme   = strtolower($formeCondi->forme->nom_forme ?? '');
		$typeCondi  = strtolower($formeCondi->conditionnement->type ?? '');
		return str_contains($nomForme, 'mini') && $typeCondi === 'individuel';
	});

	$tauxTVA    = 0.10;
	$totalTTC   = $panier->total();
	$totalHT    = $totalTTC / (1 + $tauxTVA);
	$montantTVA = $totalTTC - $totalHT;
	$nbArticles = $panier->lignes->sum('quantite');

	$modeInitial = old('mode_livraison', 'livraison');
	if ($modeInitial === 'expedition' && ! $expeditionAutorisee) {
		$modeInitial = 'livraison';
	}

	$adresseComplete = filled($user->adresse) && filled($user->ville) && filled($user->code_postal);
	$imageSecours    = asset('fichier/image/meringues/oups.webp');
@endphp

<main class="co">
	<form id="co-form" action="{{ route('checkout.payer') }}" method="POST">
		@csrf

		<div class="co__inner">

			@include('checkout._steps', ['etape' => 2])

			@if(session('error') || $errors->any())
				<div class="co-alert co-alert--error" role="alert">
					{!! coicon('alert') !!}
					<div>{{ session('error') ?? $errors->first() }}</div>
				</div>
			@endif

			<div class="co-layout">

				{{-- ══════════ COLONNE GAUCHE ══════════ --}}
				<div class="co-main">

					<a href="{{ route('panier.index') }}" class="co-back">
						{!! coicon('chevron-left') !!} Retour au panier
					</a>

					{{-- ── Articles ── --}}
					<section class="co-card" aria-labelledby="co-articles-title">
						<div class="co-card__head">
							<h2 class="co-card__title" id="co-articles-title">
								<span class="co-card__icon">{!! coicon('cart') !!}</span>
								Vos articles
								<span class="co-card__count">{{ $nbArticles }}</span>
							</h2>
							<a href="{{ route('panier.index') }}" class="co-link">{!! coicon('pen') !!} Modifier</a>
						</div>

						@foreach($panier->lignes as $ligne)
							<div class="co-line">
								<div class="co-line__img">
									<img src="{{ asset($ligne->produit->image($ligne->id_forme_condi)) }}"
										 alt="{{ $ligne->produit->forme->nom_forme }}"
										 loading="lazy"
										 onerror="this.onerror=null;this.src='{{ $imageSecours }}'">
								</div>
								<div>
									<p class="co-line__name">
										{{ $ligne->produit->forme->nom_forme }} — {{ $ligne->produit->parfum->nom_parfum }}
									</p>
									<div class="co-line__meta">
										<span class="co-line__tag">{{ $ligne->formeCondi->conditionnement->type }}</span>
										<span class="co-line__qty">× {{ $ligne->quantite }}</span>
									</div>
								</div>
								<div class="co-line__price">
									{{ number_format($ligne->prix_unitaire * $ligne->quantite, 2, ',', ' ') }} €
									<div class="co-line__unit">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} € l'unité</div>
								</div>
							</div>
						@endforeach
					</section>

					{{-- ── Mode de livraison ── --}}
					<section class="co-card" aria-labelledby="co-livraison-title">
						<div class="co-card__head">
							<h2 class="co-card__title" id="co-livraison-title">
								<span class="co-card__icon">{!! coicon('package') !!}</span>
								Mode de livraison
							</h2>
						</div>

						<div class="co-modes" role="radiogroup" aria-labelledby="co-livraison-title">

							{{-- Click & Collect : toujours disponible --}}
							<label class="co-mode">
								<input type="radio" class="co-mode__input" name="mode_livraison" value="livraison"
									   {{ $modeInitial === 'livraison' ? 'checked' : '' }}>
								<span class="co-mode__box">
									<span class="co-mode__top">
										<span class="co-mode__icon">{!! coicon('bike') !!}</span>
										<span class="co-mode__badge">Gratuit</span>
									</span>
									<span class="co-mode__title">
										<span class="co-mode__radio"></span>
										Click &amp; Collect
									</span>
									<span class="co-mode__desc">
										Retrait en main propre ou livraison locale.<br>
										Toujours disponible.
									</span>
								</span>
							</label>

							{{-- Expédition postale --}}
							<label class="co-mode {{ $expeditionAutorisee ? '' : 'co-mode--disabled' }}">
								<input type="radio" class="co-mode__input" name="mode_livraison" value="expedition"
									   {{ $modeInitial === 'expedition' ? 'checked' : '' }}
									   {{ $expeditionAutorisee ? '' : 'disabled' }}>
								<span class="co-mode__box">
									<span class="co-mode__top">
										<span class="co-mode__icon">{!! coicon('package') !!}</span>
										@if($expeditionAutorisee)
											<span class="co-mode__badge co-mode__badge--info">Colissimo</span>
										@else
											<span class="co-mode__badge co-mode__badge--warn">Indisponible</span>
										@endif
									</span>
									<span class="co-mode__title">
										<span class="co-mode__radio"></span>
										Expédition postale
									</span>
									<span class="co-mode__desc">
										@if($expeditionAutorisee)
											Envoi sécurisé partout en France.<br>
											Délai estimé : 2 – 4 jours ouvrés.
										@else
											Réservée aux meringues <strong>mini individuelles</strong> uniquement.
										@endif
									</span>
								</span>
							</label>

						</div>

						@unless($expeditionAutorisee)
							<div class="co-notice">
								{!! coicon('info') !!}
								<span>
									L'expédition postale n'est possible que si votre panier contient
									uniquement des meringues au format <strong>mini individuel</strong>.
									Modifiez votre panier pour débloquer cette option.
								</span>
							</div>
						@endunless
					</section>

					{{-- ── Coordonnées ── --}}
					<section class="co-card" aria-labelledby="co-coord-title">
						<div class="co-card__head">
							<h2 class="co-card__title" id="co-coord-title">
								<span class="co-card__icon">{!! coicon('user') !!}</span>
								Vos coordonnées
							</h2>
							<a href="{{ route('settings') }}" class="co-link">{!! coicon('pen') !!} Modifier</a>
						</div>

						<dl class="co-info">
							<div class="co-info__row">
								<dt>{!! coicon('user') !!} Nom</dt>
								<dd>{{ $user->name }}</dd>
							</div>
							<div class="co-info__row">
								<dt>{!! coicon('mail') !!} E-mail</dt>
								<dd>{{ $user->email }}</dd>
							</div>
							@if(filled($user->phone))
								<div class="co-info__row">
									<dt>{!! coicon('phone') !!} Téléphone</dt>
									<dd>{{ $user->phone }}</dd>
								</div>
							@endif
							@if($adresseComplete)
								<div class="co-info__row co-info__row--wide">
									<dt>{!! coicon('home') !!} Adresse</dt>
									<dd>{{ $user->adresse }}, {{ $user->code_postal }} {{ $user->ville }}</dd>
								</div>
							@endif
						</dl>

						@unless($adresseComplete)
							<div class="co-notice">
								{!! coicon('info') !!}
								<span>
									Votre adresse n'est pas renseignée. Pensez à
									<a href="{{ route('settings') }}" class="co-link">compléter votre profil</a>
									pour faciliter le suivi de votre commande.
								</span>
							</div>
						@endunless
					</section>

				</div>

				{{-- ══════════ COLONNE DROITE — RÉCAP ══════════ --}}
				<aside class="co-summary" aria-labelledby="co-recap-title">
					<div class="co-recap">

						<h2 class="co-recap__title" id="co-recap-title">Récapitulatif</h2>

						<div class="co-recap__thumbs">
							@foreach($panier->lignes as $ligne)
								<div class="co-recap__thumb" title="{{ $ligne->produit->forme->nom_forme }} — {{ $ligne->produit->parfum->nom_parfum }}">
									<img src="{{ asset($ligne->produit->image($ligne->id_forme_condi)) }}" alt=""
										 loading="lazy"
										 onerror="this.onerror=null;this.src='{{ $imageSecours }}'">
									<span class="co-recap__thumb-qty">{{ $ligne->quantite }}</span>
								</div>
							@endforeach
						</div>

						<div class="co-recap__row">
							<span>Sous-total HT</span>
							<strong>{{ number_format($totalHT, 2, ',', ' ') }} €</strong>
						</div>
						<div class="co-recap__row co-recap__row--sub">
							<span>TVA (10 %)</span>
							<strong>{{ number_format($montantTVA, 2, ',', ' ') }} €</strong>
						</div>
						<div class="co-recap__row" id="co-recap-livraison">
							<span>Livraison</span>
							<strong class="is-free">Gratuite</strong>
						</div>

						<hr class="co-recap__divider">

						<div class="co-recap__total">
							<span class="co-recap__total-label">Total TTC</span>
							<span class="co-recap__total-amount">{{ number_format($totalTTC, 2, ',', ' ') }} €</span>
						</div>

						<button type="submit" class="co-pay" id="co-pay">
							{!! coicon('lock') !!}
							Payer {{ number_format($totalTTC, 2, ',', ' ') }} €
						</button>

						<div class="co-secure">
							{!! coicon('shield') !!} Paiement sécurisé CIC Monetico
						</div>
						<div class="co-cards">CB · Visa · Mastercard · Apple Pay</div>
					</div>

					<div class="co-continue">
						<a href="{{ route('shop.index', 1) }}">Continuer mes achats</a>
					</div>
				</aside>

			</div>
		</div>

	</form>

	{{-- Écran de chargement (meringue) --}}
	<div class="co-loader" id="co-loader" role="status" aria-live="polite" aria-hidden="true">
		<div class="co-loader__box">
			<div class="co-meringue"></div>
			<p>Redirection vers le paiement sécurisé…<small>Merci de ne pas fermer cette page.</small></p>
		</div>
	</div>
</main>

<script>
(function () {
	const form   = document.getElementById('co-form');
	const btn    = document.getElementById('co-pay');
	const loader = document.getElementById('co-loader');
	const recap  = document.querySelector('#co-recap-livraison strong');
	let envoye   = false;

	// Mise à jour de la ligne « Livraison » du récapitulatif
	function majLivraison() {
		const choix = form.querySelector('input[name="mode_livraison"]:checked');
		if (!choix || !recap) return;
		const expedition = choix.value === 'expedition';
		recap.textContent = expedition ? 'Selon tarif Colissimo' : 'Gratuite';
		recap.classList.toggle('is-free', !expedition);
	}
	form.querySelectorAll('input[name="mode_livraison"]').forEach(r => r.addEventListener('change', majLivraison));
	majLivraison();

	// Envoi : écran de chargement + protection contre le double clic
	form.addEventListener('submit', function (e) {
		if (envoye) { e.preventDefault(); return; }
		envoye = true;
		btn.disabled = true;
		loader.classList.add('visible');
		loader.setAttribute('aria-hidden', 'false');
	});

	// Retour arrière depuis la banque (page restaurée depuis le cache) : on remet tout à zéro
	window.addEventListener('pageshow', function (e) {
		if (!e.persisted) return;
		envoye = false;
		btn.disabled = false;
		loader.classList.remove('visible');
		loader.setAttribute('aria-hidden', 'true');
	});
})();
</script>

@include('templet.footer')