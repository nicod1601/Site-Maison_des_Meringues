<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        @vite(['resources/css/style.css'])
		<title>{{ config('app.name', 'Laravel') }}</title>
	</head>
	<body>

		<nav>
			<a href="" class="navbar">✦ Ma Boutique</a>
			<div class="navbar__links">
				<a href="">Catalogue</a>
				<a href="">Importer</a>
			</div>
		</nav>
	</body>
</html>
