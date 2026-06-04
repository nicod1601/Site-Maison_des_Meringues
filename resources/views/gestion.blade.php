@vite('resources/css/gestion.css')

{{-- TOPBAR --}}
<header class="topbar">
	<span class="topbar__brand">La Maison des <em>Meringues</em></span>
	<a href="{{ route('index') }}" class="topbar__back">← Retour à l'accueil</a>
</header>
<style>
	*{
		cursor: url("{{ asset('fichier/image/sourie/cursor-50.png') }}") 0 0, auto !important;
	}
</style>

<main class="container section">
<div class="container gestion-layout">

	{{-- ══ COLONNE GAUCHE ══ --}}
	<div class="gestion-left-panel">

		<div class="grid grid-1 panel-grid mb-2xl">

			{{-- Statistiques --}}
			<div class="card">
				<div class="card__body">
					<p class="card__tag card__tag--wrap">Infos {{ $nom_boutique ?? '' }}</p>
					<div class="stat-grid mt-md">
						<div class="stat-item">
							<span class="stat-item__value">{{ $stock_total ?? '—' }}</span>
							<span class="stat-item__label">Stock total</span>
						</div>
						<div class="stat-item">
							<span class="stat-item__value">{{ $nb_produits ?? '—' }}</span>
							<span class="stat-item__label">Produits</span>
						</div>
						<div class="stat-item">
							<span class="stat-item__value">{{ count($rayons) }}</span>
							<span class="stat-item__label">Rayons</span>
						</div>
						<div class="stat-item">
							<span class="stat-item__value">{{ count($parfums) }}</span>
							<span class="stat-item__label">Parfums</span>
						</div>
					</div>
				</div>
			</div>

			{{-- Import fichier --}}
			<div class="card">
				<div class="card__body card--flex-column">
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
						<button type="submit" class="btn btn--primary btn--sm w-full btn-submit-modal">
							↑ Importer
						</button>
					</form>

					<p class="card__tag" style="margin-top: 30px">Les Boutons création </p>
					<div class="grid grid-2 mt-md">
						<button class="btn btn--ghost btn--sm btn--flex-shrink" id="btn-nvrayon" title="Nouveau Rayon">
							➕ Rayon
						</button>
						<button class="btn btn--ghost btn--sm btn--flex-shrink" id="btn-nvevent" title="Nouvel Event">
							🎉 Event
						</button>
						<button class="btn btn--ghost btn--sm btn--flex-shrink" id="btn-nvproduit" title="Nouveau Produit">
							➕ Produit
						</button>
						<button class="btn btn--ghost btn--sm btn--flex-shrink" id="btn-nvparfum" title="Nouveau Parfum">
							🍓 Parfum
						</button>
						<button class="btn btn--ghost btn--sm btn--flex-shrink" id="btn-nvforme" title="Nouvelle Forme">
							🔷 Forme
						</button>
						<button class="btn btn--ghost btn--sm btn--flex-shrink" id="btn-nvcondi" title="Nouveau Conditionnement">
							📦 Conditionnement
						</button>
						<button class="btn btn--ghost btn--sm btn--flex-shrink" id="btn-nvtheme2" title="Nouveau Thème">
							🎨 Thème
						</button>

					</div>
				</div>
			</div>

		</div>

	</div>{{-- /gestion-left-panel --}}

	{{-- ══ COLONNE DROITE ══ --}}
	<div class="gestion-right-panel">

		{{-- Onglets --}}
		<div class="tabs">
			<button class="tab active" data-tab="produits">🍬 Produits</button>
			<button class="tab" data-tab="images">🖼️ Images</button>
			<button class="tab" data-tab="rayons">🗂 Rayons</button>
			<button class="tab" data-tab="formes">🔷 Formes</button>
			<button class="tab" data-tab="conditionnements">📦 Condi.</button>
			<button class="tab" data-tab="parfums">🍓 Parfums</button>
			<button class="tab" data-tab="themes">🎨 Thèmes</button>
			<button class="tab" data-tab="events">🎉 Events</button>
			<button class="tab" data-tab="prix">💰 Prix</button>

			<select id="select-rayon" class="form-select form-select--sm select--right">
				<option value="-1" {{ !$rayonId || $rayonId == '-1' ? 'selected' : '' }}>Tous les rayons</option>
				@foreach($rayons as $r)
					<option value="{{ $r->id_rayon }}" {{ (string)$rayonId === (string)$r->id_rayon ? 'selected' : '' }}>
						{{ $r->nom_rayon }}
					</option>
				@endforeach
			</select>
		</div>

		{{-- ══ PRODUITS ══ --}}
		<div id="tab-produits" class="tab-panel">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">
					Produits <span class="data-count">{{ count($produits) }}</span>
					@if($rayon)
						<span class="chip chip--gold">{{ $rayon->nom_rayon }}</span>
					@endif
				</h2>
				<div class="toolbar-actions">
					<input type="text" id="search-input" class="form-input form-input--sm search-input" placeholder="🔍 Rechercher…">

					{{-- Bouton export Excel --}}
					<a href="{{ route('produits.export', $rayon ? ['rayon' => $rayonId] : []) }}"
					class="btn btn--ghost btn--sm"
					title="Exporter tous les produits en Excel">
						⬇ Export Excel
					</a>

					@if($rayon)
						<form action="/gestion/rayon/{{ $rayon->id_rayon }}/live" method="POST" class="form-inline">
							@csrf @method('PATCH')
							<label class="toggle-label">
								<input type="checkbox" name="live_rayon" value="1" {{ $rayon->live_rayon ? 'checked' : '' }} onchange="this.form.submit()">
								<span class="toggle-text">Live rayon</span>
							</label>
						</form>
					@endif
				</div>
			</div>

			@if(count($produits) > 0)
				<div class="table-wrapper">
					<table>
						<thead>
							<tr>
								<th>#</th>
								<th>Article</th>
								<th>Forme</th>
								<th>Parfum</th>
								<th>Description</th>
								<th>Rayons</th>
								<th>Thème</th>
								<th>Stock</th>
								<th title="Nouveauté">🆕</th>
								<th title="À emporter">🛍️</th>
								<th title="Expédition">📦</th>
								<th title="Live" class="th-actions">Live</th>
								<th class="th-actions">Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach($produits as $produit)
							<tr data-nouveaute-since="{{ $produit->nouveaute && $produit->nouveaute_since ? $produit->nouveaute_since->toISOString() : '' }}">
								<td class="td-id">{{ $produit->id_produit }}</td>
								<td class="td-namecode">{{ $produit->nom_produit ?? '—' }}</td>
								<td><span class="chip">{{ $produit->forme->nom_forme ?? '—' }}</span></td>
								<td class="td-name">{{ $produit->parfum->nom_parfum ?? '—' }}</td>
								<td class="td-desc text-muted">{{ Str::limit($produit->description, 50) }}</td>
								<td>
									@forelse($produit->rayons as $r)
										<span class="chip">{{ $r->nom_rayon }}</span>
									@empty
										<span class="text-muted">—</span>
									@endforelse
								</td>
								<td>
									@if($produit->theme && $produit->theme->id_theme)
										<span class="chip chip--dynamic-color" style="background:{{ $produit->theme->couleur }}20; border-color:{{ $produit->theme->couleur }};">
											{{ $produit->theme->icone }} {{ $produit->theme->nom_theme }}
										</span>
									@else
										<span class="text-muted">—</span>
									@endif
								</td>
								<td>
									@if($produit->quantite > 0)
										<span class="stock-badge stock-badge--ok">{{ $produit->quantite }}</span>
									@else
										<span class="stock-badge stock-badge--rupture">Rupture</span>
									@endif
								</td>
								<td>
									<div class="actions-wrap">
										<form action="/gestion/produit/{{ $produit->id_produit }}/nouveaute" method="POST">
											@csrf @method('PATCH')
											<label><input type="checkbox" name="nouveaute" value="1" {{ $produit->nouveaute ? 'checked' : '' }}></label>
										</form>
									</div>
								</td>
								<td>
									<div class="actions-wrap">
										<form action="/gestion/produit/{{ $produit->id_produit }}/emporter" method="POST">
											@csrf @method('PATCH')
											<label><input type="checkbox" name="dispo_emporter" value="1" {{ $produit->dispo_emporter ? 'checked' : '' }}></label>
										</form>
									</div>
								</td>
								<td>
									<div class="actions-wrap">
										<form action="/gestion/produit/{{ $produit->id_produit }}/expedition" method="POST">
											@csrf @method('PATCH')
											<label><input type="checkbox" name="dispo_expedition" value="1" {{ $produit->dispo_expedition ? 'checked' : '' }}></label>
										</form>
									</div>
								</td>
								<td>
									<div class="actions-wrap">
										<form action="/gestion/produit/{{ $produit->id_produit }}/live" method="POST">
											@csrf @method('PATCH')
											<label><input type="checkbox" name="live" value="1" {{ $produit->live ? 'checked' : '' }}></label>
										</form>
									</div>
								</td>
								<td class="td-actions">
									<div class="actions-wrap">
										<button
											class="btn-icon btn-icon--edit"
											title="Modifier"
											onclick="ouvrirModalModifierProduit(
												{{ $produit->id_produit }},
												'{{ addslashes($produit->nom_produit ?? '') }}',
												{{ $produit->id_parfum ?? 'null' }},
												{{ $produit->id_forme ?? 'null' }},
												{{ $produit->id_theme ?? 'null' }},
												{{ $produit->quantite ?? 0 }},
												'{{ addslashes($produit->description ?? '') }}'
											)"
										>✏️</button>
									</div>
									<div class="actions-wrap">
										<form action="{{ route('produit.destroy', $produit->id_produit) }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('ce produit')">
											@csrf @method('DELETE')
											<button type="submit" class="btn-icon btn-icon--delete" title="Supprimer">🗑️</button>
										</form>
									</div>
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

		{{-- ══ IMAGES ══ --}}
		<div id="tab-images" class="tab-panel hidden">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">
					Images <span class="data-count">{{ $images->total() }}</span>
				</h2>
				<div class="toolbar-actions">
					<input type="text" id="search-input-images" class="form-input form-input--sm search-input" placeholder="🔍 Rechercher…">
					<button class="btn btn--primary btn--sm" id="btn-nvimage">📤 Ajouter une image</button>
				</div>
			</div>

			@if($images->count() > 0)
				{{-- Vue grille --}}
				<div class="images-grid" id="images-grid">
					@foreach($images as $image)
					<div class="image-card" data-search="{{ strtolower(($image->produit->parfum->nom_parfum ?? '') . ' ' . ($image->formeCondi->forme->nom_forme ?? '') . ' ' . ($image->formeCondi->conditionnement->type ?? '')) }}">
						<div class="image-card__preview">
							<img
								src="{{ asset($image->url) }}"
								alt="{{ $image->produit->nom_produit ?? 'Image produit' }}"
								loading="lazy"
								decoding="async"
								fetchpriority="low"
								onerror="this.style.display='none';this.nextElementSibling.style.display='none';this.closest('.image-card__preview').querySelector('.image-error-placeholder').style.display='flex';"
							>
							<div class="image-error-placeholder" style="display:none;">
								<span>⚠️</span>
								<span>Image introuvable</span>
							</div>
						</div>
						<div class="image-card__body">
							<p class="image-card__name">{{ $image->produit->parfum->nom_parfum ?? '— Produit #'.$image->id_produit }}</p>
							@if($image->formeCondi)
								<div class="image-card__meta">
									<span class="chip">{{ $image->formeCondi->forme->nom_forme ?? '—' }}</span>
									<span class="chip">{{ $image->formeCondi->conditionnement->type ?? '—' }}</span>
								</div>
							@endif
							<div class="image-card__actions">
								<button
									class="btn-icon btn-icon--edit"
									title="Remplacer l'image (conserve le même chemin)"
									onclick="ouvrirModalRemplaceImage(
										{{ $image->id_image }},
										'{{ asset($image->url) }}',
										'{{ route('image.remplacer', $image->id_image) }}',
										'{{ addslashes($image->produit->nom_produit ?? 'Image #'.$image->id_image) }}'
									)"
								>🔄</button>
								<form
									action="{{ route('image.destroy', $image->id_image) }}"
									method="POST"
									class="form-delete-image"
									style="display:inline"
									onsubmit="return confirmSuppr('cette image')"
								>
									@csrf @method('DELETE')
									<button type="submit" class="btn-icon btn-icon--delete" title="Supprimer">🗑️</button>
								</form>
							</div>
						</div>
					</div>
					@endforeach
				</div>

				{{-- Pagination --}}
				@if($images->hasPages())
					<div class="pagination-images">
						<span class="pagination-info">
							{{ $images->firstItem() }}–{{ $images->lastItem() }} sur {{ $images->total() }} images
						</span>
						<div class="pagination-btns">
							@if($images->onFirstPage())
								<button class="btn-pagination" disabled>← Précédent</button>
							@else
								<a href="{{ $images->previousPageUrl() }}&tab=images{{ request('rayon') ? '&rayon='.request('rayon') : '' }}" class="btn-pagination">← Précédent</a>
							@endif

							@foreach($images->getUrlRange(1, $images->lastPage()) as $page => $url)
								@if($page == $images->currentPage())
									<button class="btn-pagination btn-pagination--active" disabled>{{ $page }}</button>
								@else
									<a href="{{ $url }}&tab=images{{ request('rayon') ? '&rayon='.request('rayon') : '' }}" class="btn-pagination">{{ $page }}</a>
								@endif
							@endforeach

							@if($images->hasMorePages())
								<a href="{{ $images->nextPageUrl() }}&tab=images{{ request('rayon') ? '&rayon='.request('rayon') : '' }}" class="btn-pagination">Suivant →</a>
							@else
								<button class="btn-pagination" disabled>Suivant →</button>
							@endif
						</div>
					</div>
				@endif

			@else
				<div class="data-empty">
					<p class="data-empty__icon">🖼️</p>
					<p class="data-empty__text">Aucune image enregistrée.</p>
					<button class="btn btn--primary btn--sm" onclick="document.getElementById('modal-image').classList.remove('hidden')">
						📤 Ajouter une première image
					</button>
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
							<tr><th>#</th><th>Nom</th><th>Events</th><th>Stock</th><th class="th-actions">Actions</th></tr>
						</thead>
						<tbody>
							@foreach($rayons as $r)
							<tr>
								<td class="td-id">{{ $r->id_rayon }}</td>
								<td class="td-name">{{ $r->nom_rayon }}</td>
								<td>
									@forelse($r->events as $event)
										<span class="chip chip--dynamic-color" style="background:{{ $event->couleur }}20; border-color:{{ $event->couleur }};">
											{{ $event->icone }} {{ $event->nom_event }}
										</span>
									@empty
										<span class="text-muted">—</span>
									@endforelse
								</td>
								<td><span class="stock-badge stock-badge--ok">{{ $r->stock_total_rayon }}</span></td>
								<td class="td-actions">
									<button class="btn-icon btn-icon--edit" title="Modifier les events"
										onclick="ouvrirModalEditRayon({{ $r->id_rayon }}, '{{ $r->nom_rayon }}', {{ json_encode($r->events->pluck('id_event')) }})">✏️</button>
									<form action="{{ route('rayon.destroy', $r->id_rayon) }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('ce rayon')">
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
							<tr><th>#</th><th>Thème</th><th>Icône</th><th>Couleur</th><th>Produits</th><th class="th-actions">Actions</th></tr>
						</thead>
						<tbody>
							@foreach($themes as $theme)
							<tr>
								<td class="td-id">{{ $theme->id_theme }}</td>
								<td class="td-name">{{ $theme->nom_theme }}</td>
								<td class="theme-icon">{{ $theme->icone }}</td>
								<td>
									<span class="color-swatch">
										<span class="color-swatch__circle" style="background:{{ $theme->couleur }};"></span>
										{{ $theme->couleur }}
									</span>
								</td>
								<td><span class="data-count">{{ $produitsByTheme[$theme->id_theme] ?? 0 }}</span></td>
								<td class="td-actions">
									<form action="{{ route('theme.destroy', $theme->id_theme) }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('ce thème')">
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

		{{-- ══ EVENTS ══ --}}
		<div id="tab-events" class="tab-panel hidden">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">Events <span class="data-count">{{ count($events) }}</span></h2>
				<button class="btn btn--primary btn--sm" id="btn-nvevent2">+ Nouvel event</button>
			</div>
			@if(count($events) > 0)
				<div class="table-wrapper">
					<table>
						<thead>
							<tr><th>#</th><th>Event</th><th>Icône</th><th>Couleur</th><th>Rayons liés</th><th>Produits éligibles</th><th class="th-actions">Actions</th></tr>
						</thead>
						<tbody>
							@foreach($events as $event)
							<tr>
								<td class="td-id">{{ $event->id_event }}</td>
								<td class="td-name">{{ $event->nom_event }}</td>
								<td class="theme-icon">{{ $event->icone }}</td>
								<td>
									<span class="color-swatch">
										<span class="color-swatch__circle" style="background:{{ $event->couleur }};"></span>
										{{ $event->couleur }}
									</span>
								</td>
								<td><span class="data-count">{{ $event->rayons->count() }}</span></td>
								<td><span class="data-count">{{ $produitsByEvent[$event->id_event] ?? 0 }}</span></td>
								<td class="td-actions">
									<form action="{{ route('event.destroy', $event->id_event) }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('cet event')">
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
					<p class="data-empty__icon">🎉</p>
					<p class="data-empty__text">Aucun event enregistré.</p>
				</div>
			@endif
		</div>

		{{-- ══ FORMES ══ --}}
		<div id="tab-formes" class="tab-panel hidden">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">Formes <span class="data-count">{{ count($formes) }}</span></h2>
				<button class="btn btn--primary btn--sm" id="btn-nvforme">+ Nouvelle forme</button>
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
									<form action="{{ route('forme.destroy', $forme->id_forme) }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('cette forme')">
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
				<button class="btn btn--primary btn--sm" id="btn-nvcondi">+ Nouveau conditionnement</button>
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
									<form action="{{ route('conditionnement.destroy', $cond->id_condi) }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('ce conditionnement')">
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
				<button class="btn btn--primary btn--sm" id="btn-nvprix">+ Nouveau prix</button>
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
									<form action="{{ route('formecondi.destroy', $fc->id_forme_condi) }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('ce prix')">
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

		{{-- ══ PARFUMS ══ --}}
		<div id="tab-parfums" class="tab-panel hidden">
			<div class="data-toolbar">
				<h2 class="data-toolbar__title">
					Parfums <span class="data-count">{{ count($parfums) }}</span>
					<input type="text" id="search-input-parfums" class="form-input form-input--sm search-input" placeholder="🔍 Rechercher…">
				</h2>
				<button class="btn btn--primary btn--sm" id="btn-nvparfum">+ Nouveau parfum</button>
			</div>
			@if(count($parfums) > 0)
				<div class="table-wrapper">
					<table>
						<thead><tr><th>#</th><th>Nom</th><th>Actions</th></tr></thead>
						<tbody>
							@foreach($parfums as $parfum)
							<tr>
								<td class="td-id">{{ $parfum->id_parfum }}</td>
								<td class="td-name">{{ $parfum->nom_parfum }}</td>
								<td class="td-actions">
									<form action="{{ route('parfum.destroy', $parfum->id_parfum) }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('ce parfum')">
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
				<div class="data-empty"><p class="data-empty__icon">🍓</p><p class="data-empty__text">Aucun parfum.</p></div>
			@endif
		</div>

	</div>{{-- /gestion-right-panel --}}
</div>

{{-- ══ MODAL PRODUIT — Création ══ --}}
<div id="modal-produit" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Nouveau produit</h2>
		<form action="/gestion/produit" method="POST">
			@csrf
			<input type="hidden" name="id_boutique" value="{{ $boutique->id_boutique }}">

			<label>Nom du produit</label>
			<input type="text" name="nom_produit" placeholder="Ex : Meringue Framboise Mini…" required>

			<label>Parfum</label>
			<select name="id_parfum" required>
				<option value="">— Choisir un parfum —</option>
				@foreach($parfums as $p)
					<option value="{{ $p->id_parfum }}">{{ $p->nom_parfum }}</option>
				@endforeach
			</select>

			<label>Forme</label>
			<select name="id_forme" required>
				<option value="">— Choisir une forme —</option>
				@foreach($formes as $f)
					<option value="{{ $f->id_forme }}">{{ $f->nom_forme }}</option>
				@endforeach
			</select>

			<label>Rayon</label>
			<select name="id_rayon" required>
				<option value="">— Choisir un rayon —</option>
				@foreach($rayons as $r)
					<option value="{{ $r->id_rayon }}">{{ $r->nom_rayon }}</option>
				@endforeach
			</select>

			<label>Thème <span class="label-hint">(optionnel)</span></label>
			<select name="id_theme">
				<option value="">— Aucun thème —</option>
				@foreach($themes as $t)
					<option value="{{ $t->id_theme }}">{{ $t->icone }} {{ $t->nom_theme }}</option>
				@endforeach
			</select>

			<label>Stock initial</label>
			<input type="number" name="quantite" min="0" value="0" required>

			<label>Description <span class="label-hint">(optionnel)</span></label>
			<textarea name="description" rows="3" placeholder="Description du produit…"></textarea>

			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">Créer le produit</button>
			</div>
		</form>
	</div>
</div>

{{-- ══ MODAL PRODUIT — Modification ══ --}}
<div id="modal-modifier-produit" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Modifier le produit</h2>
		<form id="form-modifier-produit" method="POST">
			@csrf @method('PUT')

			<label>Nom du produit</label>
			<input type="text" id="edit-nom_produit" name="nom_produit" required>

			<label>Parfum</label>
			<select id="edit-id_parfum" name="id_parfum" required>
				<option value="">— Choisir un parfum —</option>
				@foreach($parfums as $p)
					<option value="{{ $p->id_parfum }}">{{ $p->nom_parfum }}</option>
				@endforeach
			</select>

			<label>Forme</label>
			<select id="edit-id_forme" name="id_forme" required>
				<option value="">— Choisir une forme —</option>
				@foreach($formes as $f)
					<option value="{{ $f->id_forme }}">{{ $f->nom_forme }}</option>
				@endforeach
			</select>

			<label>Thème <span class="label-hint">(optionnel)</span></label>
			<select id="edit-id_theme" name="id_theme">
				<option value="">— Aucun thème —</option>
				@foreach($themes as $t)
					<option value="{{ $t->id_theme }}">{{ $t->icone }} {{ $t->nom_theme }}</option>
				@endforeach
			</select>

			<label>Stock</label>
			<input type="number" id="edit-quantite" name="quantite" min="0" required>

			<label>Description</label>
			<textarea id="edit-description" name="description" rows="3"></textarea>

			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">💾 Enregistrer</button>
			</div>
		</form>
	</div>
</div>

{{-- ══ MODAL IMAGE — Ajout ══ --}}
<div id="modal-image" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Ajouter une image</h2>
		<form action="{{ route('image.store') }}" method="POST" enctype="multipart/form-data">
			@csrf
			<label>Produit</label>
			<select name="id_produit" required>
				<option value="">— Choisir un produit —</option>
				@foreach($produits as $p)
					<option value="{{ $p->id_produit }}">{{ $p->nom_produit ?? '#'.$p->id_produit }}</option>
				@endforeach
			</select>

			<label>Forme / Conditionnement</label>
			<select name="id_forme_condi" required>
				<option value="">— Choisir —</option>
				@foreach($forme_condi as $fc)
					<option value="{{ $fc->id_forme_condi }}">
						{{ $fc->forme->nom_forme ?? '?' }} — {{ $fc->conditionnement->type ?? '?' }}
					</option>
				@endforeach
			</select>

			<label>Fichier image <span class="label-hint">(.jpg, .jpeg, .png, .webp, .gif — max 5 Mo)</span></label>
			<input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.gif" required>

			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">↑ Envoyer</button>
			</div>
		</form>
	</div>
</div>

{{-- ══ MODAL IMAGE — Remplacement ══ --}}
<div id="modal-remplace-image" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Remplacer l'image</h2>
		<p id="remplace-image-nom" class="text-muted" style="margin-bottom:var(--space-sm);"></p>
		<img
			id="remplace-image-preview"
			src=""
			alt="Aperçu"
			style="max-width:100%;max-height:160px;object-fit:contain;border-radius:var(--radius-md);margin-bottom:var(--space-md);display:block;"
		>
		<form id="form-remplace-image" method="POST" enctype="multipart/form-data">
			@csrf
			<label>Nouveau fichier <span class="label-hint">(conserve le même chemin sur le serveur)</span></label>
			<input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.gif" required>
			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">🔄 Remplacer</button>
			</div>
		</form>
	</div>
</div>

<div id="modal-rayon" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Nouveau rayon</h2>
		<form action="{{ route('nvrayon') }}" method="POST">
			@csrf
			<input type="hidden" name="id_boutique" value="{{ $boutique->id_boutique }}">
			<label>Nom du rayon</label>
			<input type="text" name="nom_rayon" placeholder="Ex : Noël, Printemps…" required>
			<label>Events associés <span class="label-hint">(optionnel)</span></label>
			<div class="themes-selector">
				@foreach($events as $event)
					<label class="themes-selector-item" id="event-label-{{ $event->id_event }}">
						<input type="checkbox" name="id_events[]" value="{{ $event->id_event }}" data-couleur="{{ $event->couleur }}" class="event-checkbox">
						{{ $event->icone }} {{ $event->nom_event }}
					</label>
				@endforeach
			</div>
			<div id="preview-produits" class="preview-produits" style="display:none;">
				🔍 <span id="preview-text">0 produit(s) seront liés à ce rayon</span>
			</div>
			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">Créer le rayon</button>
			</div>
		</form>
	</div>
</div>

<div id="modal-theme" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Nouveau thème</h2>
		<form action="{{ route('nvtheme') }}" method="POST">
			@csrf
			<label>Nom du thème</label>
			<input type="text" name="nom_theme" placeholder="Ex : Fleurs, Fruits…" required>
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

<div id="modal-event" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Nouvel event</h2>
		<form action="{{ route('nvevent') }}" method="POST">
			@csrf
			<label>Nom de l'event</label>
			<input type="text" name="nom_event" placeholder="Ex : Noël, Printemps, Anniversaire…" required>
			<label>Icône (emoji)</label>
			<input type="text" name="icone" placeholder="Ex : 🎄" maxlength="4" value="🎉">
			<label>Couleur</label>
			<input type="color" name="couleur" value="#C0392B" style="height:42px; padding:4px 8px;">
			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">Créer l'event</button>
			</div>
		</form>
	</div>
</div>

<div id="modal-forme" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Nouvelle forme</h2>
		<form action="{{ route('nvforme') }}" method="POST">
			@csrf
			<label>Nom de la forme</label>
			<input type="text" name="nom_forme" placeholder="Ex : Mini, Nid, Géant…" required>
			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">Créer</button>
			</div>
		</form>
	</div>
</div>

<div id="modal-condi" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Nouveau conditionnement</h2>
		<form action="{{ route('nvconditionnement') }}" method="POST">
			@csrf
			<label>Type de conditionnement</label>
			<input type="text" name="type" placeholder="Ex : sachet_de_4, boite_de_8…" required>
			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">Créer</button>
			</div>
		</form>
	</div>
</div>

<div id="modal-prix" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Nouveau prix</h2>
		<form action="{{ route('nvformecondi') }}" method="POST">
			@csrf
			<label>Forme</label>
			<select name="id_forme" required>
				@foreach($formes as $f)
					<option value="{{ $f->id_forme }}">{{ $f->nom_forme }}</option>
				@endforeach
			</select>
			<label>Conditionnement</label>
			<select name="id_condi" required>
				@foreach($conditionnements as $c)
					<option value="{{ $c->id_condi }}">{{ $c->type }}</option>
				@endforeach
			</select>
			<label>Prix (€)</label>
			<input type="number" name="prix" step="0.01" min="0" placeholder="Ex : 6.50" required>
			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">Créer</button>
			</div>
		</form>
	</div>
</div>

<div id="modal-parfum" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>Nouveau parfum</h2>
		<form action="{{ route('nvparfum') }}" method="POST">
			@csrf
			<label>Nom du parfum</label>
			<input type="text" name="nom_parfum" placeholder="Ex : Framboise, Pistache…" required>
			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">Créer</button>
			</div>
		</form>
	</div>
</div>

<div id="modal-edit-rayon" class="data-modal hidden">
	<div class="data-modal-content">
		<h2 id="edit-rayon-titre">Modifier le rayon</h2>
		<form id="form-edit-rayon" method="POST">
			@csrf @method('PUT')
			<label>Events associés <span class="label-hint">(les produits seront automatiquement mis à jour)</span></label>
			<div class="themes-selector" id="edit-events-selector">
				@foreach($events as $event)
					<label class="themes-selector-item edit-event-item" data-id="{{ $event->id_event }}" data-couleur="{{ $event->couleur }}">
						<input type="checkbox" name="id_events[]" value="{{ $event->id_event }}" data-couleur="{{ $event->couleur }}" class="edit-event-checkbox">
						{{ $event->icone }} {{ $event->nom_event }}
					</label>
				@endforeach
			</div>
			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary">Enregistrer</button>
			</div>
		</form>
	</div>
</div>


<div id="toast" class="toast hidden"></div>

<div id="loading-overlay">
	<div class="loading-box">
		<div class="spinner"></div>
		<p>Traitement en cours…</p>
	</div>
</div>

<script>
// ════════════════════════════════════════
// IMPORT FICHIER — Drag & Drop
// ════════════════════════════════════════
const inputFichier = document.getElementById('file-input');
const badge        = document.getElementById('file-name-badge');
const badgeText    = document.getElementById('file-name-text');
const removeBtn    = document.getElementById('remove-file');

inputFichier.addEventListener('change', () => {
	if (inputFichier.files.length > 0) {
		badgeText.textContent = inputFichier.files[0].name;
		badge.classList.add('visible');
	}
});
removeBtn.addEventListener('click', () => {
	inputFichier.value = '';
	badge.classList.remove('visible');
});

const dropZone = document.getElementById('drop-zone');
dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
dropZone.addEventListener('drop', e => {
	e.preventDefault();
	dropZone.classList.remove('drag-over');
	if (e.dataTransfer.files.length > 0) {
		inputFichier.files = e.dataTransfer.files;
		badgeText.textContent = e.dataTransfer.files[0].name;
		badge.classList.add('visible');
	}
});

// ════════════════════════════════════════
// ONGLETS
// ════════════════════════════════════════
function activerOnglet(tabName) {
	document.querySelectorAll('.tabs .tab').forEach(t => t.classList.remove('active'));
	document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
	document.querySelectorAll('.quick-nav__item').forEach(i => i.classList.remove('active'));

	const tabBtn = document.querySelector(`.tabs .tab[data-tab="${tabName}"]`);
	const panel  = document.getElementById('tab-' + tabName);
	const navBtn = document.querySelector(`.quick-nav__item[data-target="${tabName}"]`);

	if (tabBtn) tabBtn.classList.add('active');
	if (panel)  panel.classList.remove('hidden');
	if (navBtn) navBtn.classList.add('active');
}

document.querySelectorAll('.tabs .tab').forEach(btn => {
	btn.addEventListener('click', () => activerOnglet(btn.dataset.tab));
});
document.querySelectorAll('.quick-nav__item').forEach(btn => {
	btn.addEventListener('click', () => activerOnglet(btn.dataset.target));
});

// Lire le paramètre tab dans l'URL et activer le bon onglet
const tabParam = new URLSearchParams(window.location.search).get('tab');
if (tabParam) activerOnglet(tabParam);

// ════════════════════════════════════════
// FLASH MESSAGE (toast au retour de redirect)
// ════════════════════════════════════════
@if(session('success'))
	window.addEventListener('DOMContentLoaded', () => afficherToast('{{ session('success') }}', 'success'));
@endif
@if(session('error'))
	window.addEventListener('DOMContentLoaded', () => afficherToast('{{ session('error') }}', 'error'));
@endif

// ════════════════════════════════════════
// CONFIRMATION SUPPRESSION
// ════════════════════════════════════════
function confirmSuppr(label) {
	return confirm('Voulez-vous vraiment supprimer ' + label + ' ?');
}

// ════════════════════════════════════════
// OUVERTURE MODALS (boutons fixes)
// ════════════════════════════════════════
const modals = {
	'btn-nvproduit': 'modal-produit',
	'btn-nvrayon':   'modal-rayon',
	'btn-nvrayon2':  'modal-rayon',
	'btn-nvevent':   'modal-event',
	'btn-nvevent2':  'modal-event',
	'btn-nvtheme2':  'modal-theme',
	'btn-nvforme':   'modal-forme',
	'btn-nvcondi':   'modal-condi',
	'btn-nvprix':    'modal-prix',
	'btn-nvparfum':  'modal-parfum',
	'btn-nvimage':   'modal-image',
};
Object.entries(modals).forEach(([btnId, modalId]) => {
	const btn = document.getElementById(btnId);
	if (btn) btn.addEventListener('click', () => document.getElementById(modalId).classList.remove('hidden'));
});

// ════════════════════════════════════════
// FERMETURE MODALS
// ════════════════════════════════════════
document.addEventListener('click', e => {
	if (e.target.classList.contains('data-modal') || e.target.classList.contains('btn-close-modal')) {
		document.querySelectorAll('.data-modal').forEach(m => m.classList.add('hidden'));
	}
});
document.addEventListener('keydown', e => {
	if (e.key === 'Escape') {
		document.querySelectorAll('.data-modal').forEach(m => m.classList.add('hidden'));
	}
});

// ════════════════════════════════════════
// MODAL REMPLACER IMAGE
// ════════════════════════════════════════
function ouvrirModalRemplaceImage(id, urlPreview, actionUrl, nomProduit) {
	document.getElementById('remplace-image-preview').src = urlPreview;
	document.getElementById('remplace-image-nom').textContent = nomProduit;
	document.getElementById('form-remplace-image').action = actionUrl;
	document.getElementById('modal-remplace-image').classList.remove('hidden');
}

// ════════════════════════════════════════
// RECHERCHE IMAGES
// ════════════════════════════════════════
const searchImages = document.getElementById('search-input-images');
if (searchImages) {
	searchImages.addEventListener('input', function () {
		const q = this.value.toLowerCase();
		document.querySelectorAll('#images-grid .image-card').forEach(card => {
			const txt = (card.dataset.search || '').toLowerCase();
			card.style.display = txt.includes(q) ? '' : 'none';
		});
	});
}

// ════════════════════════════════════════
// OVERLAY IMPORT
// ════════════════════════════════════════
document.getElementById('import-form').addEventListener('submit', () => {
	document.getElementById('loading-overlay').classList.add('visible');
});

const submitBtn = document.getElementById('import-form').querySelector('button[type="submit"]');
function updateSubmitState() {
	submitBtn.disabled = !(inputFichier.files.length > 0);
}
inputFichier.addEventListener('change', updateSubmitState);
removeBtn.addEventListener('click', () => setTimeout(updateSubmitState, 10));
updateSubmitState();

// ════════════════════════════════════════
// FILTRE RAYON
// ════════════════════════════════════════
document.getElementById('select-rayon').addEventListener('change', function () {
	const url = new URL(window.location.href);
	this.value === '-1' ? url.searchParams.delete('rayon') : url.searchParams.set('rayon', this.value);
	window.location.href = url.toString();
});

// ════════════════════════════════════════
// CHECKBOXES EVENTS (modal rayon)
// ════════════════════════════════════════
const produitsByEvent = @json($produitsByEvent);

document.querySelectorAll('.event-checkbox').forEach(checkbox => {
	checkbox.addEventListener('change', function () {
		styleEventLabel(this.closest('label'), this.checked, this.dataset.couleur);
		updatePreview();
	});
});

function styleEventLabel(label, checked, couleur) {
	if (checked) {
		label.style.borderColor = couleur;
		label.style.background  = couleur + '20';
		label.style.color       = couleur;
	} else {
		label.style.borderColor = 'var(--color-border)';
		label.style.background  = 'var(--color-cream)';
		label.style.color       = 'inherit';
	}
}

function updatePreview() {
	const checked = [...document.querySelectorAll('.event-checkbox:checked')].map(cb => parseInt(cb.value));
	const preview = document.getElementById('preview-produits');
	const text    = document.getElementById('preview-text');
	if (checked.length === 0) { preview.style.display = 'none'; return; }
	let total = 0;
	checked.forEach(id => { total += produitsByEvent[id] || 0; });
	preview.style.display = 'block';
	text.textContent = `${total} produit(s) éligible(s) seront automatiquement liés à ce nouveau rayon`;
}

// ════════════════════════════════════════
// RECHERCHE PRODUITS
// ════════════════════════════════════════
document.getElementById('search-input').addEventListener('input', function () {
	const query = this.value.toLowerCase();
	document.querySelectorAll('#tab-produits tbody tr').forEach(row => {
		const namecode = row.querySelector('.td-namecode')?.textContent.toLowerCase() || '';
		const name     = row.querySelector('.td-name')?.textContent.toLowerCase() || '';
		const id       = row.querySelector('.td-id')?.textContent.toLowerCase() || '';
		row.style.display = (namecode.includes(query) || name.includes(query) || id.includes(query)) ? '' : 'none';
	});
});

document.getElementById('search-input-parfums').addEventListener('input', function () {
	const query = this.value.toLowerCase();
	document.querySelectorAll('#tab-parfums tbody tr').forEach(row => {
		const name = row.querySelector('.td-name')?.textContent.toLowerCase() || '';
		row.style.display = name.includes(query) ? '' : 'none';
	});
});

// ════════════════════════════════════════
// MODAL MODIFIER RAYON
// ════════════════════════════════════════
function ouvrirModalEditRayon(id, nom, eventIds) {
	document.getElementById('edit-rayon-titre').textContent = 'Modifier : ' + nom;
	document.getElementById('form-edit-rayon').action = '/gestion/rayon/' + id;

	document.querySelectorAll('.edit-event-checkbox').forEach(cb => {
		const checked = eventIds.includes(parseInt(cb.value));
		cb.checked = checked;
		styleEventLabel(cb.closest('label'), checked, cb.dataset.couleur);
	});

	document.getElementById('modal-edit-rayon').classList.remove('hidden');
}

document.querySelectorAll('.edit-event-checkbox').forEach(checkbox => {
	checkbox.addEventListener('change', function () {
		styleEventLabel(this.closest('label'), this.checked, this.dataset.couleur);
	});
});

// ════════════════════════════════════════
// TOGGLES AJAX
// ════════════════════════════════════════
document.querySelectorAll('input[name="live"], input[name="dispo_expedition"], input[name="dispo_emporter"], input[name="nouveaute"]').forEach(checkbox => {
	checkbox.addEventListener('change', function () {
		const form    = this.closest('form');
		const url     = form.action;
		const checked = this.checked;
		const name    = this.name;

		const body = new URLSearchParams();
		body.append('_token', '{{ csrf_token() }}');
		body.append('_method', 'PATCH');
		body.append(name, checked ? '1' : '0');

		fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: body.toString() })
		.then(res => {
			if (!res.ok) { this.checked = !checked; afficherToast('Erreur lors de la mise à jour', 'error'); }
			else { afficherToast('Mise à jour effectuée ✓', 'success'); }
		})
		.catch(() => { this.checked = !checked; afficherToast('Erreur réseau', 'error'); });
	});
});

// ════════════════════════════════════════
// PAGINATION TABLEAUX
// ════════════════════════════════════════
document.querySelectorAll('.table-wrapper').forEach(wrapper => {
	const table = wrapper.querySelector('table');
	const tbody = table.querySelector('tbody');
	const rowsPerPage = 10;
	let currentPage = 1;

	const rows = Array.from(tbody.querySelectorAll('tr'))
		.sort((a, b) => {
			const idA = parseInt(a.querySelector('.td-id')?.textContent) || 0;
			const idB = parseInt(b.querySelector('.td-id')?.textContent) || 0;
			return idA - idB;
		});

	// Réordonner physiquement les lignes dans le DOM
	rows.forEach(row => tbody.appendChild(row));

	const totalPages = Math.ceil(rows.length / rowsPerPage);
	if (totalPages <= 1) return;

	function renderTable() {
		rows.forEach((row, index) => {
			row.style.display = (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) ? '' : 'none';
		});
		pageInfo.textContent = `Page ${currentPage} / ${totalPages}`;
		prevBtn.disabled = currentPage === 1;
		nextBtn.disabled = currentPage === totalPages;
	}

	const paginationControls = document.createElement('div');
	paginationControls.className = 'pagination-controls';
	const prevBtn = document.createElement('button');
	prevBtn.textContent = '← Précédent';
	prevBtn.className = 'btn-pagination';
	const nextBtn = document.createElement('button');
	nextBtn.textContent = 'Suivant →';
	nextBtn.className = 'btn-pagination';
	const pageInfo = document.createElement('span');
	paginationControls.append(prevBtn, pageInfo, nextBtn);
	wrapper.appendChild(paginationControls);

	prevBtn.addEventListener('click', () => { if (currentPage > 1)         { currentPage--; renderTable(); } });
	nextBtn.addEventListener('click', () => { if (currentPage < totalPages){ currentPage++; renderTable(); } });
	renderTable();
});

// ════════════════════════════════════════
// TOAST NOTIFICATION
// ════════════════════════════════════════
function afficherToast(message, type = 'success') {
	const toast = document.getElementById('toast');
	toast.textContent = message;
	toast.className   = `toast toast--${type}`;
	toast.classList.remove('hidden');
	clearTimeout(toast._timer);
	toast._timer = setTimeout(() => toast.classList.add('hidden'), 2800);
}

// ════════════════════════════════════════
// MODAL MODIFIER PRODUIT
// ════════════════════════════════════════
function ouvrirModalModifierProduit(id, nom, idParfum, idForme, idTheme, quantite, description) {
	document.getElementById('form-modifier-produit').action = '/gestion/produit/' + id;
	document.getElementById('edit-nom_produit').value      = nom;
	document.getElementById('edit-quantite').value         = quantite;
	document.getElementById('edit-description').value      = description;

	const selParfum = document.getElementById('edit-id_parfum');
	const selForme  = document.getElementById('edit-id_forme');
	const selTheme  = document.getElementById('edit-id_theme');

	selParfum.value = idParfum ?? '';
	selForme.value  = idForme  ?? '';
	selTheme.value  = idTheme  ?? '';

	document.getElementById('modal-modifier-produit').classList.remove('hidden');
}
</script>
