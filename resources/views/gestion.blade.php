@include('templet.header',
	['titre' => 'Gestion des Données'],
	['note'  => 'Importer et gérer vos données'])
@vite('resources/css/gestion.css')
@vite('resources/css/data.css')

<div class="container" style="display:flex; gap:2rem;">

	{{-- ══ COLONNE GAUCHE ══ --}}
	<div class="left-column" style="flex:1;">

		<div class="grid grid-1 panel-grid mb-2xl">

			{{-- Statistiques --}}
			<div class="card">
				<div class="card__body">
					<p class="card__tag">Informations {{ $nom_boutique ?? '' }}</p>

					<div class="stat-card mt-md">
						<span class="stat-card__value">{{ $stock_total ?? '—' }}</span>
						<span class="stat-card__label">Stock — Total</span>
					</div>
					<hr class="mt-lg mb-lg">
					<div class="stat-card">
						<span class="stat-card__value">{{ $nb_produits ?? '—' }}</span>
						<span class="stat-card__label">Types de produit</span>
					</div>
					<hr class="mt-lg mb-lg">
				</div>
			</div>

			{{-- Import fichier --}}
			<div class="card">
				<div class="card__body" style="display:flex; flex-direction:column; height:100%;">
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

						{{-- Badge nom du fichier --}}
						<div id="file-name-badge">
							<span class="file-icon">📄</span>
							<span id="file-name-text">fichier.xlsx</span>
							<span class="remove-file" id="remove-file" title="Retirer le fichier">✕</span>
						</div>

						<button type="submit" class="btn btn--primary btn--sm w-full" style="margin-top:16px;">
							↑ Importer
						</button>
					</form>
				</div>
			</div>

			{{-- Options d'import --}}
			<div class="card">
				<div class="card__body" style="display:flex; flex-direction:column; height:100%; gap:var(--space-lg);">
					<p class="card__tag">Options d'import</p>

					<div class="form-group" style="margin-bottom:0;">
						<label class="form-label" for="type-select">Type de données</label>
						<select id="type-select" name="type" class="form-select" form="import-form">
							@foreach($type_donnee as $type)
								<option value="{{ $type['value'] }}">{{ $type['text'] }}</option>
							@endforeach
						</select>
					</div>

					<div class="flex gap-sm" style="margin-top:auto;">
						<button class="btn btn--ghost btn--sm" id="delete-btn" title="Supprimer le fichier sélectionné" style="flex-shrink:0;">
							🗑
						</button>
					</div>
				</div>
			</div>

		</div>{{-- /panel-grid --}}

		{{-- Aperçu des données --}}
		<section id="zone-tableau">
			<div class="section-title">
				<h2>Aperçu des données</h2>
			</div>

			@if($datas !== null)
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

			<div class="card overflow-hidden" id="table-card">
				@if($datas === null)
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
									@foreach($datas[0][0] as $cell)
										<th>{{ $cell }}</th>
									@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach(array_slice($datas[0], 1) as $row)
									<tr>
										@foreach($row as $cell)
											<td>{{ $cell }}</td>
										@endforeach
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			</div>

			<nav class="pagination" id="paginationBar" aria-label="Pagination du tableau"></nav>
		</section>

		{{-- Liste fichiers --}}
		<section id="zone-liste-fichiers" class="mt-2xl" style="display:none;">
			<div class="section-title">
				<h2>Fichiers enregistrés</h2>
			</div>
			<div style="display:flex; justify-content:flex-end; margin-bottom:var(--space-lg);">
				<button class="btn btn--ghost btn--sm" id="delete-all-btn">
					🗑 Tout supprimer
				</button>
			</div>
			<div class="grid grid-3" id="liste-fichiers-container"></div>
		</section>

	</div>{{-- /left-column --}}

	{{-- ══ COLONNE DROITE ══ --}}
	<div class="right-column" style="flex:2;">

		{{-- Onglets --}}
		<div class="tabs">
			<button class="tab active" data-tab="produits">🍬 Produits</button>
			<button class="tab" data-tab="formes">🔷 Formes</button>
			<button class="tab" data-tab="conditionnements">📦 Conditionnements</button>
			<button class="tab" data-tab="prix">💰 Prix</button>
		</div>

		{{-- ══ PRODUITS ══ --}}
		<div id="tab-produits" class="tab-panel">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">
					Produits <span class="data-count">{{ count($produits ?? []) }}</span>
				</h2>
				<button class="btn btn--primary btn--sm" id="btn-nvproduit">+ Nouveau produit</button>
			</div>

			@if(count($produits ?? []) > 0)
				<div class="table-wrapper">
					<table>
						<thead>
							<tr>
								<th>Code</th>
								<th>Article</th>
								<th>Description</th>
								<th>Forme</th>
								<th>Conditionnement</th>
								<th>Prix (€)</th>
								<th>Stock</th>
								<th class="th-actions">Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach($produits as $produit)
								<tr>
									<td class="td-id">{{ $produit->id_produit }}</td>
									<td class="td-name">{{ $produit->parfum->nom_parfum ?? '—' }}</td>
									<td class="td-desc text-muted">{{ Str::limit($produit->description, 60) }}</td>
									<td><span class="chip">{{ $produit->forme_condi->forme->nom_forme ?? '—' }}</span></td>
									<td>
										@foreach($produit->tous_conditionnements() as $fc)
											<span class="chip">{{ $fc->conditionnement->type }}</span>
										@endforeach
									</td>
									<td>
										@foreach($produit->tous_conditionnements() as $fc)
											<span class="chip chip--gold">{{ $fc->prix }} €</span>
										@endforeach
									</td>
									<td>
										@if($produit->quantite > 0)
											<span class="stock-badge stock-badge--ok">{{ $produit->quantite }}</span>
										@else
											<span class="stock-badge stock-badge--rupture">Rupture</span>
										@endif
									</td>
									<td class="td-actions">
										<a href="/gestion/produit/{{ $produit->id_produit }}/edit"
										   class="btn-icon btn-icon--edit" title="Modifier">✏️</a>
										<form action="/gestion/produit/{{ $produit->id_produit }}" method="POST"
											  class="form-delete"
											  onsubmit="return confirmSuppr('ce produit')">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn-icon btn-icon--delete" title="Supprimer">🗑️</button>
										</form>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@else
				<div class="data-empty">
					<p class="data-empty__icon">🍬</p>
					<p class="data-empty__text">Aucun produit enregistré pour le moment.</p>
					<a href="/gestion/produit/create" class="btn btn--primary">Créer un produit</a>
				</div>
			@endif
		</div>

		{{-- ══ FORMES ══ --}}
		<div id="tab-formes" class="tab-panel hidden">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">
					Formes <span class="data-count">{{ count($formes ?? []) }}</span>
				</h2>
				<a href="/gestion/forme/create" class="btn btn--primary btn--sm">+ Nouvelle forme</a>
			</div>

			@if(count($formes ?? []) > 0)
				<div class="table-wrapper">
					<table>
						<thead>
							<tr>
								<th>#</th>
								<th>Nom</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach($formes as $forme)
								<tr>
									<td class="td-id">{{ $forme->id_forme }}</td>
									<td class="td-name">{{ $forme->nom_forme }}</td>
									<td class="td-actions">
										<a href="/gestion/forme/{{ $forme->id_forme }}/edit"
										   class="btn-icon btn-icon--edit" title="Modifier">✏️</a>
										<form action="/gestion/forme/{{ $forme->id_forme }}" method="POST"
											  class="form-delete"
											  onsubmit="return confirmSuppr('cette forme')">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn-icon btn-icon--delete" title="Supprimer">🗑️</button>
										</form>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@else
				<div class="data-empty">
					<p class="data-empty__icon">🔷</p>
					<p class="data-empty__text">Aucune forme enregistrée pour le moment.</p>
					<a href="/gestion/forme/create" class="btn btn--primary">Créer une forme</a>
				</div>
			@endif
		</div>

		{{-- ══ CONDITIONNEMENTS ══ --}}
		<div id="tab-conditionnements" class="tab-panel hidden">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">
					Conditionnements <span class="data-count">{{ count($conditionnements ?? []) }}</span>
				</h2>
				<a href="/gestion/conditionnement/create" class="btn btn--primary btn--sm">+ Nouveau conditionnement</a>
			</div>

			@if(count($conditionnements ?? []) > 0)
				<div class="table-wrapper">
					<table>
						<thead>
							<tr>
								<th>#</th>
								<th>Nom</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach($conditionnements as $cond)
								<tr>
									<td class="td-id">{{ $cond->id_condi }}</td>
									<td class="td-name">{{ $cond->type }}</td>
									<td class="td-actions">
										<a href="/gestion/conditionnement/{{ $cond->id_condi }}/edit"
										   class="btn-icon btn-icon--edit" title="Modifier">✏️</a>
										<form action="/gestion/conditionnement/{{ $cond->id_condi }}" method="POST"
											  class="form-delete"
											  onsubmit="return confirmSuppr('ce conditionnement')">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn-icon btn-icon--delete" title="Supprimer">🗑️</button>
										</form>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@else
				<div class="data-empty">
					<p class="data-empty__icon">📦</p>
					<p class="data-empty__text">Aucun conditionnement enregistré pour le moment.</p>
					<a href="/gestion/conditionnement/create" class="btn btn--primary">Créer un conditionnement</a>
				</div>
			@endif
		</div>

		{{-- ══ PRIX ══ --}}
		<div id="tab-prix" class="tab-panel hidden">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">
					Prix <span class="data-count">{{ count($forme_condi ?? []) }}</span>
				</h2>
				<a href="/gestion/forme_condi/create" class="btn btn--primary btn--sm">+ Nouveau prix</a>
			</div>

			@if(count($forme_condi ?? []) > 0)
				<div class="table-wrapper">
					<table>
						<thead>
							<tr>
								<th>#</th>
								<th>Forme</th>
								<th>Conditionnement</th>
								<th>Prix</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach($forme_condi as $fc)
								<tr>
									<td class="td-id">{{ $fc->id_forme_condi }}</td>
									<td class="td-name-forme">{{ $fc->forme->nom_forme }}</td>
									<td class="td-name-condi">{{ $fc->conditionnement->type }}</td>
									<td class="td-prix">{{ $fc->prix }} €</td>
									<td class="td-actions">
										<a href="/gestion/forme_condi/{{ $fc->id_forme_condi }}/edit"
										   class="btn-icon btn-icon--edit" title="Modifier">✏️</a>
										<form action="/gestion/forme_condi/{{ $fc->id_forme_condi }}" method="POST"
											  class="form-delete"
											  onsubmit="return confirmSuppr('ce prix')">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn-icon btn-icon--delete" title="Supprimer">🗑️</button>
										</form>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@else
				<div class="data-empty">
					<p class="data-empty__icon">💰</p>
					<p class="data-empty__text">Aucun prix enregistré pour le moment.</p>
					<a href="/gestion/forme_condi/create" class="btn btn--primary">Créer un prix</a>
				</div>
			@endif
		</div>

	</div>{{-- /right-column --}}
</div>{{-- /container --}}

{{-- ══ MODAL NOUVEAU PRODUIT ══ --}}
<div id="modal-produit" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Nouveau produit</h2>
		<form action="/gestion/produit" method="POST">
			@csrf

			<label for="modal-parfum">Article (parfum)</label>
			<input type="text" id="modal-parfum" name="parfum" placeholder="Ex : Rose sauvage" required>

			<label for="modal-description">Description</label>
			<input type="text" id="modal-description" name="description" placeholder="Description courte">

			<label for="modal-quantite">Quantité en stock</label>
			<input type="number" id="modal-quantite" name="quantite" min="0" value="0">

			<label for="modal-forme-condi">Forme / Conditionnement</label>
			<select id="modal-forme-condi" name="id_forme_condi">
				@foreach($forme_condi as $fc)
					<option value="{{ $fc->id_forme_condi }}">
						{{ $fc->forme->nom_forme }} — {{ $fc->conditionnement->type }} ({{ $fc->prix }} €)
					</option>
				@endforeach
			</select>

			<div class="modal-actions">
				<button type="button" id="close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">Créer le produit</button>
			</div>
		</form>
	</div>
</div>

{{-- ══ OVERLAY CHARGEMENT ══ --}}
<div id="loading-overlay">
	<div class="loading-box">
		<div class="spinner"></div>
		<p id="traitement">Traitement en cours…</p>
	</div>
</div>

<script>
	// ── Gestion du fichier ──────────────────────────────────────────────
	const inputFichier = document.getElementById('file-input');
	const badge        = document.getElementById('file-name-badge');
	const badgeText    = document.getElementById('file-name-text');
	const removeBtn    = document.getElementById('remove-file');

	inputFichier.addEventListener('change', function () {
		if (inputFichier.files.length > 0) {
			badgeText.textContent = inputFichier.files[0].name;
			badge.classList.add('visible');
		}
	});

	removeBtn.addEventListener('click', function () {
		inputFichier.value = '';        // reset réel de l'input
		badge.classList.remove('visible');
	});

	// Drag & drop sur la zone
	const dropZone = document.getElementById('drop-zone');
	dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
	dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
	dropZone.addEventListener('drop', e => {
		e.preventDefault();
		dropZone.classList.remove('drag-over');
		const files = e.dataTransfer.files;
		if (files.length > 0) {
			inputFichier.files = files;
			badgeText.textContent = files[0].name;
			badge.classList.add('visible');
		}
	});

	// ── Onglets ────────────────────────────────────────────────────────
	document.querySelectorAll('.tabs .tab').forEach(btn => {
		btn.addEventListener('click', () => {
			// Retirer l'état actif de tous les boutons et panels
			document.querySelectorAll('.tabs .tab').forEach(t => t.classList.remove('active'));
			document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
			// Activer le bouton cliqué et son panel
			btn.classList.add('active');
			document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
		});
	});

	// ── Confirmation suppression ────────────────────────────────────────
	function confirmSuppr(label) {
		return confirm('Voulez-vous vraiment supprimer ' + label + ' ? Cette action est irréversible.');
	}

	// ── Modal nouveau produit ───────────────────────────────────────────
	const modal       = document.getElementById('modal-produit');
	const btnNvProduit = document.getElementById('btn-nvproduit');
	const closeModal   = document.getElementById('close-modal');

	btnNvProduit.addEventListener('click', () => modal.classList.remove('hidden'));
	closeModal.addEventListener('click',   () => modal.classList.add('hidden'));

	// Fermer en cliquant sur le fond
	modal.addEventListener('click', e => {
		if (e.target === modal) modal.classList.add('hidden');
	});

	// ── Overlay chargement à la soumission du form ──────────────────────
	document.getElementById('import-form').addEventListener('submit', () => {
		document.getElementById('loading-overlay').classList.add('visible');
	});
</script>
