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

		<!-- Information-->
		<main class="container section">
			<div class="grid grid-3 mb-2xl">
				<div class="card">
					<div class="card__body">
						<h4 class="card__title">Information</h4>
						<div class="flex flex-center py-md">
							<div class="stat">
								<span class="stat__number" id="dossier-count">0</span>
								<span class="stat__label">Fichiers indexés</span>
							</div>
						</div>
					</div>
				</div>



				<div class="card">
					<div class="card__body text-center">
						<h3 class="mb-lg">Importer un fichier</h3>
						<label for="file-input" class="form-input py-4xl" id="drop-zone" style="cursor: pointer; border-style: dashed;">
							<div style="font-size: 3rem; margin-bottom: var(--space-md);">📥</div>
							<p class="text-muted">
								<strong>Glissez-déposez</strong> votre fichier ici <br>
								<span class="text-xs">ou cliquez pour sélectionner</span>
							</p>
							<input type="file" id="file-input" name="file" hidden>
							<div id="file-name" class="mt-md text-accent"></div>
						</label>
					</div>
				</div>

				<!-- Annee-->
				<div class="card">
					<div class="card__body">
						<h4 class="card__title">Options de tri</h4>
						<div class="form-group mt-md">
							<label class="form-label">Sélectionner l'année</label>
							<select id="annee-select" name="annee" class="form-select">
								@foreach($annees as $annee)
									<option value="{{ $annee['value'] }}">{{ $annee['text'] }}</option>
								@endforeach
							</select>
						</div>
						<div class="flex gap-md mt-lg">
							<button class="btn btn--primary btn--sm" id="import-btn">Importer</button>
							<button class="btn btn--secondary btn--sm" id="delete-btn">Supprimer</button>
						</div>
					</div>
				</div>
			</div>

			<section class="section--sm" id="zone-tableau">
				<h2 class="hero__title text-center mb-xl">Aperçu des données</h2>

				<div class="card overflow-hidden">
					<div style="overflow-x: auto; max-height: 500px;">
						<table class="table" id="excel-table"></table>
					</div>
				</div>

				<div class="pagination" id="paginationBar"></div>
				<div id="confirm-btn-container" class="flex-center mt-xl"></div>
			</section>

			<section class="section--sm" id="zone-liste-fichiers" style="display:none;">
				<div class="flex-between mb-lg">
					<h3>Liste des fichiers</h3>
					<button class="btn btn--secondary btn--sm" id="delete-all-btn">Tout supprimer</button>
				</div>
				<div id="liste-fichiers-container" class="grid grid-3"></div>
			</section>
		</main>

		<!-- Fenêtre de chargement -->
		<div id="loading-overlay" style="display:none;">
			<div class="loading-box">
				<div class="spinner"></div>
				<p id="traitement"></p>
			</div>
		</div>

	</body>
</html>
