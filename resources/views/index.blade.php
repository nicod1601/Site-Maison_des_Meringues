<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		@vite(['resources/css/style.css'])
		<title>{{ config('app.name', 'Laravel') }}</title>
	</head>
	<body>
		<nav class="navbar">
			<div class="navbar__inner">
				<a href="/" class="navbar__logo">Maison des <span>Meringues</span></a>

				<div class="navbar__links">
					<a href="/" class="active">Accueil</a>
					<a href="/gestion">Gestionnaire</a>
					<a href="#">Catalogue</a>
					<a href="#">Mes Données</a>
					<a href="/shop" class="navbar__cta">shop</a>
				</div>
			</div>
		</nav>
	</body>
</html>
