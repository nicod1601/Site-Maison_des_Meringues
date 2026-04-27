<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	@vite(['resources/css/style.css', 'resources/css/boutique.css'])
	<title>{{ $boutique->nom_boutique }} — La Maison des Meringues</title>
</head>
<body>

{{-- ── NAVBAR (identique aux autres pages) ────────────────── --}}
<nav class="navbar">
	<div class="navbar__inner">

		<a href="/" class="navbar__logo-zone">
			<img src="{{ asset('fichier/image/La_Maison_des_Meringues_logo.png') }}"
				 alt="Logo La Maison des Meringues"
				 class="navbar__logo-img">
			<span class="navbar__logo-text">
				Maison des<br><span>Meringues</span>
			</span>
		</a>

		<span class="navbar__divider"></span>

		<nav class="navbar__links" aria-label="Navigation principale">
			<a href="/"        class="navbar__link {{ request()->is('/') ? 'active' : '' }}">Accueil</a>
			<a href="/news"    class="navbar__link {{ request()->is('news') ? 'active' : '' }}">Catalogue</a>
			<a href="/gestion" class="navbar__link {{ request()->is('gestion') ? 'active' : '' }}">Importation</a>
			<a href="/shop/1"  class="navbar__cta active">Boutique</a>
		</nav>

		<div class="navbar__profile" aria-expanded="false">
			<div class="navbar__avatar">OR</div>
			<span class="navbar__username">Olivia Rhye</span>
			<svg width="14" height="14" viewBox="0 0 14 14" fill="none"
				 stroke="currentColor" stroke-width="1.5">
				<path d="M3.5 5.5l3.5 3.5 3.5-3.5"/>
			</svg>
			<div class="navbar__dropdown">
				<a href="/profil" class="navbar__dropdown-item">
					<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
						<circle cx="7" cy="5" r="3"/><path d="M2 12c0-2.8 2.2-4 5-4s5 1.2 5 4"/>
					</svg>
					Mon profil
				</a>
				<a href="/settings" class="navbar__dropdown-item">
					<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
						<circle cx="7" cy="7" r="5"/><path d="M7 4v3l2 1.5"/>
					</svg>
					Paramètres
				</a>
				<hr class="navbar__dropdown-sep">
				<a href="/logout" class="navbar__dropdown-item navbar__dropdown-item--danger">
					<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
						<path d="M5 2H2v10h3M9 9l3-2-3-2M6 7h6"/>
					</svg>
					Se déconnecter
				</a>
			</div>
		</div>

	</div>
</nav>


{{-- ── HERO BOUTIQUE (remplace le page-header standard) ──── --}}
<div class="boutique-hero">
	<div class="boutique-hero__inner">
		<p class="boutique-hero__eyebrow">Nos créations</p>
		<h1 class="boutique-hero__title">
			{{ $boutique->nom_boutique }}
			<em>Meringues &amp; Douceurs Artisanales</em>
		</h1>
		<p class="boutique-hero__desc">
			{{ $totalProduits }} produit{{ $totalProduits > 1 ? 's' : '' }}
			disponible{{ $totalProduits > 1 ? 's' : '' }}
		</p>
	</div>

	{{-- ── NAVIGATION DES RAYONS ───────────────────────────── --}}
	<nav class="rayon-nav" aria-label="Rayons de la boutique">
		<div class="rayon-nav__inner">

			<a href="{{ route('shop', $boutique->id_boutique) }}"
			   class="rayon-nav__item {{ !request('rayon') ? 'active' : '' }}">
				Tous les produits
				<span class="rayon-nav__count">{{ $totalProduits }}</span>
			</a>

			@foreach($rayons as $rayon)
			<a href="{{ route('shop', ['id_boutique' => $boutique->id_boutique, 'rayon' => $rayon->id_rayon]) }}"
			   class="rayon-nav__item {{ request('rayon') == $rayon->id_rayon ? 'active' : '' }}">
				{{ $rayon->nom_rayon }}
				<span class="rayon-nav__count">{{ $rayon->produits_count }}</span>
			</a>
			@endforeach

		</div>
	</nav>
</div>


{{-- ── CORPS ───────────────────────────────────────────────── --}}
<main class="boutique-body" id="boutique-contenu">

	{{-- ── BARRE D'OUTILS ─────────────────────────────────── --}}
	@php
		$baseParams = array_filter([
			'id_boutique' => $boutique->id_boutique,
			'rayon'       => request('rayon'),
			'theme'       => request('theme'),
			'tri'         => request('tri'),
		]);
	@endphp

	<div class="boutique-toolbar">
		<div class="boutique-toolbar__left">

			<span class="boutique-toolbar__count">
				{{ $totalProduits }} produit{{ $totalProduits > 1 ? 's' : '' }}
				@if($rayonActif)
					dans <strong>{{ $rayonActif->nom_rayon }}</strong>
				@endif
			</span>

			{{-- Filtres rapides --}}
			<div class="boutique-toolbar__filters" role="group" aria-label="Filtres">
				<a href="{{ route('shop', array_diff_key($baseParams, ['filtre' => ''])) }}"
				   class="boutique-filter-chip {{ !request('filtre') ? 'active' : '' }}">Tous</a>
				<a href="{{ route('shop', array_merge($baseParams, ['filtre' => 'nouveaute'])) }}"
				   class="boutique-filter-chip {{ request('filtre') == 'nouveaute' ? 'active' : '' }}">Nouveautés</a>
				<a href="{{ route('shop', array_merge($baseParams, ['filtre' => 'emporter'])) }}"
				   class="boutique-filter-chip {{ request('filtre') == 'emporter' ? 'active' : '' }}">À emporter</a>
				<a href="{{ route('shop', array_merge($baseParams, ['filtre' => 'expedition'])) }}"
				   class="boutique-filter-chip {{ request('filtre') == 'expedition' ? 'active' : '' }}">Expédition</a>
				<a href="{{ route('shop', array_merge($baseParams, ['filtre' => 'dispo'])) }}"
				   class="boutique-filter-chip {{ request('filtre') == 'dispo' ? 'active' : '' }}">En stock</a>
			</div>

			{{-- Filtre par thème --}}
			@if($themesDisponibles->isNotEmpty())
			<div class="boutique-toolbar__filters" role="group" aria-label="Filtrer par thème">
				<span class="boutique-toolbar__count" style="margin-right:4px;">Thème :</span>
				@foreach($themesDisponibles as $th)
				<a href="{{ route('shop', array_merge($baseParams, ['theme' => $th->id_theme])) }}"
				   class="boutique-filter-chip {{ request('theme') == $th->id_theme ? 'active' : '' }}"
				   @if($th->couleur)
					   style="border-color:{{ $th->couleur }};{{ request('theme') == $th->id_theme ? 'background:' . $th->couleur . '22;color:' . $th->couleur . ';' : '' }}"
				   @endif>
					@if($th->icone)<span aria-hidden="true">{{ $th->icone }}</span> @endif
					{{ $th->nom_theme }}
				</a>
				@endforeach
			</div>
			@endif

		</div>

		<div class="boutique-toolbar__right">
			<div class="boutique-sort">
				<label for="tri-select" class="text-small text-muted">Trier par</label>
				<select id="tri-select" onchange="appliquerTri(this.value)">
					<option value="nom_asc"    {{ request('tri', 'nom_asc') == 'nom_asc'    ? 'selected' : '' }}>Nom A → Z</option>
					<option value="prix_asc"   {{ request('tri') == 'prix_asc'   ? 'selected' : '' }}>Prix croissant</option>
					<option value="prix_desc"  {{ request('tri') == 'prix_desc'  ? 'selected' : '' }}>Prix décroissant</option>
					<option value="nouveautes" {{ request('tri') == 'nouveautes' ? 'selected' : '' }}>Nouveautés d'abord</option>
				</select>
			</div>

			<div class="boutique-view-toggle" role="group" aria-label="Mode d'affichage">
				<button class="boutique-view-btn active" id="btn-grille"
						aria-label="Vue grille" onclick="switchView('grille')">⊞</button>
				<button class="boutique-view-btn" id="btn-liste"
						aria-label="Vue liste"  onclick="switchView('liste')">☰</button>
			</div>
		</div>
	</div>
	{{-- /boutique-toolbar --}}


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
				<div class="theme-header__action">
					<span class="badge badge--cream">
						{{ $theme->produits_affiches->count() }}
						produit{{ $theme->produits_affiches->count() > 1 ? 's' : '' }}
					</span>
				</div>
			</div>

			{{-- Grille produits --}}
			<div class="products-grid" id="grille-theme-{{ $theme->id_theme ?? 'autres' }}">

				@foreach($theme->produits_affiches as $produit)
				<article class="boutique-card" aria-label="{{ $produit->nom_produit }}">

					{{-- Placeholder image --}}
					<div class="boutique-card__img-wrap">
						<div class="boutique-card__img-placeholder" aria-hidden="true"
							@if($theme->couleur)
								style="background:linear-gradient(135deg,{{ $theme->couleur }}22 0%,{{ $theme->couleur }}44 100%);"
							@endif>
							{{ $theme->icone ?? '🍬' }}
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

						@if($produit->parfum)
							<p class="boutique-card__theme">{{ $produit->parfum->nom_parfum }}</p>
						@endif

						<h3 class="boutique-card__name">{{ $produit->nom_produit }}</h3>

						@if($produit->forme_condi)
							<p class="boutique-card__desc">
								@if($produit->forme_condi->forme){{ $produit->forme_condi->forme->nom_forme }}@endif
								@if($produit->forme_condi->conditionnement) &mdash; {{ $produit->forme_condi->conditionnement->type }}@endif
							</p>
						@endif

						@if($produit->description)
							<p class="boutique-card__desc" style="margin-top:.2rem;">
								{{ Str::limit($produit->description, 80) }}
							</p>
						@endif

						<div style="display:flex;gap:4px;flex-wrap:wrap;margin-top:auto;padding-top:var(--space-sm);">
							@if($produit->dispo_emporter)
								<span class="badge badge--cream" style="font-size:10px;">🛍 Emporter</span>
							@endif
							@if($produit->dispo_expedition)
								<span class="badge badge--cream" style="font-size:10px;">📦 Expédition</span>
							@endif
						</div>

						<div class="boutique-card__footer">
							<div>
								@if($produit->forme_condi)
									<p class="boutique-card__price">
										{{ number_format($produit->forme_condi->prix, 2, ',', ' ') }} €
									</p>
								@else
									<p class="boutique-card__price" style="color:var(--color-text-muted);">— €</p>
								@endif
								@if($produit->quantite > 0)
									<p class="boutique-card__price-old">{{ $produit->quantite }} en stock</p>
								@else
									<p class="boutique-card__price-old" style="color:#c0392b;">Épuisé</p>
								@endif
							</div>

							@if($produit->quantite > 0)
								<button class="boutique-card__add-btn"
										onclick="voirProduit({{ $produit->id_produit }})"
										aria-label="Voir {{ $produit->nom_produit }}"
										title="Voir le produit">→</button>
							@else
								<span class="boutique-card__add-btn"
									  style="background:var(--color-border);cursor:not-allowed;"
									  aria-disabled="true">✕</span>
							@endif
						</div>

					</div>
				</article>
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

</main>


{{-- ── FOOTER (même include que les autres pages) ─────────── --}}
@include('templet.footer')


{{-- ── SCRIPTS ─────────────────────────────────────────────── --}}
<script>
	// Navbar dropdown (identique au header)
	const profileBtn = document.querySelector('.navbar__profile');
	const dropdown   = document.querySelector('.navbar__dropdown');
	profileBtn.addEventListener('click', function () {
		const isOpen = dropdown.classList.contains('open');
		dropdown.classList.toggle('open', !isOpen);
		profileBtn.setAttribute('aria-expanded', !isOpen);
	});
	document.addEventListener('click', function (e) {
		if (!profileBtn.contains(e.target)) {
			dropdown.classList.remove('open');
			profileBtn.setAttribute('aria-expanded', false);
		}
	});

	// Vue grille / liste
	function switchView(mode) {
		document.querySelectorAll('.products-grid').forEach(g => {
			g.classList.toggle('list-view', mode === 'liste');
		});
		document.getElementById('btn-grille').classList.toggle('active', mode === 'grille');
		document.getElementById('btn-liste').classList.toggle('active',  mode === 'liste');
		localStorage.setItem('boutique_view', mode);
	}
	document.addEventListener('DOMContentLoaded', () => {
		if (localStorage.getItem('boutique_view') === 'liste') switchView('liste');
	});

	// Tri
	function appliquerTri(valeur) {
		const url = new URL(window.location.href);
		url.searchParams.set('tri', valeur);
		window.location.href = url.toString();
	}

	// Voir fiche produit
	function voirProduit(id) {
		window.location.href = '/produit/' + id;
	}
</script>

</body>
</html>
