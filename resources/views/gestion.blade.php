@include('templet.header',
	['titre' => 'Gestion des Données'],
	['note'  => 'Importer et gérer vos données'])
@vite('resources/css/gestion.css')
@vite('resources/css/data.css')

<div class="container" style="display:flex; gap:2rem; align-items:flex-start;">

	{{-- ══ COLONNE GAUCHE ══ --}}
	<div class="left-column" style="flex:0 0 220px; width:220px; min-width:220px;">

		<div class="grid grid-1 panel-grid mb-2xl">

			{{-- Statistiques --}}
			<div class="card">
				<div class="card__body">
					<p class="card__tag" style="white-space:normal; word-break:break-word; font-size:var(--text-xs);">
						Infos {{ $nom_boutique ?? '' }}
					</p>
					<div class="stat-card mt-md">
						<span class="stat-card__value">{{ $stock_total ?? '—' }}</span>
						<span class="stat-card__label" style="white-space:normal;">Stock — Total</span>
					</div>
					<hr class="mt-lg mb-lg">
					<div class="stat-card">
						<span class="stat-card__value">{{ $nb_produits ?? '—' }}</span>
						<span class="stat-card__label" style="white-space:normal;">Types de produit</span>
					</div>
					<hr class="mt-lg mb-lg">
					<div class="stat-card">
						<span class="stat-card__value">{{ count($rayons) }}</span>
						<span class="stat-card__label" style="white-space:normal;">Rayons</span>
					</div>
					<hr class="mt-lg mb-lg">
					<div class="stat-card">
						<span class="stat-card__value">{{ count($themes) }}</span>
						<span class="stat-card__label" style="white-space:normal;">Thèmes</span>
					</div>
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

			{{-- Sélection rayon import --}}
			<div class="card">
				<div class="card__body" style="display:flex; flex-direction:column; height:100%; gap:var(--space-lg);">
					<p class="card__tag">Sélectionner le Rayon</p>
					<div class="form-group" style="margin-bottom:0;">
						<label class="form-label" for="type-select">Rayon d'import</label>
						<select id="type-select" name="id_rayon" class="form-select" form="import-form">
							<option value="-1">Sélectionner un rayon</option>
							@foreach($rayons as $rayon)
								<option value="{{ $rayon->id_rayon }}">
									{{ $rayon->theme ? $rayon->theme->icone.' ' : '' }}{{ $rayon->nom_rayon }}
								</option>
							@endforeach
						</select>
					</div>
					<div class="flex gap-sm" style="margin-top:auto;">
						<button class="btn btn--ghost btn--sm" id="btn-nvrayon" title="Nouveau Rayon" style="flex-shrink:0;">
							➕ Rayon
						</button>
						<button class="btn btn--ghost btn--sm" id="btn-nvtheme" title="Nouveau Thème" style="flex-shrink:0;">
							🎨 Thème
						</button>
					</div>
				</div>
			</div>

		</div>

		{{-- Aperçu import --}}
		<section id="zone-tableau" style="overflow:hidden; max-width:100%;">
			<div class="section-title"><h2>Aperçu</h2></div>
			<div class="card overflow-hidden" style="max-width:100%;">
				@if($datas === null)
					<div class="empty-state">
						<div class="empty-state__icon">📋</div>
						<p class="empty-state__title">Aucun fichier importé</p>
						<p>Sélectionnez un fichier ci-dessus.</p>
					</div>
				@else
					<div style="overflow-x:auto; max-width:100%; max-height:300px;">
						<table id="excel-table" style="min-width:400px;">
							<thead>
								<tr>@foreach($datas[0][0] as $cell)<th>{{ $cell }}</th>@endforeach</tr>
							</thead>
							<tbody>
								@foreach(array_slice($datas[0], 1) as $row)
									<tr>@foreach($row as $cell)<td>{{ $cell }}</td>@endforeach</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			</div>
		</section>

	</div>{{-- /left-column --}}

	{{-- ══ COLONNE DROITE ══ --}}
	<div class="right-column" style="flex:2; min-width:0;">

		{{-- Onglets --}}
		<div class="tabs">
			<button class="tab active" data-tab="produits">🍬 Produits</button>
			<button class="tab" data-tab="rayons">🗂 Rayons</button>
			<button class="tab" data-tab="themes">🎨 Thèmes</button>
			<button class="tab" data-tab="formes">🔷 Formes</button>
			<button class="tab" data-tab="conditionnements">📦 Conditionnements</button>
			<button class="tab" data-tab="prix">💰 Prix</button>

			<select id="select-rayon" class="form-select form-select--sm" style="margin-left:auto; max-width:160px;">
				<option value="-1" {{ !$rayonId || $rayonId == '-1' ? 'selected' : '' }}>Tous les rayons</option>
				@foreach($rayons as $rayon)
					<option value="{{ $rayon->id_rayon }}" {{ (string)$rayonId === (string)$rayon->id_rayon ? 'selected' : '' }}>
						{{ $rayon->theme ? $rayon->theme->icone.' ' : '' }}{{ $rayon->nom_rayon }}
					</option>
				@endforeach
			</select>
		</div>

		{{-- ══ PRODUITS ══ --}}
		<div id="tab-produits" class="tab-panel">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">
					Produits <span class="data-count">{{ count($produits) }}</span>
					@if($rayonId && $rayonId != '-1')
						@php $rayonActif = $rayons->firstWhere('id_rayon', $rayonId); @endphp
						@if($rayonActif)
							<span class="chip chip--gold" style="font-size:var(--text-xs);">
								{{ $rayonActif->theme ? $rayonActif->theme->icone.' ' : '📦 ' }}{{ $rayonActif->nom_rayon }}
							</span>
						@endif
					@endif
				</h2>
				<button class="btn btn--primary btn--sm" id="btn-nvproduit">+ Nouveau produit</button>
			</div>

			@if(count($produits) > 0)
				<div class="table-wrapper">
					<table>
						<thead>
							<tr>
								<th>#</th><th>Article</th><th>Description</th>
								<th>Rayon</th><th>Thème</th><th>Forme</th>
								<th>Conditionnement</th><th>Prix (€)</th><th>Stock</th>
								<th class="th-actions">Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach($produits as $produit)
							<tr>
								<td class="td-id">{{ $produit->id_produit }}</td>
								<td class="td-name">{{ $produit->parfum->nom_parfum ?? '—' }}</td>
								<td class="td-desc text-muted">{{ Str::limit($produit->description, 50) }}</td>
								<td><span class="chip">{{ $produit->rayon->nom_rayon ?? '—' }}</span></td>
								<td>
									@if($produit->theme)
										<span class="chip" style="background:{{ $produit->theme->couleur }}20; border-color:{{ $produit->theme->couleur }};">
											{{ $produit->theme->icone }} {{ $produit->theme->nom_theme }}
										</span>
									@else
										<span class="text-muted">—</span>
									@endif
								</td>
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
									<form action="/gestion/produit/{{ $produit->id_produit }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('ce produit')">
										@csrf @method('DELETE')
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
					<p class="data-empty__text">Aucun produit enregistré.</p>
				</div>
			@endif
		</div>

		{{-- ══ RAYONS ══ --}}
		<div id="tab-rayons" class="tab-panel hidden">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">Rayons <span class="data-count">{{ count($rayons) }}</span></h2>
				<button class="btn btn--primary btn--sm" id="btn-nvrayon2">+ Nouveau rayon</button>
			</div>
			@if(count($rayons) > 0)
				<div class="table-wrapper">
					<table>
						<thead>
							<tr><th>#</th><th>Nom</th><th>Thème</th><th>Stock</th><th class="th-actions">Actions</th></tr>
						</thead>
						<tbody>
							@foreach($rayons as $rayon)
							<tr>
								<td class="td-id">{{ $rayon->id_rayon }}</td>
								<td class="td-name">{{ $rayon->nom_rayon }}</td>
								<td>
									@forelse($rayon->themes as $theme)
										<span class="chip" style="background:{{ $theme->couleur }}20; border-color:{{ $theme->couleur }};">
											{{ $theme->icone }} {{ $theme->nom_theme }}
										</span>
									@empty
										<span class="text-muted">—</span>
									@endforelse
								</td>
								<td><span class="stock-badge stock-badge--ok">{{ $rayon->stock_total_rayon }}</span></td>
								<td class="td-actions">
									<form action="/gestion/rayon/{{ $rayon->id_rayon }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('ce rayon')">
										@csrf @method('DELETE')
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
					<p class="data-empty__icon">🗂</p>
					<p class="data-empty__text">Aucun rayon enregistré.</p>
				</div>
			@endif
		</div>

		{{-- ══ THÈMES ══ --}}
		<div id="tab-themes" class="tab-panel hidden">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">Thèmes <span class="data-count">{{ count($themes) }}</span></h2>
				<button class="btn btn--primary btn--sm" id="btn-nvtheme2">+ Nouveau thème</button>
			</div>
			@if(count($themes) > 0)
				<div class="table-wrapper">
					<table>
						<thead>
							<tr><th>#</th><th>Thème</th><th>Icône</th><th>Couleur</th><th>Rayons liés</th><th class="th-actions">Actions</th></tr>
						</thead>
						<tbody>
							@foreach($themes as $theme)
							<tr>
								<td class="td-id">{{ $theme->id_theme }}</td>
								<td class="td-name">{{ $theme->nom_theme }}</td>
								<td style="font-size:1.4rem;">{{ $theme->icone }}</td>
								<td>
									<span style="display:inline-flex; align-items:center; gap:6px;">
										<span style="width:16px; height:16px; border-radius:50%; background:{{ $theme->couleur }}; border:1px solid var(--color-border); display:inline-block;"></span>
										{{ $theme->couleur }}
									</span>
								</td>
								<td>
									@php $nb = $rayons->where('id_theme', $theme->id_theme)->count(); @endphp
									<span class="data-count">{{ $nb }}</span>
								</td>
								<td class="td-actions">
									<form action="/gestion/theme/{{ $theme->id_theme }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('ce thème')">
										@csrf @method('DELETE')
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
					<p class="data-empty__icon">🎨</p>
					<p class="data-empty__text">Aucun thème enregistré.</p>
				</div>
			@endif
		</div>

		{{-- ══ FORMES ══ --}}
		<div id="tab-formes" class="tab-panel hidden">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">Formes <span class="data-count">{{ count($formes) }}</span></h2>
			</div>
			@if(count($formes) > 0)
				<div class="table-wrapper">
					<table>
						<thead><tr><th>#</th><th>Nom</th><th>Actions</th></tr></thead>
						<tbody>
							@foreach($formes as $forme)
							<tr>
								<td class="td-id">{{ $forme->id_forme }}</td>
								<td class="td-name">{{ $forme->nom_forme }}</td>
								<td class="td-actions">
									<form action="/gestion/forme/{{ $forme->id_forme }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('cette forme')">
										@csrf @method('DELETE')
										<button type="submit" class="btn-icon btn-icon--delete">🗑️</button>
									</form>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@else
				<div class="data-empty"><p class="data-empty__icon">🔷</p><p class="data-empty__text">Aucune forme.</p></div>
			@endif
		</div>

		{{-- ══ CONDITIONNEMENTS ══ --}}
		<div id="tab-conditionnements" class="tab-panel hidden">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">Conditionnements <span class="data-count">{{ count($conditionnements) }}</span></h2>
			</div>
			@if(count($conditionnements) > 0)
				<div class="table-wrapper">
					<table>
						<thead><tr><th>#</th><th>Nom</th><th>Actions</th></tr></thead>
						<tbody>
							@foreach($conditionnements as $cond)
							<tr>
								<td class="td-id">{{ $cond->id_condi }}</td>
								<td class="td-name">{{ $cond->type }}</td>
								<td class="td-actions">
									<form action="/gestion/conditionnement/{{ $cond->id_condi }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('ce conditionnement')">
										@csrf @method('DELETE')
										<button type="submit" class="btn-icon btn-icon--delete">🗑️</button>
									</form>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@else
				<div class="data-empty"><p class="data-empty__icon">📦</p><p class="data-empty__text">Aucun conditionnement.</p></div>
			@endif
		</div>

		{{-- ══ PRIX ══ --}}
		<div id="tab-prix" class="tab-panel hidden">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">Prix <span class="data-count">{{ count($forme_condi) }}</span></h2>
			</div>
			@if(count($forme_condi) > 0)
				<div class="table-wrapper">
					<table>
						<thead><tr><th>#</th><th>Forme</th><th>Conditionnement</th><th>Prix</th><th>Actions</th></tr></thead>
						<tbody>
							@foreach($forme_condi as $fc)
							<tr>
								<td class="td-id">{{ $fc->id_forme_condi }}</td>
								<td>{{ $fc->forme->nom_forme }}</td>
								<td>{{ $fc->conditionnement->type }}</td>
								<td class="td-prix">{{ $fc->prix }} €</td>
								<td class="td-actions">
									<form action="/gestion/forme_condi/{{ $fc->id_forme_condi }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('ce prix')">
										@csrf @method('DELETE')
										<button type="submit" class="btn-icon btn-icon--delete">🗑️</button>
									</form>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@else
				<div class="data-empty"><p class="data-empty__icon">💰</p><p class="data-empty__text">Aucun prix.</p></div>
			@endif
		</div>

	</div>{{-- /right-column --}}
</div>

{{-- ══ MODAL NOUVEAU PRODUIT ══ --}}
<div id="modal-produit" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Nouveau produit</h2>
		<form action="/gestion/produit/0" method="POST">
			@csrf
			<label>Parfum</label>
			<select name="id_parfum" required>
				@foreach($parfums as $p)
					<option value="{{ $p->id_parfum }}">{{ $p->nom_parfum }}</option>
				@endforeach
			</select>
			<label>Forme / Conditionnement</label>
			<select name="id_forme_condi" required>
				@foreach($forme_condi as $fc)
					<option value="{{ $fc->id_forme_condi }}">{{ $fc->forme->nom_forme }} — {{ $fc->conditionnement->type }} ({{ $fc->prix }} €)</option>
				@endforeach
			</select>
			<label>Rayon</label>
			<select name="id_rayon" required>
				@foreach($rayons as $rayon)
					<option value="{{ $rayon->id_rayon }}" {{ $rayonId == $rayon->id_rayon ? 'selected' : '' }}>
						{{ $rayon->theme ? $rayon->theme->icone.' ' : '' }}{{ $rayon->nom_rayon }}
					</option>
				@endforeach
			</select>
			<label>Description</label>
			<input type="text" name="description" placeholder="Description courte">
			<label>Quantité</label>
			<input type="number" name="quantite" min="0" value="0">
			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">Créer</button>
			</div>
		</form>
	</div>
</div>

{{-- ══ MODAL NOUVEAU RAYON ══ --}}
<div id="modal-rayon" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Nouveau rayon</h2>
		<form action="{{ route('nvrayon') }}" method="POST">
			@csrf
			<input type="hidden" name="id_boutique" value="{{ $boutique->id_boutique }}">

			<label>Nom du rayon</label>
			<input type="text" name="nom_rayon" placeholder="Ex : Noël, Printemps…" required>

			<label>Thèmes associés <span style="font-weight:400; text-transform:none;">(optionnel)</span></label>
			<div style="display:flex; flex-wrap:wrap; gap:var(--space-sm); margin-top:var(--space-xs);">
				@foreach($themes as $theme)
					<label style="
						display:inline-flex;
						align-items:center;
						gap:6px;
						padding: 6px 12px;
						border-radius: var(--radius-full);
						border: 1.5px solid var(--color-border);
						background: var(--color-cream);
						cursor: pointer;
						font-size: var(--text-xs);
						font-weight: 600;
						transition: all var(--transition-fast);
						user-select: none;
					"
					onmouseenter="this.style.borderColor='{{ $theme->couleur }}'; this.style.background='{{ $theme->couleur }}20';"
					onmouseleave="if(!this.querySelector('input').checked){ this.style.borderColor='var(--color-border)'; this.style.background='var(--color-cream)'; }"
					>
						<input
							type="checkbox"
							name="id_themes[]"
							value="{{ $theme->id_theme }}"
							style="display:none;"
							onchange="
								if(this.checked){
									this.closest('label').style.borderColor='{{ $theme->couleur }}';
									this.closest('label').style.background='{{ $theme->couleur }}20';
									this.closest('label').style.color='{{ $theme->couleur }}';
								} else {
									this.closest('label').style.borderColor='var(--color-border)';
									this.closest('label').style.background='var(--color-cream)';
									this.closest('label').style.color='inherit';
								}
							"
						>
						{{ $theme->icone }} {{ $theme->nom_theme }}
					</label>
				@endforeach
			</div>

			{{-- Aperçu du nombre de produits qui seront copiés --}}
			<div id="preview-produits" style="
				margin-top: var(--space-lg);
				padding: var(--space-md);
				background: var(--color-cream);
				border-radius: var(--radius-md);
				border: 1px solid var(--color-border);
				font-size: var(--text-xs);
				color: var(--color-text-muted);
				display: none;
			">
				🔍 <span id="preview-text">0 produit(s) seront copiés dans ce rayon</span>
			</div>

			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">Créer le rayon</button>
			</div>
		</form>
	</div>
</div>

{{-- ══ MODAL NOUVEAU THÈME ══ --}}
<div id="modal-theme" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Nouveau thème</h2>
		<form action="{{ route('nvtheme') }}" method="POST">
			@csrf
			<label>Nom du thème</label>
			<input type="text" name="nom_theme" placeholder="Ex : Fleurs, Fruits, Noël…" required>
			<label>Icône (emoji)</label>
			<input type="text" name="icone" placeholder="Ex : 🌸" maxlength="4" value="🎨">
			<label>Couleur</label>
			<input type="color" name="couleur" value="#C0395A" style="height:42px; padding:4px 8px;">
			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">Créer le thème</button>
			</div>
		</form>
	</div>
</div>

{{-- ══ OVERLAY CHARGEMENT ══ --}}
<div id="loading-overlay">
	<div class="loading-box">
		<div class="spinner"></div>
		<p>Traitement en cours…</p>
	</div>
</div>

<script>
	// Fichier
	const inputFichier = document.getElementById('file-input');
	const badge        = document.getElementById('file-name-badge');
	const badgeText    = document.getElementById('file-name-text');
	const removeBtn    = document.getElementById('remove-file');

	inputFichier.addEventListener('change', () => {
		if (inputFichier.files.length > 0) { badgeText.textContent = inputFichier.files[0].name; badge.classList.add('visible'); }
	});
	removeBtn.addEventListener('click', () => { inputFichier.value = ''; badge.classList.remove('visible'); });

	const dropZone = document.getElementById('drop-zone');
	dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
	dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
	dropZone.addEventListener('drop', e => {
		e.preventDefault(); dropZone.classList.remove('drag-over');
		if (e.dataTransfer.files.length > 0) { inputFichier.files = e.dataTransfer.files; badgeText.textContent = e.dataTransfer.files[0].name; badge.classList.add('visible'); }
	});

	// Onglets
	document.querySelectorAll('.tabs .tab').forEach(btn => {
		btn.addEventListener('click', () => {
			document.querySelectorAll('.tabs .tab').forEach(t => t.classList.remove('active'));
			document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
			btn.classList.add('active');
			document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
		});
	});

	// Confirmation suppression
	function confirmSuppr(label) { return confirm('Voulez-vous vraiment supprimer ' + label + ' ?'); }

	// Ouverture modals
	document.getElementById('btn-nvproduit') .addEventListener('click', () => document.getElementById('modal-produit').classList.remove('hidden'));
	document.getElementById('btn-nvrayon')   .addEventListener('click', () => document.getElementById('modal-rayon')  .classList.remove('hidden'));
	document.getElementById('btn-nvrayon2')  .addEventListener('click', () => document.getElementById('modal-rayon')  .classList.remove('hidden'));
	document.getElementById('btn-nvtheme')   .addEventListener('click', () => document.getElementById('modal-theme')  .classList.remove('hidden'));
	document.getElementById('btn-nvtheme2')  .addEventListener('click', () => document.getElementById('modal-theme')  .classList.remove('hidden'));

	// Fermeture modals
	document.addEventListener('click', e => {
		if (e.target.classList.contains('data-modal') || e.target.classList.contains('btn-close-modal')) {
			document.querySelectorAll('.data-modal').forEach(m => m.classList.add('hidden'));
		}
	});

	// Overlay import
	document.getElementById('import-form').addEventListener('submit', () => document.getElementById('loading-overlay').classList.add('visible'));

	// Validation import
	const typeSelect = document.getElementById('type-select');
	const submitBtn  = document.getElementById('import-form').querySelector('button[type="submit"]');
	function updateSubmitState() { submitBtn.disabled = !(typeSelect.value !== '-1' && inputFichier.files.length > 0); }
	typeSelect.addEventListener('change', updateSubmitState);
	inputFichier.addEventListener('change', updateSubmitState);
	removeBtn.addEventListener('click', () => setTimeout(updateSubmitState, 10));
	updateSubmitState();

	// Filtre rayon
	document.getElementById('select-rayon').addEventListener('change', function () {
		const url = new URL(window.location.href);
		this.value === '-1' ? url.searchParams.delete('rayon') : url.searchParams.set('rayon', this.value);
		window.location.href = url.toString();
	});

    // Aperçu produits copiés dans nouveau rayon
    // Données produits par thème (passées depuis le contrôleur)
    const produitsByTheme = @json($produitsByTheme);

    // Aperçu dynamique dans le modal rayon
    document.querySelectorAll('input[name="id_themes[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updatePreview);
    });

    function updatePreview() {
        const checked = [...document.querySelectorAll('input[name="id_themes[]"]:checked')]
            .map(cb => parseInt(cb.value));

        const preview = document.getElementById('preview-produits');
        const text    = document.getElementById('preview-text');

        if (checked.length === 0) {
            preview.style.display = 'none';
            return;
        }

        // Compter les produits uniques (sans doublons si thèmes partagés)
        let total = 0;
        checked.forEach(id => { total += produitsByTheme[id] || 0; });

        preview.style.display = 'block';
        text.textContent = `🔍 ${total} produit(s) seront copiés dans ce nouveau rayon`;
    }
</script>
