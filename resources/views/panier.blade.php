@include('templet.header', [
	'titre' => 'Mon Panier',
	'note'  => 'Meringues & Douceurs Artisanales',
	'title' => 'Panier'
])

@vite('resources/css/panier.css')

@php
	$nbArticles = $panier->lignes->sum('quantite');
	$plural     = fn (int $n) => $n . ' article' . ($n > 1 ? 's' : '');
@endphp

<main class="cart">
	<div class="cart__inner">

		{{-- ── Étapes ── --}}
		<ol class="cart-steps" aria-label="Étapes de commande">
			<li class="cart-steps__item cart-steps__item--active" aria-current="step">
				<span class="cart-steps__num">1</span>
				<span class="cart-steps__label">Panier</span>
			</li>
			<li class="cart-steps__line" aria-hidden="true"></li>
			<li class="cart-steps__item">
				<span class="cart-steps__num">2</span>
				<span class="cart-steps__label">Livraison &amp; paiement</span>
			</li>
			<li class="cart-steps__line" aria-hidden="true"></li>
			<li class="cart-steps__item">
				<span class="cart-steps__num">3</span>
				<span class="cart-steps__label">Confirmation</span>
			</li>
		</ol>

		{{-- ── Message succès ── --}}
		@if(session('success'))
			<div class="cart-alert" role="status" data-cart-alert>
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
				{{ session('success') }}
			</div>
		@endif

		@if($panier->lignes->isEmpty())

			{{-- ── Panier vide ── --}}
			<section class="cart-empty">
				<div class="cart-empty__icon">
					<svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
				</div>
				<h2 class="cart-empty__title">Votre panier est vide</h2>
				<p class="cart-empty__text">Découvrez nos meringues artisanales normandes et composez votre sélection de douceurs.</p>
				<a href="{{ route('shop.index', 1) }}" class="cart-cta">
					Découvrir la boutique
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
				</a>
			</section>

		@else

			<div class="cart-layout">

				{{-- ══ LISTE DES ARTICLES ══ --}}
				<section class="cart-card" aria-labelledby="cart-title">
					<div class="cart-card__head">
						<h2 class="cart-card__title" id="cart-title">
							Votre sélection
							<span class="cart-card__count" data-cart-count-label>{{ $plural($nbArticles) }}</span>
						</h2>

						<form action="{{ route('panier.vider') }}" method="POST"
							  onsubmit="return confirm('Vider tout le panier ?')">
							@csrf
							<button type="submit" class="cart-link-btn">
								<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
								Vider le panier
							</button>
						</form>
					</div>

					<div class="cart-items" id="cart-items">
						@foreach($panier->lignes as $ligne)
							@php $img = $ligne->produit->image($ligne->id_forme_condi); @endphp

							<article class="cart-item" data-line="{{ $ligne->id_ligne }}">

								{{-- Image --}}
								<div class="cart-item__media">
									@if($img)
										<img src="{{ asset($img) }}"
											 alt="{{ $ligne->produit->nom_produit }}"
											 loading="lazy"
											 onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'cart-item__placeholder',innerHTML:'&#9825;'}))">
									@else
										<div class="cart-item__placeholder" aria-hidden="true">
											<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
										</div>
									@endif
								</div>

								{{-- Infos --}}
								<div class="cart-item__info">
									<h3 class="cart-item__name">
										{{ $ligne->produit->forme->nom_forme }} — {{ $ligne->produit->parfum->nom_parfum }}
									</h3>
									<span class="cart-item__tag">{{ $ligne->formeCondi->conditionnement->type }}</span>
									<p class="cart-item__unit">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} € l'unité</p>
								</div>

								{{-- Quantité --}}
								<form action="{{ route('panier.modifier', $ligne->id_ligne) }}" method="POST"
									  class="cart-item__qty js-qty-form">
									@csrf
									@method('PATCH')
									<div class="qty">
										<button type="button" class="qty__btn" data-step="-1"
												aria-label="Diminuer la quantité" @disabled($ligne->quantite <= 1)>
											<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14"/></svg>
										</button>
										<input type="number" name="quantite" class="qty__input"
											   value="{{ $ligne->quantite }}" min="1" max="99"
											   aria-label="Quantité">
										<button type="button" class="qty__btn" data-step="1"
												aria-label="Augmenter la quantité" @disabled($ligne->quantite >= 99)>
											<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
										</button>
										<button type="submit" class="qty__submit">OK</button>
									</div>
								</form>

								{{-- Sous-total --}}
								<div class="cart-item__total" data-line-total>
									{{ number_format($ligne->prix_unitaire * $ligne->quantite, 2, ',', ' ') }} €
								</div>

								{{-- Supprimer --}}
								<form action="{{ route('panier.supprimer', $ligne->id_ligne) }}" method="POST" class="js-remove-form">
									@csrf
									@method('DELETE')
									<button type="submit" class="cart-item__remove"
											aria-label="Retirer {{ $ligne->produit->nom_produit }} du panier">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
									</button>
								</form>

							</article>
						@endforeach
					</div>

					<div class="cart-card__foot">
						<a href="{{ route('shop.index', 1) }}" class="cart-back">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
							Continuer mes achats
						</a>
					</div>
				</section>

				{{-- ══ RÉCAPITULATIF ══ --}}
				<aside class="cart-summary" aria-labelledby="summary-title">
					<h2 class="cart-summary__title" id="summary-title">Récapitulatif</h2>

					<div class="cart-summary__row">
						<span>Articles</span>
						<strong data-cart-count-label>{{ $plural($nbArticles) }}</strong>
					</div>
					<div class="cart-summary__row">
						<span>Sous-total</span>
						<strong><span data-cart-total>{{ number_format($panier->total(), 2, ',', ' ') }}</span> €</strong>
					</div>
					<div class="cart-summary__row">
						<span>Livraison</span>
						<em>Choisie à l'étape suivante</em>
					</div>

					<div class="cart-summary__total">
						<span>Total</span>
						<span class="cart-summary__amount"><span data-cart-total>{{ number_format($panier->total(), 2, ',', ' ') }}</span> €</span>
					</div>

					@auth
						<a href="{{ route('checkout.index') }}" class="cart-cta">
							Passer la commande
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
						</a>
					@else
						<a href="{{ route('login') }}" class="cart-cta">
							Se connecter pour commander
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
						</a>
						<p class="cart-summary__hint">
							Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a><br>
							Votre panier sera conservé après connexion.
						</p>
					@endauth

					<ul class="cart-trust">
						<li>
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
							Paiement sécurisé par carte bancaire
						</li>
						<li>
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
							Meringues artisanales, fabriquées en Normandie
						</li>
					</ul>
				</aside>

			</div>
		@endif
	</div>
</main>

<script>
(function () {
	document.documentElement.classList.add('js');

	const root = document.querySelector('.cart');
	if (!root) return;

	const label = n => n + ' article' + (n > 1 ? 's' : '');
	const debounce = (fn, ms) => { let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), ms); }; };

	// Message de succès : disparition automatique
	const alertBox = root.querySelector('[data-cart-alert]');
	if (alertBox) setTimeout(() => {
		alertBox.style.transition = 'opacity .4s';
		alertBox.style.opacity = '0';
		setTimeout(() => alertBox.remove(), 400);
	}, 4000);

	// Envoi AJAX (JSON) d'un formulaire
	async function send(form) {
		const res = await fetch(form.action, {
			method: 'POST',
			headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
			body: new FormData(form),
			credentials: 'same-origin',
		});
		if (!res.ok) throw new Error(res.status);
		return res.json();
	}

	// Mise à jour des totaux affichés
	function refreshTotals(data) {
		root.querySelectorAll('[data-cart-total]').forEach(el => el.textContent = data.total);
		root.querySelectorAll('[data-cart-count-label]').forEach(el => el.textContent = label(data.nb_articles));
	}

	// ── Quantité ────────────────────────────────────────────────
	root.querySelectorAll('.js-qty-form').forEach(form => {
		const input = form.querySelector('.qty__input');
		const minus = form.querySelector('[data-step="-1"]');
		const plus  = form.querySelector('[data-step="1"]');
		const item  = form.closest('.cart-item');

		const clamp = () => Math.min(99, Math.max(1, parseInt(input.value, 10) || 1));
		const syncButtons = () => {
			minus.disabled = clamp() <= 1;
			plus.disabled  = clamp() >= 99;
		};

		const save = debounce(async () => {
			item.classList.add('is-loading');
			try {
				const data = await send(form);
				item.querySelector('[data-line-total]').textContent = data.ligne_total + ' €';
				refreshTotals(data);
			} catch (e) {
				form.submit(); // repli : envoi classique
			} finally {
				item.classList.remove('is-loading');
			}
		}, 350);

		form.addEventListener('click', e => {
			const btn = e.target.closest('.qty__btn');
			if (!btn) return;
			input.value = clamp() + parseInt(btn.dataset.step, 10);
			input.value = clamp();
			syncButtons();
			save();
		});

		input.addEventListener('change', () => { input.value = clamp(); syncButtons(); save(); });
		form.addEventListener('submit', e => { e.preventDefault(); input.value = clamp(); save(); });
	});

	// ── Suppression ─────────────────────────────────────────────
	root.querySelectorAll('.js-remove-form').forEach(form => {
		form.addEventListener('submit', async e => {
			e.preventDefault();
			const item = form.closest('.cart-item');
			try {
				const data = await send(form);
				item.classList.add('is-removing');
				setTimeout(() => {
					if (data.nb_lignes === 0) return location.reload();
					item.remove();
					refreshTotals(data);
				}, 300);
			} catch (err) {
				form.submit();
			}
		});
	});
})();
</script>

@include('templet.footer')

</body>
</html>