@include('templet.header', [
	'titre' => 'Gestion des Données',
	'note'  => 'Importer et gérer vos données',
	'title' => 'Gestion'
])
@vite('resources/css/gestion.css')

<main class="container section">

<div class="container gestion-layout">

	{{-- ══ COLONNE GAUCHE ══ --}}
	<div class="gestion-left-panel">

		<div class="grid grid-1 panel-grid mb-2xl">

			{{-- Statistiques --}}
			<div class="card">
				<div class="card__body">
					<p class="card__tag card__tag--wrap">
						Infos {{ $nom_boutique ?? '' }}
					</p>
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
							<span class="stat-item__value">{{ count($themes) }}</span>
							<span class="stat-item__label">Thèmes</span>
						</div>
						<div class="stat-item">
							<span class="stat-item__value">{{ count($images) }}</span>
							<span class="stat-item__label">Images</span>
						</div>
						<div class="stat-item">
							<span class="stat-item__value">{{ count($events) }}</span>
							<span class="stat-item__label">Events</span>
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
					<div class="flex gap-sm flex-spacer">
						<button class="btn btn--ghost btn--sm btn--flex-shrink" id="btn-nvrayon" title="Nouveau Rayon">
							➕ Rayon
						</button>
						<button class="btn btn--ghost btn--sm btn--flex-shrink" id="btn-nvevent" title="Nouvel Event">
							🎉 Event
						</button>
					</div>
				</div>
			</div>

		</div>

		{{-- Navigation rapide --}}
		<div class="quick-nav card">
			<div class="card__body">
				<p class="card__tag">Navigation rapide</p>
				<nav class="quick-nav__list mt-md">
					<button class="quick-nav__item active" data-target="produits">🍬 Produits <span class="quick-nav__count">{{ count($produits) }}</span></button>
					<button class="quick-nav__item" data-target="images">🖼️ Images <span class="quick-nav__count">{{ count($images) }}</span></button>
					<button class="quick-nav__item" data-target="rayons">🗂 Rayons <span class="quick-nav__count">{{ count($rayons) }}</span></button>
					<button class="quick-nav__item" data-target="themes">🎨 Thèmes <span class="quick-nav__count">{{ count($themes) }}</span></button>
					<button class="quick-nav__item" data-target="events">🎉 Events <span class="quick-nav__count">{{ count($events) }}</span></button>
					<button class="quick-nav__item" data-target="formes">🔷 Formes <span class="quick-nav__count">{{ count($formes) }}</span></button>
					<button class="quick-nav__item" data-target="conditionnements">📦 Conditionnements <span class="quick-nav__count">{{ count($conditionnements) }}</span></button>
					<button class="quick-nav__item" data-target="parfums">🍓 Parfums <span class="quick-nav__count">{{ count($parfums) }}</span></button>
					<button class="quick-nav__item" data-target="prix">💰 Prix <span class="quick-nav__count">{{ count($forme_condi) }}</span></button>
				</nav>
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
				@foreach($rayons as $rayon)
					<option value="{{ $rayon->id_rayon }}" {{ (string)$rayonId === (string)$rayon->id_rayon ? 'selected' : '' }}>
						{{ $rayon->nom_rayon }}
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
							<span class="chip chip--gold">
								@foreach($rayonActif->events as $e){{ $e->icone }} @endforeach {{ $rayonActif->nom_rayon }}
							</span>
						@endif
					@endif
				</h2>
				<div class="toolbar-actions">
					<input type="text" id="search-input" class="form-input form-input--sm search-input" placeholder="🔍 Rechercher…">
					@if($rayonId && $rayonId != '-1')
						@php $rayonActif = $rayons->firstWhere('id_rayon', $rayonId); @endphp
						@if($rayonActif)
							<form action="/gestion/rayon/{{ $rayonActif->id_rayon }}/live" method="POST" class="form-inline">
								@csrf @method('PATCH')
								<label class="toggle-label">
									<input type="checkbox" name="live_rayon" value="1" {{ $rayonActif->live_rayon ? 'checked' : '' }} onchange="this.form.submit()">
									<span class="toggle-text">Live rayon</span>
								</label>
							</form>
						@endif
					@endif
					<button class="btn btn--primary btn--sm" id="btn-nvproduit" disabled>+ Nouveau produit</button>
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
								<td>
									<span class="chip">{{ $produit->forme->nom_forme ?? '—' }}</span>
								</td>
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
					Images <span class="data-count">{{ count($images) }}</span>
				</h2>
				<div class="toolbar-actions">
					<input type="text" id="search-input-images" class="form-input form-input--sm search-input" placeholder="🔍 Rechercher…">
					<button class="btn btn--primary btn--sm" id="btn-nvimage">📤 Ajouter une image</button>
				</div>
			</div>

			@if(count($images) > 0)
				{{-- Vue grille --}}
				<div class="images-grid" id="images-grid">
					@foreach($images as $image)
					<div class="image-card" data-search="{{ strtolower(($image->produit->nom_produit ?? '') . ' ' . ($image->formeCondi->forme->nom_forme ?? '') . ' ' . ($image->formeCondi->conditionnement->type ?? '')) }}">
						<div class="image-card__preview" onclick="ouvrirLightbox('{{ asset($image->url) }}', '{{ $image->produit->nom_produit ?? 'Image #'.$image->id_image }}')">
							<img
								src="{{ asset($image->url) }}"
								alt="{{ $image->produit->nom_produit ?? 'Image produit' }}"
								loading="lazy"
								onerror="this.closest('.image-card__preview').classList.add('image-error'); this.style.display='none';"
							>
							<div class="image-card__overlay">
								<span>🔍 Voir</span>
							</div>
							<div class="image-error-placeholder" style="display:none;">
								<span>⚠️</span>
								<span>Image introuvable</span>
							</div>
						</div>
						<div class="image-card__body">
							<p class="image-card__name">{{ $image->produit->nom_produit ?? '— Produit #'.$image->id_produit }}</p>
							@if($image->formeCondi)
								<div class="image-card__meta">
									<span class="chip">{{ $image->formeCondi->forme->nom_forme ?? '—' }}</span>
									<span class="chip">{{ $image->formeCondi->conditionnement->type ?? '—' }}</span>
								</div>
							@endif
							<p class="image-card__path" title="{{ $image->url }}">
								<span class="path-icon">🔗</span>{{ $image->url }}
							</p>
							<div class="image-card__actions">
								<button
									class="btn-icon btn-icon--edit"
									title="Remplacer l'image (conserve le lien)"
									onclick="ouvrirModalRemplaceImage({{ $image->id_image }}, '{{ asset($image->url) }}', '{{ $image->url }}', '{{ $image->produit->nom_produit ?? '' }}')"
								>🔄</button>
								<button
									class="btn-icon btn-icon--copy"
									title="Copier le lien"
									onclick="copierLien('{{ $image->url }}')"
								>📋</button>
								<form action="{{ route('image.destroy', $image->id_image) }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('cette image')">
									@csrf @method('DELETE')
									<button type="submit" class="btn-icon btn-icon--delete" title="Supprimer">🗑️</button>
								</form>
							</div>
						</div>
					</div>
					@endforeach
				</div>
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
							@foreach($rayons as $rayon)
							<tr>
								<td class="td-id">{{ $rayon->id_rayon }}</td>
								<td class="td-name">{{ $rayon->nom_rayon }}</td>
								<td>
									@forelse($rayon->events as $event)
										<span class="chip chip--dynamic-color" style="background:{{ $event->couleur }}20; border-color:{{ $event->couleur }};">
											{{ $event->icone }} {{ $event->nom_event }}
										</span>
									@empty
										<span class="text-muted">—</span>
									@endforelse
								</td>
								<td><span class="stock-badge stock-badge--ok">{{ $rayon->stock_total_rayon }}</span></td>
								<td class="td-actions">
									<button
										class="btn-icon btn-icon--edit"
										title="Modifier les events"
										onclick="ouvrirModalEditRayon({{ $rayon->id_rayon }}, '{{ $rayon->nom_rayon }}', {{ json_encode($rayon->events->pluck('id_event')) }})"
									>✏️</button>
									<form action="{{ route('rayon.destroy', $rayon->id_rayon) }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('ce rayon')">
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
				<h2 class="data-toolbar__title">Parfums <span class="data-count">{{ count($parfums) }}</span>
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

{{-- ══ MODAL NOUVEAU PRODUIT ══ --}}
<div id="modal-produit" class="data-modal hidden">
</div>

{{-- ══ MODAL AJOUTER IMAGE ══ --}}
<div id="modal-image" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>📤 Ajouter une image</h2>
		<p class="modal-hint">Associez le fichier image au produit et au conditionnement correspondants. Le fichier sera enregistré dans <code>/public/images/</code>.</p>
		<form action="{{ route('image.store') }}" method="POST" enctype="multipart/form-data" id="form-image">
			@csrf

			<label>Produit associé</label>
			<select name="id_produit" id="image-produit-select" required>
				<option value="">— Choisir un produit —</option>
				@foreach($produits as $p)
					<option value="{{ $p->id_produit }}">{{ $p->nom_produit ?? 'Produit #'.$p->id_produit }}</option>
				@endforeach
			</select>

			<label>Conditionnement associé (forme + conditionnement)</label>
			<select name="id_forme_condi" id="image-formecondi-select" required>
				<option value="">— Choisir un conditionnement —</option>
				@foreach($forme_condi as $fc)
					<option value="{{ $fc->id_forme_condi }}">
						{{ $fc->forme->nom_forme ?? '—' }} — {{ $fc->conditionnement->type ?? '—' }} ({{ $fc->prix }} €)
					</option>
				@endforeach
			</select>

			<label>Fichier image</label>
			<label for="image-file-input" class="drop-zone drop-zone--image mt-xs" id="drop-zone-image">
				<div class="drop-zone__icon">🖼️</div>
				<div class="drop-zone__label">
					<strong id="image-file-name">Choisir une image</strong>
					<span class="hint">.jpg, .jpeg, .png, .webp, .gif</span>
				</div>
				<input type="file" id="image-file-input" name="image" accept=".jpg,.jpeg,.png,.webp,.gif" hidden>
			</label>

			{{-- Aperçu image --}}
			<div id="image-preview-wrap" class="image-preview-wrap" style="display:none;">
				<img id="image-preview" src="" alt="Aperçu">
				<button type="button" id="image-preview-remove" class="image-preview-remove" title="Retirer">✕</button>
			</div>

			{{-- Chemin généré --}}
			<label class="mt-lg">Chemin généré <span class="label-hint">(automatique selon forme + conditionnement)</span></label>
			<div class="lien-genere" id="lien-genere">
				<span class="lien-genere__prefix">fichier/image/meringues/</span>
				<span class="lien-genere__value" id="lien-genere-value">— choisissez d'abord un conditionnement —</span>
			</div>

			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary" id="btn-submit-image" disabled>📤 Enregistrer</button>
			</div>
		</form>
	</div>
</div>

{{-- ══ MODAL REMPLACER IMAGE ══ --}}
<div id="modal-remplace-image" class="data-modal hidden">
	<div class="data-modal-content">
		<h2>🔄 Remplacer l'image</h2>
		<p class="modal-hint">Le lien restera identique. Seul le fichier physique sera remplacé.</p>

		<div class="lien-actuel-wrap">
			<span class="lien-actuel__label">Lien actuel</span>
			<span class="lien-actuel__value" id="lien-actuel-display">—</span>
		</div>

		<div class="image-remplace-preview">
			<div class="image-remplace-preview__before">
				<span class="preview-label">Image actuelle</span>
				<img id="remplace-img-actuelle" src="" alt="Image actuelle" onerror="this.src=''; this.closest('.image-remplace-preview__before').classList.add('no-image');">
			</div>
			<div class="image-remplace-preview__arrow">→</div>
			<div class="image-remplace-preview__after">
				<span class="preview-label">Nouvelle image</span>
				<div class="remplace-new-preview" id="remplace-new-preview">
					<span>Aucun fichier</span>
				</div>
			</div>
		</div>

		<form id="form-remplace-image" action="" method="POST" enctype="multipart/form-data">
			@csrf @method('POST')
			<input type="hidden" name="remplace_image" value="1">

			<label>Nouveau fichier</label>
			<label for="remplace-file-input" class="drop-zone drop-zone--image mt-xs" id="drop-zone-remplace">
				<div class="drop-zone__icon">📁</div>
				<div class="drop-zone__label">
					<strong id="remplace-file-name">Choisir un fichier</strong>
					<span class="hint">.jpg, .jpeg, .png, .webp, .gif</span>
				</div>
				<input type="file" id="remplace-file-input" name="image" accept=".jpg,.jpeg,.png,.webp,.gif" hidden>
			</label>

			<div class="modal-actions">
				<button type="button" class="btn-close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary" id="btn-submit-remplace" disabled>🔄 Remplacer</button>
			</div>
		</form>
	</div>
</div>

{{-- ══ LIGHTBOX IMAGE ══ --}}
<div id="lightbox" class="lightbox hidden" onclick="fermerLightbox(event)">
	<button class="lightbox__close" onclick="fermerLightbox()">✕</button>
	<div class="lightbox__content" onclick="event.stopPropagation()">
		<img id="lightbox-img" src="" alt="Image agrandie">
		<div class="lightbox__info">
			<p class="lightbox__name" id="lightbox-name"></p>
			<p class="lightbox__path" id="lightbox-path"></p>
			<button class="btn btn--ghost btn--sm" onclick="copierLien(document.getElementById('lightbox-path').textContent)">📋 Copier le lien</button>
		</div>
	</div>
</div>

{{-- ══ TOAST NOTIFICATION ══ --}}
<div id="toast" class="toast hidden"></div>

{{-- ══ MODAL NOUVEAU RAYON ══ --}}
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

{{-- ══ MODAL NOUVEAU THÈME ══ --}}
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

{{-- ══ MODAL NOUVEL EVENT ══ --}}
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

{{-- ══ MODAL NOUVELLE FORME ══ --}}
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

{{-- ══ MODAL NOUVEAU CONDITIONNEMENT ══ --}}
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

{{-- ══ MODAL NOUVEAU PRIX ══ --}}
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

{{-- ══ MODAL NOUVEAU PARFUM ══ --}}
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

{{-- ══ MODAL MODIFIER RAYON ══ --}}
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

{{-- ══ OVERLAY CHARGEMENT ══ --}}
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
// ONGLETS (tabs + navigation rapide)
// ════════════════════════════════════════
function activerOnglet(tabName) {
	document.querySelectorAll('.tabs .tab').forEach(t => t.classList.remove('active'));
	document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
	document.querySelectorAll('.quick-nav__item').forEach(i => i.classList.remove('active'));

	const tabBtn = document.querySelector(`.tabs .tab[data-tab="${tabName}"]`);
	const panel  = document.getElementById('tab-' + tabName);
	const navBtn = document.querySelector(`.quick-nav__item[data-target="${tabName}"]`);

	if (tabBtn)  tabBtn.classList.add('active');
	if (panel)   panel.classList.remove('hidden');
	if (navBtn)  navBtn.classList.add('active');
}

document.querySelectorAll('.tabs .tab').forEach(btn => {
	btn.addEventListener('click', () => activerOnglet(btn.dataset.tab));
});

document.querySelectorAll('.quick-nav__item').forEach(btn => {
	btn.addEventListener('click', () => activerOnglet(btn.dataset.target));
});

// ════════════════════════════════════════
// CONFIRMATION SUPPRESSION
// ════════════════════════════════════════
function confirmSuppr(label) {
	return confirm('Voulez-vous vraiment supprimer ' + label + ' ?');
}

// ════════════════════════════════════════
// OUVERTURE MODALS
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
// RECHERCHE IMAGES
// ════════════════════════════════════════
document.getElementById('search-input-images')?.addEventListener('input', function () {
	const query = this.value.toLowerCase();
	document.querySelectorAll('.image-card').forEach(card => {
		const search = card.dataset.search || '';
		card.style.display = search.includes(query) ? '' : 'none';
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
// TOGGLES AJAX (live, expedition, emporter, nouveaute)
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
// PAGINATION
// ════════════════════════════════════════
document.querySelectorAll('.table-wrapper').forEach(wrapper => {
	const table = wrapper.querySelector('table');
	const rowsPerPage = 10;
	let currentPage = 1;
	const rows = Array.from(table.querySelectorAll('tbody tr'));
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

	prevBtn.addEventListener('click', () => { if (currentPage > 1)           { currentPage--; renderTable(); } });
	nextBtn.addEventListener('click', () => { if (currentPage < totalPages)  { currentPage++; renderTable(); } });
	renderTable();
});

// ════════════════════════════════════════
// IMAGES — MODAL AJOUTER
// ════════════════════════════════════════
const imageFileInput    = document.getElementById('image-file-input');
const imagePreviewWrap  = document.getElementById('image-preview-wrap');
const imagePreview      = document.getElementById('image-preview');
const imagePreviewRemove = document.getElementById('image-preview-remove');
const btnSubmitImage    = document.getElementById('btn-submit-image');

function sanitizeFilename(name) {
	return name.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-_.]/g, '');
}

// Données des forme_condi pour afficher le chemin dynamique
@php
$formeCondiJs = $forme_condi->mapWithKeys(function($fc) {
    return [
        $fc->id_forme_condi => [
            'id'    => $fc->id_forme_condi,
            'forme' => $fc->forme->nom_forme ?? 'divers',
            'condi' => $fc->conditionnement->type ?? 'divers',
        ]
    ];
});
@endphp
const formeCondiData = @json($formeCondiJs);

function slugify(str) {
	return str.toLowerCase()
		.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
		.replace(/[^a-z0-9]+/g, '-')
		.replace(/^-+|-+$/g, '');
}

function mettreAjourCheminGenere() {
	const fcId    = document.getElementById('image-formecondi-select')?.value;
	const fichier = imageFileInput?.files[0]?.name || null;
	const val     = document.getElementById('lien-genere-value');
	if (!val) return;

	if (!fcId || !fichier) {
		val.textContent = fcId
			? '— choisissez un fichier —'
			: '— choisissez d\'abord un conditionnement —';
		return;
	}

	const fc = formeCondiData[fcId];
	if (!fc) { val.textContent = '—'; return; }

	const forme = slugify(fc.forme);
	const condi = slugify(fc.condi);
	val.textContent = forme + '/' + condi + '/' + fichier;
}

document.getElementById('image-formecondi-select')?.addEventListener('change', mettreAjourCheminGenere);

imageFileInput?.addEventListener('change', function () {
	if (this.files.length > 0) {
		const file = this.files[0];
		const reader = new FileReader();
		reader.onload = e => {
			imagePreview.src = e.target.result;
			imagePreviewWrap.style.display = 'block';
		};
		reader.readAsDataURL(file);

		document.getElementById('image-file-name').textContent = file.name;
		mettreAjourCheminGenere();
		btnSubmitImage.disabled = false;
	}
});

imagePreviewRemove?.addEventListener('click', () => {
	imageFileInput.value = '';
	imagePreview.src = '';
	imagePreviewWrap.style.display = 'none';
	document.getElementById('image-file-name').textContent = 'Choisir une image';
	lienGenereValue.textContent = '—';
	btnSubmitImage.disabled = true;
});

// Drag & drop zone image
const dropZoneImage = document.getElementById('drop-zone-image');
dropZoneImage?.addEventListener('dragover',  e => { e.preventDefault(); dropZoneImage.classList.add('drag-over'); });
dropZoneImage?.addEventListener('dragleave', () => dropZoneImage.classList.remove('drag-over'));
dropZoneImage?.addEventListener('drop', e => {
	e.preventDefault();
	dropZoneImage.classList.remove('drag-over');
	if (e.dataTransfer.files.length > 0) {
		imageFileInput.files = e.dataTransfer.files;
		imageFileInput.dispatchEvent(new Event('change'));
	}
});

// ════════════════════════════════════════
// IMAGES — MODAL REMPLACER
// ════════════════════════════════════════
function ouvrirModalRemplaceImage(id, srcAsset, urlRelative, nom) {
	document.getElementById('lien-actuel-display').textContent = urlRelative;
	document.getElementById('remplace-img-actuelle').src = srcAsset;
	document.getElementById('form-remplace-image').action = '/gestion/image/' + id + '/remplacer';
	document.getElementById('remplace-file-name').textContent = 'Choisir un fichier';
	document.getElementById('btn-submit-remplace').disabled = true;
	document.getElementById('remplace-new-preview').innerHTML = '<span>Aucun fichier</span>';
	document.getElementById('modal-remplace-image').classList.remove('hidden');
}

const remplaceFileInput = document.getElementById('remplace-file-input');
remplaceFileInput?.addEventListener('change', function () {
	if (this.files.length > 0) {
		const file = this.files[0];
		const reader = new FileReader();
		reader.onload = e => {
			document.getElementById('remplace-new-preview').innerHTML = `<img src="${e.target.result}" alt="Nouvelle image">`;
		};
		reader.readAsDataURL(file);
		document.getElementById('remplace-file-name').textContent = file.name;
		document.getElementById('btn-submit-remplace').disabled = false;
	}
});

const dropZoneRemplace = document.getElementById('drop-zone-remplace');
dropZoneRemplace?.addEventListener('dragover',  e => { e.preventDefault(); dropZoneRemplace.classList.add('drag-over'); });
dropZoneRemplace?.addEventListener('dragleave', () => dropZoneRemplace.classList.remove('drag-over'));
dropZoneRemplace?.addEventListener('drop', e => {
	e.preventDefault();
	dropZoneRemplace.classList.remove('drag-over');
	if (e.dataTransfer.files.length > 0) {
		remplaceFileInput.files = e.dataTransfer.files;
		remplaceFileInput.dispatchEvent(new Event('change'));
	}
});

// ════════════════════════════════════════
// LIGHTBOX
// ════════════════════════════════════════
function ouvrirLightbox(src, nom) {
	document.getElementById('lightbox-img').src = src;
	document.getElementById('lightbox-name').textContent = nom || 'Image';
	document.getElementById('lightbox-path').textContent = src;
	document.getElementById('lightbox').classList.remove('hidden');
	document.body.style.overflow = 'hidden';
}

function fermerLightbox(e) {
	if (!e || e.target === document.getElementById('lightbox') || e.target.classList.contains('lightbox__close')) {
		document.getElementById('lightbox').classList.add('hidden');
		document.body.style.overflow = '';
	}
}

document.addEventListener('keydown', e => {
	if (e.key === 'Escape') {
		document.querySelectorAll('.data-modal').forEach(m => m.classList.add('hidden'));
		fermerLightbox();
	}
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
// COPIER LIEN
// ════════════════════════════════════════
function copierLien(lien) {
	navigator.clipboard.writeText(lien)
		.then(() => afficherToast('Lien copié ! 📋'))
		.catch(() => afficherToast('Impossible de copier', 'error'));
}
</script>
</document_content>
