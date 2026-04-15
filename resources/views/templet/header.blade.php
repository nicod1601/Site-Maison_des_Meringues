<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		@vite($style ?? 'resources/css/style.css')
		<title>Gestion — La Maison des Meringues'</title>
	</head>
	<body>
		<nav class="navbar">
			<div class="navbar__inner">
				<img src="{{ asset('fichier/image/La_Maison_des_Meringues_logo.png') }}" alt="Logo" class="navbar__logo-img">
				<a href="/" class="navbar__logo">Maison des <span>Meringues</span></a>
				<div class="navbar__links">
					<a href="/" class="active">Accueil</a>
					<a href="/news">Catalogue</a>
					<a href="/gestion">Gestionnaire</a>
					<a href="/data">Mes données</a>
					<a href="/shop" class="navbar__cta">Boutique</a>
					<a href="#" id="light">💡</a>
				</div>
				<div class="navbar__burger" id="burger" aria-label="Menu">
					<span></span><span></span><span></span>
				</div>
			</div>
		</nav>

		<header class="page-header">
			<div class="container">
				<h1 class="page-header__title mt-md">{{$titre}}</h1>
				<p class="page-header__sub">{{$note}}</p>
			</div>
		</header>

		<main class="container section">
