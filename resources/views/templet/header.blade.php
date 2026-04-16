<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		@vite('resources/css/style.css')
		<title>Gestion — La Maison des Meringues'</title>
	</head>
	<body>
		<nav class="navbar">
			<div class="navbar__inner">
				<img src="{{ asset('fichier/image/La_Maison_des_Meringues_logo.png') }}" alt="Logo" class="navbar__logo-img">
				<a href="/" class="navbar__logo">Maison des <span>Meringues</span></a>
				<div class="navbar__links">
					<a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Accueil</a>
					<a href="/news" class="{{ request()->is('news') ? 'active' : '' }}">Catalogue</a>
					<a href="/gestion" class="{{ request()->is('gestion') ? 'active' : '' }}">Importation</a>
					<a href="/data" class="{{ request()->is('data') ? 'active' : '' }}">Mes Données</a>
					<a href="/shop" class="navbar__cta">Boutique</a>
				</div>
				<div class="user-profile">
					<img src="https://ui-avatars.com/api/?name=Olivia+Rhye&background=c7d9f8&color=0D1B3E" alt="Avatar">
					<span class="user-name">Olivia Rhye</span>
				</div>
			</div>
		</nav>

		<header class="{{ request()->is('/') ? 'page-header-home' : 'page-header'}}">
			<div class="{{ request()->is('/') ? 'container-home' : 'container'}}">
				<h1 class="{{ request()->is('/') ? 'page-header__title-home' : 'page-header__title'}} mt-md">{{$titre}}</h1>
				<p class="page-header__sub'">{{$note ?? ''}}</p>
			</div>
		</header>

		<main class="container section">
