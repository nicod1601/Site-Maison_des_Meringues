@include('templet.header',
		['titre' => 'Gestionnaire des Données'],
		['note' => 'Importer vos donner pour mettre à jour votre boutique'],
		['style' => 'resources/css/gestion.css'])

			<div class="grid grid-3 panel-grid mb-2xl">
				<!-- Colonne 1 : Statistiques -->
				<div class="card">
					<div class="card__body">
						<p class="card__tag">Informations {{ $nom_boutique ?? ""}}</p>
						<div class="stat-card mt-md">
							<span class="stat-card__value" id="dossier-count">{{$stock_total ?? '—'}}</span>
							<span class="stat-card__label">Stock - Total</span>
						</div>

						<hr class="mt-lg mb-lg">

                        <div class="stat-card mt-md">
							<span class="stat-card__value" id="dossier-count">{{ $nb_produits ?? '—'}}</span>
							<span class="stat-card__label">Type de Produit</span>
						</div>

                        <hr class="mt-lg mb-lg">
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
									<strong id="name-file">Choisir un fichier</strong>
									<span class="hint">.xlsx, .csv</span>
								</div>

								<input type="file" id="file-input" name="file" accept=".xlsx,.xls,.csv" hidden>
							</label>

							<button type="submit" style="margin-top:20px; text-align:center;" class="btn btn--primary btn--sm w-full">
								Confirmer
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
				@if ($datas !== null)
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
				@endif

				<!-- Tableau ou état vide -->
				<div class="card overflow-hidden" id="table-card">
					@if ($datas === null)
						<div id="table-empty-state" class="empty-state">
							<div class="empty-state__icon">📋</div>
							<p class="empty-state__title">Aucun fichier importé</p>
							<p>Sélectionnez un fichier Excel ou CSV ci-dessus pour visualiser son contenu ici.</p>
						</div>
					@else
						<div class="data-table-wrapper" id="table-wrapper">
							<table id="excel-table">
								<thead>
									<tr>
										@foreach ($datas[0][0] as $cell)
											<th>{{ $cell }}</th>
										@endforeach
									</tr>
								</thead>
								<tbody>
									@foreach (array_slice($datas[0], 1) as $row)
										<tr>
											@foreach ($row as $cell)
												<td>{{ $cell }}</td>
											@endforeach
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@endif
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

		<script>
			const InputFichier = document.getElementById('file-input');
			const nomFichier   = document.getElementById('name-file');
			const badge        = document.getElementById('file-name-badge');
			const badgeText    = document.getElementById('file-name-text');
			const removeBtn    = document.getElementById('remove-file');

			InputFichier.addEventListener('change', function () {
				if (InputFichier.files.length > 0) {
					const name = InputFichier.files[0].name;
					//nomFichier.textContent = name;
					badgeText.textContent  = name;
					badge.classList.add('visible');
				}
			});

			removeBtn.addEventListener('click', function () {
				//InputFichier.value    = '';
				//nomFichier.textContent = 'Choisir un fichier';
				badge.classList.remove('visible');
			});

			const lightBtn = document.getElementById('light');
			lightBtn.addEventListener('click', function () {
				const body = document.body;

				if(body.style.backgroundColor === "black")
					body.style.backgroundColor = "white";
				else
					body.style.backgroundColor = "black";
			});
		</script>
	</body>
</html>
