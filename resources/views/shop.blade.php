@include('templet.header', [
	'titre'      => 'La '.$boutique->nom_boutique,
	'note'       => 'Douceurs Artisanales',
	'title'      => 'Shop',
	'hideHeader' => true,
])

@vite('resources/css/boutique.css')

@php
if (!function_exists('sicon')) {
	function sicon($name, $class = '') {
		$icons = [
			'cart'    => '<circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.6 12.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.5L21 8H6"/>',
			'sparkle' => '<path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8Z"/><path d="M19 16v4M17 18h4"/>',
			'bag'     => '<path d="M6 7h12l1 13a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2Z"/><path d="M9 7V5a3 3 0 0 1 6 0v2"/>',
			'package' => '<path d="M21 8 12 3 3 8v8l9 5 9-5Z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/>',
			'check'   => '<path d="M20 6 9 17l-5-5"/>',
			'search'  => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/>',
			'x'       => '<path d="M18 6 6 18M6 6l12 12"/>',
			'clock'   => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/>',
			'grid'    => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
			'star'    => '<path d="M12 3l2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1-4.4-4.3 6.1-.9Z"/>',
		];
		$path = $icons[$name] ?? '';
		return '<svg class="bout-icon '.$class.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$path.'</svg>';
	}
}
@endphp


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
	$isPaques = $rayonActif?->events->contains(fn($e) => strtolower($e->nom_event) === 'pâques') ?? false;
	$isHalloween = $rayonActif?->events->contains(fn($e) => strtolower($e->nom_event) === 'halloween') ?? false;
@endphp

@if($isNoel)
	@vite('resources/css/event/noel.css')
@endif

@if($isPaques)
	@vite('resources/css/event/paques.css')
@endif

@if($isHalloween)
	@vite('resources/css/event/halloween.css')
@endif


{{-- ── HERO BOUTIQUE ────────────────────────────────────── --}}
<section class="boutique-hero">
	<div class="boutique-hero__inner">
		<p class="boutique-hero__eyebrow">{{ $boutique->nom_boutique }}</p>
		<h1 class="boutique-hero__title">
			Nos meringues<em>faites main, avec passion</em>
		</h1>
		<div class="boutique-hero__ornament" aria-hidden="true">
			<span class="boutique-hero__ornament-line"></span>
			<span class="boutique-hero__ornament-dot"></span>
			<span class="boutique-hero__ornament-line"></span>
		</div>
		<p class="boutique-hero__desc">
			Craquantes à l'extérieur, moelleuses à cœur — à déguster sur place, à emporter ou à recevoir chez vous.
		</p>
	</div>
</section>


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
		<span class="cart-bubble__icon">{!! sicon('cart') !!}</span>
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
			<p class="boutique-empty__icon">{!! sicon('clock') !!}</p>
			<p class="boutique-empty__title">En cours de traitement</p>
			<p class="boutique-empty__desc">Merci de patienter pour l'ajout des produits</p>
		</div>
	@else

		{{-- ── BARRE FILTRES / RECHERCHE ── --}}
		<div class="boutique-toolbar reveal">
			<div class="boutique-toolbar__left">
				<span class="boutique-toolbar__count" id="boutique-count" data-total="{{ $totalProduits }}">{{ $totalProduits }} produit{{ $totalProduits > 1 ? 's' : '' }}</span>
				<div class="boutique-toolbar__filters">
					@php
						$baseUrl = route('shop.index', $boutique->id_boutique) . '?' . http_build_query(array_filter([
							'rayon' => $rayonSelectionne,
							'theme' => request('theme'),
						]));
						$sep = str_contains($baseUrl, '?') ? '&' : '?';
					@endphp
					<a href="{{ $baseUrl }}" class="boutique-filter-chip {{ !request('filtre') ? 'active' : '' }}">{!! sicon('grid') !!} Tous</a>
					<a href="{{ $baseUrl }}{{ $sep }}filtre=nouveaute" class="boutique-filter-chip {{ request('filtre') === 'nouveaute' ? 'active' : '' }}">{!! sicon('sparkle') !!} Nouveautés</a>
					<a href="{{ $baseUrl }}{{ $sep }}filtre=emporter" class="boutique-filter-chip {{ request('filtre') === 'emporter' ? 'active' : '' }}">{!! sicon('bag') !!} À emporter</a>
					<a href="{{ $baseUrl }}{{ $sep }}filtre=expedition" class="boutique-filter-chip {{ request('filtre') === 'expedition' ? 'active' : '' }}">{!! sicon('package') !!} Expédition</a>
					<a href="{{ $baseUrl }}{{ $sep }}filtre=dispo" class="boutique-filter-chip {{ request('filtre') === 'dispo' ? 'active' : '' }}">{!! sicon('check') !!} Disponibles</a>
				</div>
			</div>
			<div class="boutique-toolbar__right">
				<div class="boutique-search">
					{!! sicon('search', 'boutique-search__icon') !!}
					<input type="search" id="boutique-search-input" placeholder="Rechercher une meringue, un parfum…" autocomplete="off" aria-label="Rechercher un produit">
					<button type="button" class="boutique-search__clear" id="boutique-search-clear" aria-label="Effacer la recherche">{!! sicon('x') !!}</button>
				</div>
			</div>
		</div>

		{{-- Message : aucun résultat de recherche (affiché par JS) --}}
		<div class="boutique-empty" id="boutique-search-empty" style="display:none;">
			<div class="boutique-empty__icon">{!! sicon('search') !!}</div>
			<h2 class="boutique-empty__title">Aucun résultat</h2>
			<p class="boutique-empty__desc">Aucune meringue ne correspond à votre recherche.</p>
		</div>

		{{-- ── SECTIONS PAR THÈME ──────────────────────────────── --}}
		@forelse($themesAffiches as $themeIndex => $theme)

			@if($themeIndex > 0)
				<div class="theme-divider" aria-hidden="true">
					<span class="theme-divider__icon">✦</span>
				</div>
			@endif

			<section class="theme-section reveal"
					 id="theme-{{ $theme->id_theme ?? 'autres' }}"
					 aria-labelledby="titre-theme-{{ $theme->id_theme ?? 'autres' }}">

				{{-- En-tête du thème --}}
				<div class="theme-header">
					<div class="theme-header__left">
						<p class="theme-header__eyebrow">Thème</p>
						<h2 class="theme-header__title" id="titre-theme-{{ $theme->id_theme ?? 'autres' }}">
							<span class="theme-header__symbol" aria-hidden="true" @if($theme->couleur) style="color:{{ $theme->couleur }}" @endif>{!! sicon('star') !!}</span>
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
						@php
							$stockCondi = $produit->stocks
								->firstWhere('id_forme_condi', $fc->id_forme_condi)
								?->quantite ?? 0;

							$dispo  = $stockCondi > 0;
							$maxQte = $dispo ? min($stockCondi, 20) : 0;
						@endphp

						{{-- N'afficher la carte que si le stock de ce conditionnement est > 0 --}}
						@if($stockCondi > 0)
						<article class="boutique-card reveal" aria-label="{{ $produit->nom_produit }}"
							data-search="{{ mb_strtolower(($produit->forme->nom_forme ?? '').' '.($produit->parfum->nom_parfum ?? '').' '.($fc->conditionnement->type ?? '').' '.($theme->nom_theme ?? '').' '.($produit->description ?? '')) }}">

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
										onerror="this.src='/fichier/image/meringues/oups.webp';"
									>
								</div>
								<div class="boutique-card__badges">
									@if($produit->nouveaute)
										<span class="boutique-card__badge boutique-card__badge--rose">Nouveau</span>
									@endif
									@if(!$dispo)
										<span class="boutique-card__badge boutique-card__badge--cream">Épuisé</span>
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
										<span class="boutique-card__badge boutique-card__badge--gold">{!! sicon('bag') !!} Emporter</span>
									@endif
									@if($produit->dispo_expedition && $fc->conditionnement->type === 'individuel')
										<span class="boutique-card__badge boutique-card__badge--gold">{!! sicon('package') !!} Expédition</span>
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

									@if($dispo)
										<form class="form-ajout-panier" action="{{ route('panier.ajouter') }}" method="POST">
											@csrf
											<input type="hidden" name="id_produit"     value="{{ $produit->id_produit }}">
											<input type="hidden" name="id_forme_condi" value="{{ $fc->id_forme_condi }}">

											<div style="display:flex;align-items:center;gap:6px;">
												<select name="quantite"
													style="width:52px;padding:5px 4px;border:1px solid var(--color-border);border-radius:6px;font-size:.82rem;text-align:center;background:var(--color-bg,#fff);color:var(--color-text);cursor:pointer;appearance:none;-webkit-appearance:none;">
													@for($q = 1; $q <= $maxQte; $q++)
														<option value="{{ $q }}">{{ $q }}</option>
													@endfor
												</select>

												<button type="submit" class="boutique-card__add-btn"
														aria-label="Ajouter {{ $produit->nom_produit }} au panier"
														title="Ajouter au panier">+</button>
											</div>
										</form>
									@else
										<span class="boutique-card__add-btn"
											style="background:var(--color-border);cursor:not-allowed;"
											aria-disabled="true">{!! sicon('x') !!}</span>
									@endif

								</div>
							</div>
						</article>
						@endif
						@endforeach
					@endforeach

				</div>
				{{-- /products-grid --}}

			</section>

		@empty

			<div class="boutique-empty">
				<div class="boutique-empty__icon">{!! sicon('package') !!}</div>
				<h2 class="boutique-empty__title">Aucun produit disponible</h2>
				<p class="boutique-empty__desc">
					@if(request()->hasAny(['filtre', 'theme', 'rayon']))
						Aucun produit ne correspond à ces filtres.<br>
						<a href="{{ route('shop.index', $boutique->id_boutique) }}"
						   class="btn btn--ghost btn--sm mt-md">Réinitialiser les filtres</a>
					@else
						Notre boutique est en cours de préparation. Revenez bientôt&nbsp;!
					@endif
				</p>
			</div>

		@endforelse

	@endif

</main>


@include('templet.footer')


<script>
	// ── Recherche en direct ─────────────────────────────────────
	const searchInput = document.getElementById('boutique-search-input');
	const searchClear = document.getElementById('boutique-search-clear');
	const searchEmpty = document.getElementById('boutique-search-empty');
	const countEl     = document.getElementById('boutique-count');

	const normaliser = t => (t || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim();

	function appliquerRecherche() {
		const q = normaliser(searchInput.value);
		const cartes = document.querySelectorAll('.boutique-card[data-search]');
		let visibles = 0;

		cartes.forEach(carte => {
			const ok = !q || normaliser(carte.dataset.search).includes(q);
			carte.style.display = ok ? '' : 'none';
			if (ok) {
				visibles++;
				carte.classList.add('reveal--visible'); // évite les cartes invisibles après un filtre
			}
		});

		// Masquer les thèmes sans résultat
		document.querySelectorAll('.theme-section').forEach(sec => {
			const aDesCartes = [...sec.querySelectorAll('.boutique-card[data-search]')].some(c => c.style.display !== 'none');
			sec.style.display = aDesCartes ? '' : 'none';
			if (aDesCartes) sec.classList.add('reveal--visible');
		});
		document.querySelectorAll('.theme-divider').forEach(d => d.style.display = q ? 'none' : '');

		searchEmpty.style.display = (q && visibles === 0) ? 'block' : 'none';
		searchClear.classList.toggle('show', q.length > 0);

		if (q) {
			countEl.textContent = visibles + ' résultat' + (visibles > 1 ? 's' : '');
		} else {
			const total = parseInt(countEl.dataset.total || '0');
			countEl.textContent = total + ' produit' + (total > 1 ? 's' : '');
		}
	}

	searchInput?.addEventListener('input', appliquerRecherche);
	searchClear?.addEventListener('click', () => {
		searchInput.value = '';
		appliquerRecherche();
		searchInput.focus();
	});

	const SVG_CHECK = '<svg class="bout-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';

	function voirProduit(id) {
		window.location.href = '/produit/' + id;
	}

	// ── Ajout au panier en AJAX (garde la position de scroll) ──
	document.addEventListener('submit', function (e) {
		const form = e.target.closest('.form-ajout-panier');
		if (!form) return;

		e.preventDefault();

		const btn   = form.querySelector('button[type="submit"]');
		const data  = new FormData(form);
		const badge = document.getElementById('cart-badge');
		const bubble = document.getElementById('cart-bubble');

		btn.disabled         = true;
		btn.innerHTML        = SVG_CHECK;
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

			// Petit "rebond" du panier pour confirmer l'ajout
			bubble?.classList.remove('cart-bounce');
			void bubble?.offsetWidth;
			bubble?.classList.add('cart-bounce');

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
		img.addEventListener('error', () => {
			img.src = "/fichier/image/meringues/oups.webp";
		});
	});

	// ── Animations au scroll ────────────────────────────────────
	const reveals = document.querySelectorAll('.reveal');
	if ('IntersectionObserver' in window) {
		const observer = new IntersectionObserver(entries => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					entry.target.classList.add('reveal--visible');
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

		reveals.forEach(el => observer.observe(el));
	} else {
		reveals.forEach(el => el.classList.add('reveal--visible'));
	}

</script>