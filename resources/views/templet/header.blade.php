<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		@vite('resources/css/style.css')

		<link rel="icon" type="image/webp" href="{{ asset('fichier/image/logo.webp') }}">

		<title>{{ $title ?? 'Accueil' }} — La Maison des Meringues</title>
	</head>
	<style>
        /* Curseur normal */
        * {
            cursor: url("{{ asset('fichier/image/sourie/cursor-50.png') }}") 0 0, auto !important;
        }

        /* Curseur quand on peut cliquer */
        a,
        button,
        input[type="button"],
        input[type="submit"],
        input[type="reset"],
        select,
        [role="button"],
        [onclick] {
            cursor: url("{{ asset('fichier/image/sourie/cursor-hand-50.png') }}") 0 0, pointer !important;
        }
	</style>
	<body>
		<nav class="navbar">
			<div class="navbar__inner">

				<a href="/" class="navbar__logo-zone">
					<img src="{{ asset('fichier/image/logo.webp') }}"
						alt="Logo La Maison des Meringues"
						class="navbar__logo-img">
					<span class="navbar__logo-text">
						La Maison des<br><span>Meringues</span>
					</span>
				</a>

				<span class="navbar__divider"></span>

				<nav class="navbar__links" aria-label="Navigation principale">
					<a href="/"        class="navbar__link {{ request()->is('/') ? 'active' : '' }}">Accueil</a>
					<a href="/blog"    class="navbar__link {{ request()->is('blog') ? 'active' : '' }}">Blog</a>
				</nav>

				<div class="navbar__cta-group">
					<a href="/shop/{{1}}" class="navbar__cta">Boutique</a>
					<a href="/pro" class="navbar__cta navbar__cta--outline">Professionnel</a>
				</div>

				<button type="button" class="navbar__burger" id="navbar-burger" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="navbar-mob-drawer">
					<svg class="navbar__burger-open" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
						<path d="M3 6h14M3 10h14M3 14h14"/>
					</svg>
					<svg class="navbar__burger-close" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
						<path d="M4 4l12 12M16 4L4 16"/>
					</svg>
				</button>

				@auth
					<div class="navbar__profile" aria-expanded="false">
						<div class="navbar__avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
						<span class="navbar__username">{{ Auth::user()->name }}</span>
						<svg width="14" height="14" viewBox="0 0 14 14" fill="none"
							stroke="currentColor" stroke-width="1.5">
							<path d="M3.5 5.5l3.5 3.5 3.5-3.5"/>
						</svg>

						<div class="navbar__dropdown">

							@if(Auth::user()->isAdmin())
								<a href="/gestion" class="navbar__dropdown-item">
									<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
										<rect x="1" y="1" width="5" height="5" rx="1"/>
										<rect x="8" y="1" width="5" height="5" rx="1"/>
										<rect x="1" y="8" width="5" height="5" rx="1"/>
										<rect x="8" y="8" width="5" height="5" rx="1"/>
									</svg>
									Gestion des données
								</a>

							@endif

							<a href="/ticketCommande" class="navbar__dropdown-item">
								<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
									<rect x="1" y="3" width="12" height="8" rx="2"/>
									<line x1="1" y1="5.5" x2="13" y2="5.5"/>
									<line x1="1" y1="8.5" x2="13" y2="8.5"/>
									<line x1="4.5" y1="3" x2="4.5" y2="11"/>
									<line x1="9.5" y1="3" x2="9.5" y2="11"/>
								</svg>
								Ticket de commande
							</a>

							<a href="/settings" class="navbar__dropdown-item">
								<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
									<circle cx="7" cy="7" r="2"/>
									<path d="M7 1v2M7 11v2M1 7h2M11 7h2M2.93 2.93l1.41 1.41M9.66 9.66l1.41 1.41M2.93 11.07l1.41-1.41M9.66 4.34l1.41-1.41"/>
								</svg>
								Paramètres
							</a>

							<hr class="navbar__dropdown-sep">

							<form method="POST" action="{{ route('logout') }}">
								@csrf
								<button type="submit" class="navbar__dropdown-item navbar__dropdown-item--danger"
										style="background:none;border:none;width:100%;text-align:left;cursor:pointer;display:flex;align-items:center;gap:6px;padding:0;">
									<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
										<path d="M5 2H2v10h3M9 9l3-2-3-2M6 7h6"/>
									</svg>
									Se déconnecter
								</button>
							</form>
						</div>
					</div>
				@else
					<a href="{{ route('login') }}" class="navbar__cta">Se connecter</a>
				@endauth

			</div>
		</nav>

		{{-- Menu mobile --}}
		<div class="navbar__mob-overlay" id="navbar-mob-overlay"></div>
		<nav class="navbar__mob-drawer" id="navbar-mob-drawer" aria-label="Navigation mobile">
			<div class="navbar__mob-head">
				<span class="navbar__logo-text">La Maison des<br><span>Meringues</span></span>
				<button type="button" class="navbar__mob-close" id="navbar-mob-close" aria-label="Fermer le menu">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
						<path d="M3 3l10 10M13 3L3 13"/>
					</svg>
				</button>
			</div>

			<div class="navbar__mob-links">
				<a href="/"     class="navbar__mob-link {{ request()->is('/') ? 'active' : '' }}">Accueil</a>
				<a href="/blog" class="navbar__mob-link {{ request()->is('blog') ? 'active' : '' }}">Blog</a>
			</div>

			<div class="navbar__mob-ctas">
				<a href="/shop/{{1}}" class="navbar__cta">Boutique</a>
				<a href="/pro" class="navbar__cta navbar__cta--outline">Professionnel</a>
			</div>

			<div class="navbar__mob-foot">
				@auth
					<div class="navbar__mob-foot-user">
						<div class="navbar__avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
						<span class="navbar__username" style="display:block;">{{ Auth::user()->name }}</span>
					</div>

					@if(Auth::user()->isAdmin())
						<a href="/gestion" class="navbar__dropdown-item">
							<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
								<rect x="1" y="1" width="5" height="5" rx="1"/>
								<rect x="8" y="1" width="5" height="5" rx="1"/>
								<rect x="1" y="8" width="5" height="5" rx="1"/>
								<rect x="8" y="8" width="5" height="5" rx="1"/>
							</svg>
							Gestion des données
						</a>
					@endif

					<a href="/ticketCommande" class="navbar__dropdown-item">
						<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
							<rect x="1" y="3" width="12" height="8" rx="2"/>
							<line x1="1" y1="5.5" x2="13" y2="5.5"/>
							<line x1="1" y1="8.5" x2="13" y2="8.5"/>
							<line x1="4.5" y1="3" x2="4.5" y2="11"/>
							<line x1="9.5" y1="3" x2="9.5" y2="11"/>
						</svg>
						Ticket de commande
					</a>

					<a href="/settings" class="navbar__dropdown-item">
						<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
							<circle cx="7" cy="7" r="2"/>
							<path d="M7 1v2M7 11v2M1 7h2M11 7h2M2.93 2.93l1.41 1.41M9.66 9.66l1.41 1.41M2.93 11.07l1.41-1.41M9.66 4.34l1.41-1.41"/>
						</svg>
						Paramètres
					</a>

					<hr class="navbar__dropdown-sep">

					<form method="POST" action="{{ route('logout') }}">
						@csrf
						<button type="submit" class="navbar__dropdown-item navbar__dropdown-item--danger"
								style="background:none;border:none;width:100%;text-align:left;cursor:pointer;display:flex;align-items:center;gap:6px;padding:12px;">
							<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
								<path d="M5 2H2v10h3M9 9l3-2-3-2M6 7h6"/>
							</svg>
							Se déconnecter
						</button>
					</form>
				@else
					<a href="{{ route('login') }}" class="navbar__dropdown-item">
						<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
							<path d="M9 2h3v10H9M5 4.5L1.5 7 5 9.5M1.5 7H9"/>
						</svg>
						Se connecter
					</a>
				@endauth
			</div>
		</nav>

		@if(empty($hideHeader))
		<header class="{{ request()->is('/') ? 'page-header-home' : 'page-header'}}">
			<div class="{{ request()->is('/') ? 'container-home' : 'container'}}">
				@if(request()->is('/'))
					<span class="page-header-home__eyebrow">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v4M12 17v4M3 12h4M17 12h4"/><circle cx="12" cy="12" r="3"/></svg>
						Fabrication artisanale
					</span>
					<h1 class="page-header__title-home mt-md">
						Bienvenue à La Maison des<br><em>Meringues</em>
					</h1>
					<p class="page-header__sub">{{ $note ?? "Des meringues artisanales pochées avec passion, à déguster sur place ou à emporter." }}</p>
					<div class="page-header-home__ctas">
						<a href="/shop/1" class="btn btn--gold">Découvrir la boutique</a>
						<a href="#nous-trouver" class="btn btn--ghost" style="color:#F3EDE6;border-color:rgba(255,255,255,.4);">Nous trouver</a>
					</div>
				@else
					<h1 class="page-header__title mt-md">{{$titre}}</h1>
					<p class="page-header__sub">{{$note ?? ''}}</p>
				@endif
			</div>

			@if(request()->is('/'))
			<span class="page-header-home__scroll">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
				Découvrir
			</span>
			@endif
		</header>
		@endif

		{{-- Script navbar — sécurisé avec vérification null --}}
		<script>
			(function () {
				const profileBtn = document.querySelector('.navbar__profile');
				const dropdown   = document.querySelector('.navbar__dropdown');

				if (!profileBtn || !dropdown) return; {{-- sécurité si non connecté --}}

				profileBtn.addEventListener('click', function () {
					const isOpen = dropdown.classList.contains('open');
					dropdown.classList.toggle('open', !isOpen);
					profileBtn.setAttribute('aria-expanded', String(!isOpen));
				});

				document.addEventListener('click', function (e) {
					if (!profileBtn.contains(e.target)) {
						dropdown.classList.remove('open');
						profileBtn.setAttribute('aria-expanded', 'false');
					}
				});
			})();

			// Ombre discrète sur la navbar dès qu'on scrolle
			(function () {
				const navbar = document.querySelector('.navbar');
				if (!navbar) return;

				const maj = () => navbar.classList.toggle('is-scrolled', window.scrollY > 8);
				maj();
				window.addEventListener('scroll', maj, { passive: true });
			})();

			// Menu mobile (tiroir)
			(function () {
				const burger  = document.getElementById('navbar-burger');
				const drawer  = document.getElementById('navbar-mob-drawer');
				const overlay = document.getElementById('navbar-mob-overlay');
				const closeBtn = document.getElementById('navbar-mob-close');

				if (!burger || !drawer || !overlay) return;

				function ouvrir() {
					drawer.classList.add('open');
					overlay.classList.add('open');
					burger.setAttribute('aria-expanded', 'true');
					document.body.style.overflow = 'hidden';
				}
				function fermer() {
					drawer.classList.remove('open');
					overlay.classList.remove('open');
					burger.setAttribute('aria-expanded', 'false');
					document.body.style.overflow = '';
				}

				burger.addEventListener('click', () => {
					drawer.classList.contains('open') ? fermer() : ouvrir();
				});
				closeBtn?.addEventListener('click', fermer);
				overlay.addEventListener('click', fermer);
				document.addEventListener('keydown', e => {
					if (e.key === 'Escape') fermer();
				});
				drawer.querySelectorAll('a, button[type="submit"]').forEach(el => {
					el.addEventListener('click', fermer);
				});
			})();
		</script>