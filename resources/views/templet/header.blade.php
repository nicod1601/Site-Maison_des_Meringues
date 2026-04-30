<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		@vite('resources/css/style.css')
		<title>{{ $title ?? 'Accueil'}} — La Maison des Meringues'</title>
	</head>
	<body>
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
					<a href="/shop/{{1}}"    class="navbar__cta">Boutique</a>
                    <a href="/panier"  class="navbar__link {{ request()->is('panier') ? 'active' : '' }}">Mon Panier</a>
				</nav>

				@auth
					<div class="navbar__profile" aria-expanded="false">
						<div class="navbar__avatar">OR</div>
						<span class="navbar__username">{{ Auth::user()->name }}</span>
						<svg width="14" height="14" viewBox="0 0 14 14" fill="none"
							stroke="currentColor" stroke-width="1.5">
							<path d="M3.5 5.5l3.5 3.5 3.5-3.5"/>
						</svg>

						<div class="navbar__dropdown">
							<a href="/profil" class="navbar__dropdown-item">
								<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
									<circle cx="7" cy="5" r="3"/>
									<path d="M2 12c0-2.8 2.2-4 5-4s5 1.2 5 4"/>
								</svg>
								Mon profil
							</a>

							@if(Auth::user()->isAdmin())
								<a href="/gestion" class="navbar__dropdown-item {{ request()->is('gestion') ? 'active' : '' }}">
									<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
										<rect x="1" y="1" width="5" height="5" rx="1"/>
										<rect x="8" y="1" width="5" height="5" rx="1"/>
										<rect x="1" y="8" width="5" height="5" rx="1"/>
										<rect x="8" y="8" width="5" height="5" rx="1"/>
									</svg>
									Gestion des données
								</a>
							@endif

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

		<header class="{{ request()->is('/') ? 'page-header-home' : 'page-header'}}">
			<div class="{{ request()->is('/') ? 'container-home' : 'container'}}">
				<h1 class="{{ request()->is('/') ? 'page-header__title-home' : 'page-header__title'}} mt-md">{{$titre}}</h1>
				<p class="page-header__sub'">{{$note ?? ''}}</p>
			</div>
		</header>

		<script>
			const profileBtn = document.querySelector('.navbar__profile');
			const dropdown = document.querySelector('.navbar__dropdown');

			if (profileBtn && dropdown) {
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
			}
		</script>



