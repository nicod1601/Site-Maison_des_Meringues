@include('templet.header', [
	'titre' => 'La '.$boutique->nom_boutique,
	'note'  => 'Douceurs Artisanales',
	'title' => 'Shop'
])

@vite('resources/css/boutique.css')


{{-- ── VÉRIFICATION RAYONS ACTIFS ──────────────────────── --}}
@php
	$rayonsActifs      = $rayons->filter(fn($r) => $r->islive());
	$rayonsActifsCount = $rayonsActifs->count();
	$rayonSelectionne  = $rayonActif?->id_rayon;
	$totalArticles     = $ListeProduits ? $ListeProduits->sum('quantite') : 0;
@endphp


{{-- ── EVENT CSS ──────────────────────────────────────────── --}}
@php
	$isNoel = $rayonActif?->events->contains(fn($e) => strtolower($e->nom_event) === 'noël') ?? false;
@endphp

@if($isNoel)
	@vite('resources/css/event/noel.css')
@endif


{{-- ── NAVIGATION DES RAYONS ───────────────────────────── --}}
<nav class="rayon-nav" aria-label="Rayons de la boutique">
	<div class="rayon-nav__inner">
		@foreach($rayons as $rayon)
			@if($rayon->islive())
				<a href="/shop/{{ $boutique->id_boutique }}?rayon={{ $rayon->id_rayon }}"
					class="rayon-nav__item {{ $rayonSelectionne == $rayon->id_rayon ? 'active' : '' }}">
					{{ $rayon->nom_rayon }}
				</a>
			@endif
		@endforeach
	</div>

	{{-- PANIER À DROITE --}}
	<a href="/panier"
	   class="cart-bubble"
	   id="cart-bubble"
	   aria-label="Voir mon panier ({{ $totalArticles }} article{{ $totalArticles > 1 ? 's' : '' }})">
		<span>🛒</span>
		@if($totalArticles > 0)
			<span class="cart-bubble__badge" id="cart-badge">{{ $totalArticles }}</span>
		@else
			<span class="cart-bubble__badge" id="cart-badge" style="display:none;">0</span>
		@endif
	</a>

</nav>


{{-- ── CORPS ───────────────────────────────────────────────── --}}
<main class="boutique-body {{ $isNoel ? 'event--noel' : '' }}" id="boutique-contenu">

	@if($rayonsActifsCount === 0)
		{{-- Aucun rayon actif --}}
		<div class="boutique-empty">
			<p class="boutique-empty__icon">⏳</p>
			<p class="boutique-empty__title">En cours de traitement</p>
			<p class="boutique-empty__text">Merci de patienter pour l'ajout des produits</p>
		</div>
	@else

		{{-- ── SECTIONS PAR THÈME ──────────────────────────────── --}}
		@forelse($themesAffiches as $themeIndex => $theme)

			@if($themeIndex > 0)
				<div class="theme-divider" aria-hidden="true">
					<span class="theme-divider__icon">✦</span>
				</div>
			@endif

			<section class="theme-section"
					 id="theme-{{ $theme->id_theme ?? 'autres' }}"
					 aria-labelledby="titre-theme-{{ $theme->id_theme ?? 'autres' }}">

				{{-- En-tête du thème --}}
				<div class="theme-header">
					<div class="theme-header__left">
						<p class="theme-header__eyebrow">Thème</p>
						<h2 class="theme-header__title" id="titre-theme-{{ $theme->id_theme ?? 'autres' }}">
							@if($theme->icone)
								<span aria-hidden="true" style="margin-right:.3em;">{{ $theme->icone }}</span>
							@endif
							<span @if($theme->couleur) style="color:{{ $theme->couleur }}" @endif>
								{{ $theme->nom_theme }}
							</span>
						</h2>
						<div class="theme-header__ornament" aria-hidden="true">
							<span class="theme-header__ornament-line"
								@if($theme->couleur) style="background:{{ $theme->couleur }}" @endif></span>
							<span class="theme-header__ornament-dot"
								@if($theme->couleur) style="background:{{ $theme->couleur }}" @endif></span>
							<span class="theme-header__ornament-line"
								@if($theme->couleur) style="background:{{ $theme->couleur }}" @endif></span>
						</div>
					</div>
				</div>

				{{-- Grille produits --}}
				<div class="products-grid" id="grille-theme-{{ $theme->id_theme ?? 'autres' }}">

					@foreach($theme->produits_affiches as $produit)
						@foreach($produit->forme->forme_condis as $fc)
						<article class="boutique-card" aria-label="{{ $produit->nom_produit }}">

							{{-- Image --}}
							<div class="boutique-card__img-wrap">
								<div class="boutique-card__img-placeholder" aria-hidden="true"
									@if($theme->couleur)
										style="background:linear-gradient(135deg,{{ $theme->couleur }}22 0%,{{ $theme->couleur }}44 100%);"
									@endif>
									<img
										src="{{ asset($produit->image($fc->id_forme_condi)) }}"
										class="boutique-card__img"
										loading="lazy"
										decoding="async"
										fetchpriority="low"

										onerror="
											console.log('Erreur image ❌');
											this.src='/fichier/image/meringues/oups.png";
										"
									>
								</div>
								<div class="boutique-card__badges">
									@if($produit->nouveaute)
										<span class="badge badge--new">Nouveau</span>
									@endif
									@if($produit->quantite == 0)
										<span class="badge badge--close">Épuisé</span>
									@endif
								</div>
							</div>

							{{-- Corps --}}
							<div class="boutique-card__body">

								<p class="boutique-card__theme">
									{{ $fc->conditionnement->type }}
								</p>

								<h3 class="boutique-card__name">
									{{ $produit->forme->nom_forme }} — {{ $produit->parfum->nom_parfum }}
								</h3>

								@if($produit->description && $produit->description != 'Aucune description')
									<p class="boutique-card__desc" style="margin-top:.2rem;">
										{{ Str::limit($produit->description, 80) }}
									</p>
								@endif

								<div style="display:flex;gap:4px;flex-wrap:wrap;margin-top:auto;padding-top:var(--space-sm);">
									@if($produit->dispo_emporter)
										<span class="badge badge--cream" style="font-size:10px;">🛍 Emporter</span>
									@endif
									@if($produit->dispo_expedition && $fc->conditionnement->type == 'individuel')
										<span class="badge badge--cream" style="font-size:10px;">📦 Expédition</span>
									@endif
								</div>

								<div class="boutique-card__footer">
									<div>
										@if($fc->prix)
											<p class="boutique-card__price">
												{{ number_format($fc->prix, 2, ',', ' ') }} €
											</p>
										@else
											<p class="boutique-card__price" style="color:var(--color-text-muted);">— €</p>
										@endif
									</div>

									@php
										$dispo = match($fc->conditionnement->type) {
											'individuel' => $produit->quantite > 0,
											'sachet_de_10' => $produit->quantite >= 10,
											'sachet_de_4'  => $produit->quantite >= 4,
											'boite_de_8'   => $produit->quantite >= 8,
											default        => false,
										};
									@endphp

									@if($dispo)
										<form class="form-ajout-panier" action="{{ route('panier.ajouter') }}" method="POST">
											@csrf
											<input type="hidden" name="id_produit"     value="{{ $produit->id_produit }}">
											<input type="hidden" name="id_forme_condi" value="{{ $fc->id_forme_condi }}">
											<input type="hidden" name="quantite"       value="1">
											<button type="submit" class="boutique-card__add-btn"
													aria-label="Ajouter {{ $produit->nom_produit }} au panier"
													title="Ajouter au panier">+</button>
										</form>
									@else
										<span class="boutique-card__add-btn"
											style="background:var(--color-border);cursor:not-allowed;"
											aria-disabled="true">✕</span>
									@endif

								</div>
							</div>
						</article>
						@endforeach
					@endforeach

				</div>
				{{-- /products-grid --}}

			</section>

		@empty

			<div class="boutique-empty">
				<div class="boutique-empty__icon">🎂</div>
				<h2 class="boutique-empty__title">Aucun produit disponible</h2>
				<p>
					@if(request()->hasAny(['filtre', 'theme', 'rayon']))
						Aucun produit ne correspond à ces filtres.<br>
						<a href="{{ route('shop', $boutique->id_boutique) }}"
						   class="btn btn--ghost btn--sm mt-md">Réinitialiser les filtres</a>
					@else
						Notre boutique est en cours de préparation. Revenez bientôt&nbsp;!
					@endif
				</p>
			</div>

		@endforelse

	@endif {{-- Fin vérification rayons actifs --}}

</main>


{{-- ── FOOTER ──────────────────────────────────────────────── --}}
@include('templet.footer')


{{-- ── SCRIPTS ─────────────────────────────────────────────── --}}
{{-- !! Tri ──────────────────────────────────────────────────── --}}
<script>
	function appliquerTri(valeur) {
		const url = new URL(window.location.href);
		url.searchParams.set('tri', valeur);
		window.location.href = url.toString();
	}

	function voirProduit(id) {
		window.location.href = '/produit/' + id;
	}

	// ── Ajout au panier en AJAX (garde la position de scroll) ──
	document.addEventListener('submit', function (e) {
		const form = e.target.closest('.form-ajout-panier');
		if (!form) return;

		e.preventDefault();
		console.log('AJAX déclenché ✓', form.action);

		const btn   = form.querySelector('button[type="submit"]');
		const data  = new FormData(form);
		const badge = document.getElementById('cart-badge');

		btn.disabled         = true;
		btn.textContent      = '✓';
		btn.style.background = 'var(--color-gold)';

		fetch(form.action, {
			method: 'POST',
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'X-CSRF-TOKEN':     data.get('_token'),
				'Accept':           'application/json',
			},
			body: data,
		})
		.then(res => {
			if (!res.ok) throw new Error('Erreur serveur');
			return res.json();
		})
		.then(json => {
			const total = json.total_quantite ?? (parseInt(badge.textContent || '0') + 1);
			badge.textContent   = total;
			badge.style.display = 'flex';

			badge.classList.remove('pop');
			void badge.offsetWidth;
			badge.classList.add('pop');

			setTimeout(() => {
				btn.disabled         = false;
				btn.textContent      = '+';
				btn.style.background = '';
			}, 1200);
		})
		.catch(() => {
			btn.disabled = false;
			form.submit();
		});
	});

	document.querySelectorAll('.boutique-card__img').forEach(img => {

		/*img.addEventListener('load', () => {
			console.log('OK :', img.src);
		});*/

		img.addEventListener('error', () => {
			//console.log('ERREUR :', img.src);
			img.src = "/fichier/image/meringues/oups.png";
		});

	});

</script>

</body>
</html>
