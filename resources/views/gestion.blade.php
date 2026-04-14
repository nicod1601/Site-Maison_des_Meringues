<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		@vite(['resources/css/style.css'])
		@vite(['resources/css/gestion.css'])
		<title>Gestion — {{ config('app.name', 'La Maison des Meringues') }}</title>
	</head>
	<body>
		<nav class="navbar">
			<div class="navbar__inner">
				<a href="/" class="navbar__logo">Maison des <span>Meringues</span></a>
				<div class="navbar__links">
					<a href="/">Accueil</a>
					<a href="/gestion" class="active">Gestionnaire</a>
					<a href="#">Catalogue</a>
					<a href="#">Mes données</a>
					<a href="/shop" class="navbar__cta">Boutique</a>
				</div>
				<div class="navbar__burger" id="burger" aria-label="Menu">
					<span></span><span></span><span></span>
				</div>
			</div>
		</nav>

		<header class="page-header">
			<div class="container">
				<p class="breadcrumb">
					<a href="/">Accueil</a>
					<span class="breadcrumb__sep">›</span>
					<span>Gestionnaire</span>
				</p>
				<h1 class="page-header__title mt-md">Gestionnaire de données</h1>
				<p class="page-header__sub">Importez, visualisez et gérez vos fichiers Excel</p>
			</div>
		</header>

		<main class="container section">

			<div class="grid grid-3 panel-grid mb-2xl">

				<!-- Colonne 1 : Statistiques -->
				<div class="card">
					<div class="card__body">
						<p class="card__tag">Informations</p>
						<div class="stat-card mt-md">
							<span class="stat-card__value" id="dossier-count">0</span>
							<span class="stat-card__label">Fichiers indexés</span>
							<span class="stat-card__sub" id="last-import-date">Aucun import récent</span>
						</div>

						<hr class="mt-lg mb-lg">

						<div class="flex-between">
							<div class="stat-card">
								<span class="stat-card__value" id="row-count">—</span>
								<span class="stat-card__label">Lignes</span>
							</div>
							<div class="stat-card" style="text-align:right;">
								<span class="stat-card__value" id="col-count">—</span>
								<span class="stat-card__label">Colonnes</span>
							</div>
						</div>
					</div>
				</div>

				<!-- Colonne 2 : Import fichier -->
				<div class="card">
					<div class="card__body" style="display:flex;flex-direction:column;height:100%;">
						<p class="card__tag">Importer un fichier</p>

						<form action="{{ route('import.excel') }}" method="POST" enctype="multipart/form-data" id="import-form">
							@csrf

							<label for="file-input" class="drop-zone mt-md" id="drop-zone">
								<div class="drop-zone__icon">📥</div>
								<div class="drop-zone__label">
									<strong>Choisir un fichier</strong>
									<span class="hint">.xlsx, .csv</span>
								</div>

								<input type="file" id="file-input" name="file" accept=".xlsx,.xls,.csv" hidden>
							</label>

							<button type="submit" style="margin-top:10px;">
								Importer
							</button>
						</form>

						<div id="file-name-badge">
							<span class="file-icon">📄</span>
							<span id="file-name-text">fichier.xlsx</span>
							<span class="remove-file" id="remove-file" title="Retirer le fichier">✕</span>
						</div>
					</div>
				</div>

				<!-- Colonne 3 : Options -->
				<div class="card">
					<div class="card__body" style="display:flex;flex-direction:column;height:100%;gap:var(--space-lg);">
						<p class="card__tag">Options d'import</p>

						<div class="form-group" style="margin-bottom:0;">
							<label class="form-label" for="annee-select">Année</label>
							<select id="annee-select" name="annee" class="form-select">
								@foreach($annees as $annee)
									<option value="{{ $annee['value'] }}">{{ $annee['text'] }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group" style="margin-bottom:0;">
							<label class="form-label" for="type-select">Type de données</label>
							<select id="type-select" name="type" class="form-select">
								@foreach($type_donnee as $type)
									<option value="{{ $type['value'] }}">{{ $type['text'] }}</option>
								@endforeach
							</select>
						</div>

						<div class="flex gap-sm mt-md" style="margin-top:auto;">
							<button class="btn btn--primary btn--sm w-full" id="import-btn">
								Importer
							</button>
							<button class="btn btn--ghost btn--sm" id="delete-btn" title="Supprimer le fichier sélectionné" style="flex-shrink:0;">
								🗑
							</button>
						</div>
					</div>
				</div>

			</div><!-- /panel-grid -->

			<section id="zone-tableau">
				<div class="section-title">
					<h2>Aperçu des données</h2>
				</div>

				<!-- Actions au-dessus du tableau -->
				<div class="table-actions">
					<span class="table-meta" id="table-meta-info">Aucune donnée chargée</span>
					<div class="flex gap-sm">
						<button class="btn btn--ghost btn--sm" id="btn-export" style="display:none;">
							⬇ Exporter
						</button>
						<button class="btn btn--secondary btn--sm" id="confirm-btn" style="display:none;">
							✓ Confirmer l'import
						</button>
					</div>
				</div>

				<!-- Tableau ou état vide -->
				<div class="card overflow-hidden" id="table-card">
					<div id="table-empty-state" class="empty-state">
						<div class="empty-state__icon">📋</div>
						<p class="empty-state__title">Aucun fichier importé</p>
						<p>Sélectionnez un fichier Excel ou CSV ci-dessus pour visualiser son contenu ici.</p>
					</div>
					<div class="data-table-wrapper" id="table-wrapper" style="display:none;">
						<table id="excel-table"></table>
					</div>
				</div>

				<!-- Pagination -->
				<nav class="pagination" id="paginationBar" aria-label="Pagination du tableau"></nav>
			</section>

			<section id="zone-liste-fichiers" class="mt-2xl" style="display:none;">
				<div class="section-title">
					<h2>Fichiers enregistrés</h2>
				</div>

				<div style="display:flex;justify-content:flex-end;margin-bottom:var(--space-lg);">
					<button class="btn btn--ghost btn--sm" id="delete-all-btn">
						🗑 Tout supprimer
					</button>
				</div>

				<div class="grid grid-3" id="liste-fichiers-container"></div>
			</section>

		</main>

		<footer style="border-top:1px solid var(--color-border);padding:var(--space-xl) 0;margin-top:var(--space-4xl);">
			<div class="container flex-between" style="font-size:var(--text-xs);color:var(--color-text-muted);">
				<span>© 2025 La Maison des Meringues</span>
				<a href="/" style="color:var(--color-text-muted);">Retour à l'accueil</a>
			</div>
		</footer>

		<div id="loading-overlay">
			<div class="loading-box">
				<div class="spinner"></div>
				<p id="traitement">Traitement en cours…</p>
			</div>
		</div>
	</body>
</html>
