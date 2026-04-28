@include('templet.header',
	['titre' => 'Gestion des Données'],
	['note'  => 'Importer et gérer vos données'])
@vite('resources/css/gestion.css')

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
					<div class="stat-card">
						<span class="stat-card__value">{{ count($rayons) }}</span>
						<span class="stat-card__label">Rayons</span>
					</div>
					<hr class="mt-lg mb-lg">
					<div class="stat-card">
						<span class="stat-card__value">{{ count($themes) }}</span>
						<span class="stat-card__label">Thèmes</span>
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

	</div>{{-- /gestion-left-panel --}}

	{{-- ══ COLONNE DROITE ══ --}}
	<div class="gestion-right-panel">

		{{-- Onglets --}}
		<div class="tabs">
			<button class="tab active" data-tab="produits">🍬 Produits</button>
			<button class="tab" data-tab="rayons">🗂 Rayons</button>
			<button class="tab" data-tab="formes">🔷 Formes</button>
			<button class="tab" data-tab="conditionnements">📦 Conditionnements</button>
			<button class="tab" data-tab="parfums">🍓 Parfums</button>
			<button class="tab" data-tab="themes">🎨 Thèmes</button>
			<button class="tab" data-tab="events">🎉 Events</button>{{-- ✅ Onglet Events ajouté --}}
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

					<input type="text" id="search-input" class="form-input form-input--sm" placeholder="Rechercher...">

					@if($rayonId && $rayonId != '-1')
						@php $rayonActif = $rayons->firstWhere('id_rayon', $rayonId); @endphp
						@if($rayonActif)
							<form action="/gestion/rayon/{{ $rayonActif->id_rayon }}/live" method="POST">
								@csrf @method('PATCH')
								<label>
									<input
										type="checkbox"
										name="live_rayon"
										value="1"
										{{ $rayonActif->live_rayon ? 'checked' : '' }}
										onchange="this.form.submit()"
									> Live
								</label>
							</form>
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
								<th>#</th>
								<th>Article</th>
								<th>Forme</th>
								<th>Parfum</th>
								<th>Description</th>
								<th>Rayons</th>
								<th>Thème</th>
								<th>Stock</th>
								<th>Nouveauté</th>
								<th>Emporter</th>
								<th>Expédition</th>
								<th class="th-actions">Live</th>
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

								{{-- Rayons (many-to-many) --}}
								<td>
									@forelse($produit->rayons as $r)
										<span class="chip">{{ $r->nom_rayon }}</span>
									@empty
										<span class="text-muted">—</span>
									@endforelse
								</td>

								{{-- Thème du produit (optionnel, indicateur de tri) --}}
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
											<label>
												<input
													type="checkbox"
													name="nouveaute"
													value="1"
													{{ $produit->nouveaute ? 'checked' : '' }}
												>
											</label>
										</form>
									</div>
								</td>

								<td>
									<div class="actions-wrap">
										<form action="/gestion/produit/{{ $produit->id_produit }}/emporter" method="POST">
											@csrf @method('PATCH')
											<label>
												<input
													type="checkbox"
													name="dispo_emporter"
													value="1"
													{{ $produit->dispo_emporter ? 'checked' : '' }}
												>
											</label>
										</form>
									</div>
								</td>

								<td>
									<div class="actions-wrap">
										<form action="/gestion/produit/{{ $produit->id_produit }}/expedition" method="POST">
											@csrf @method('PATCH')
											<label>
												<input
													type="checkbox"
													name="dispo_expedition"
													value="1"
													{{ $produit->dispo_expedition ? 'checked' : '' }}
												>
											</label>
										</form>
									</div>
								</td>

								<td>
									<div class="actions-wrap">
										<form action="/gestion/produit/{{ $produit->id_produit }}/live" method="POST">
											@csrf @method('PATCH')
											<label>
												<input
													type="checkbox"
													name="live"
													value="1"
													{{ $produit->live ? 'checked' : '' }}
												>
											</label>
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
							{{-- ✅ Corrigé : colonne Thèmes → Events --}}
							<tr><th>#</th><th>Nom</th><th>Events</th><th>Stock</th><th class="th-actions">Actions</th></tr>
						</thead>
						<tbody>
							@foreach($rayons as $rayon)
							<tr>
								<td class="td-id">{{ $rayon->id_rayon }}</td>
								<td class="td-name">{{ $rayon->nom_rayon }}</td>
								<td>
									{{-- ✅ Corrigé : $rayon->themes → $rayon->events --}}
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
									{{-- Bouton modifier les events du rayon --}}
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

								<td>
									<span class="data-count">{{ $produitsByTheme[$theme->id_theme] ?? 0 }}</span>
								</td>
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
								<td>
									<span class="data-count">{{ $event->rayons->count() }}</span>
								</td>
								<td>
									<span class="data-count">{{ $produitsByEvent[$event->id_event] ?? 0 }}</span>
								</td>
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
				<h2 class="data-toolbar__title">Parfums <span class="data-count">{{ count($parfums) }}</span></h2>
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
	<div class="data-modal-content">
		<h2>Nouveau produit</h2>
		<form action="{{ route('produit.store') }}" method="POST">
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
			<label>Thème <span style="font-weight:400;">(optionnel — indicateur de tri)</span></label>
			<select name="id_theme">
				<option value="">— Aucun thème —</option>
				@foreach($themes as $theme)
					<option value="{{ $theme->id_theme }}">{{ $theme->icone }} {{ $theme->nom_theme }}</option>
				@endforeach
			</select>
			<label>Rayon</label>
			<select name="id_rayon" required>
				@foreach($rayons as $rayon)
					<option value="{{ $rayon->id_rayon }}" {{ $rayonId == $rayon->id_rayon ? 'selected' : '' }}>
						{{-- ✅ Corrigé : ->themes → ->events --}}
						@foreach($rayon->events as $e){{ $e->icone }} @endforeach {{ $rayon->nom_rayon }}
					</option>
				@endforeach
			</select>
			<label>Nom du produit</label>
			<input type="text" name="nom_produit" placeholder="Nom du produit" required>
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

			{{-- ✅ Corrigé : sélection d'Events (et non de Thèmes) pour le rayon --}}
			{{-- Un rayon est lié à des events (occasion), pas à des thèmes (tri produit) --}}
			<label>Events associés <span style="font-weight:400; text-transform:none; letter-spacing:0;">(optionnel — les produits éligibles à ces events seront liés)</span></label>
			<div class="themes-selector">
				@foreach($events as $event)
					<label class="themes-selector-item" id="event-label-{{ $event->id_event }}">
						<input
							type="checkbox"
							name="id_events[]"
							value="{{ $event->id_event }}"
							data-couleur="{{ $event->couleur }}"
							class="event-checkbox"
						>
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
{{-- ✅ Nouveau modal Event ajouté --}}
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
			<label>Events associés <span style="font-weight:400; text-transform:none; letter-spacing:0;">(les produits seront automatiquement mis à jour)</span></label>
			<div class="themes-selector" id="edit-events-selector">
				@foreach($events as $event)
					<label class="themes-selector-item edit-event-item" data-id="{{ $event->id_event }}" data-couleur="{{ $event->couleur }}">
						<input
							type="checkbox"
							name="id_events[]"
							value="{{ $event->id_event }}"
							data-couleur="{{ $event->couleur }}"
							class="edit-event-checkbox"
						>
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
	// ── Fichier drag & drop ──
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

	// ── Onglets ──
	document.querySelectorAll('.tabs .tab').forEach(btn => {
		btn.addEventListener('click', () => {
			document.querySelectorAll('.tabs .tab').forEach(t => t.classList.remove('active'));
			document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
			btn.classList.add('active');
			document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
		});
	});

	// ── Confirmation suppression ──
	function confirmSuppr(label) {
		return confirm('Voulez-vous vraiment supprimer ' + label + ' ?');
	}

	// ── Ouverture modals ──
	document.getElementById('btn-nvproduit') .addEventListener('click', () => document.getElementById('modal-produit').classList.remove('hidden'));
	document.getElementById('btn-nvrayon')   .addEventListener('click', () => document.getElementById('modal-rayon')  .classList.remove('hidden'));
	document.getElementById('btn-nvrayon2')  .addEventListener('click', () => document.getElementById('modal-rayon')  .classList.remove('hidden'));
	// ✅ Corrigé : btn-nvtheme retiré du panneau gauche, remplacé par btn-nvevent
	document.getElementById('btn-nvevent')   .addEventListener('click', () => document.getElementById('modal-event')  .classList.remove('hidden'));
	document.getElementById('btn-nvevent2')  .addEventListener('click', () => document.getElementById('modal-event')  .classList.remove('hidden'));
	document.getElementById('btn-nvtheme2')  .addEventListener('click', () => document.getElementById('modal-theme')  .classList.remove('hidden'));
	document.getElementById('btn-nvforme')   .addEventListener('click', () => document.getElementById('modal-forme')  .classList.remove('hidden'));
	document.getElementById('btn-nvcondi')   .addEventListener('click', () => document.getElementById('modal-condi')  .classList.remove('hidden'));
	document.getElementById('btn-nvprix')    .addEventListener('click', () => document.getElementById('modal-prix')   .classList.remove('hidden'));
	document.getElementById('btn-nvparfum')  .addEventListener('click', () => document.getElementById('modal-parfum') .classList.remove('hidden'));

	// ── Fermeture modals ──
	document.addEventListener('click', e => {
		if (e.target.classList.contains('data-modal') || e.target.classList.contains('btn-close-modal')) {
			document.querySelectorAll('.data-modal').forEach(m => m.classList.add('hidden'));
		}
	});

	// ── Overlay import ──
	document.getElementById('import-form').addEventListener('submit', () => {
		document.getElementById('loading-overlay').classList.add('visible');
	});

	// ── Validation import ──
	const submitBtn  = document.getElementById('import-form').querySelector('button[type="submit"]');
	function updateSubmitState() {
		submitBtn.disabled = !(inputFichier.files.length > 0);
	}
	inputFichier.addEventListener('change', updateSubmitState);
	removeBtn.addEventListener('click', () => setTimeout(updateSubmitState, 10));
	updateSubmitState();

	// ── Filtre rayon ──
	document.getElementById('select-rayon').addEventListener('change', function () {
		const url = new URL(window.location.href);
		this.value === '-1' ? url.searchParams.delete('rayon') : url.searchParams.set('rayon', this.value);
		window.location.href = url.toString();
	});

	// ── Checkboxes events dans le modal rayon ──
	// ✅ Corrigé : theme-checkbox → event-checkbox, produitsByTheme → produitsByEvent
	const produitsByEvent = @json($produitsByEvent);

	document.querySelectorAll('.event-checkbox').forEach(checkbox => {
		checkbox.addEventListener('change', function () {
			const label   = this.closest('label');
			const couleur = this.dataset.couleur;
			if (this.checked) {
				label.style.borderColor = couleur;
				label.style.background  = couleur + '20';
				label.style.color       = couleur;
			} else {
				label.style.borderColor = 'var(--color-border)';
				label.style.background  = 'var(--color-cream)';
				label.style.color       = 'inherit';
			}
			updatePreview();
		});
	});

	function updatePreview() {
		const checked = [...document.querySelectorAll('.event-checkbox:checked')]
			.map(cb => parseInt(cb.value));

		const preview = document.getElementById('preview-produits');
		const text    = document.getElementById('preview-text');

		if (checked.length === 0) {
			preview.style.display = 'none';
			return;
		}

		let total = 0;
		checked.forEach(id => { total += produitsByEvent[id] || 0; });

		preview.style.display = 'block';
		text.textContent = `${total} produit(s) éligible(s) seront automatiquement liés à ce nouveau rayon`;
	}

	// ── Recherche rapide produits ──
	document.getElementById('search-input').addEventListener('input', function () {
		const query = this.value.toLowerCase();
		document.querySelectorAll('#tab-produits tbody tr').forEach(row => {
			const namecode = row.querySelector('.td-namecode').textContent.toLowerCase();
			const name     = row.querySelector('.td-name').textContent.toLowerCase();
			row.style.display = (namecode.includes(query) || name.includes(query)) ? '' : 'none';
		});
	});

	// ── Modal modifier rayon ──
	function ouvrirModalEditRayon(id, nom, eventIds) {
		document.getElementById('edit-rayon-titre').textContent = 'Modifier : ' + nom;
		document.getElementById('form-edit-rayon').action = '/gestion/rayon/' + id;

		// Cocher les events actuels du rayon
		document.querySelectorAll('.edit-event-checkbox').forEach(cb => {
			const checked = eventIds.includes(parseInt(cb.value));
			cb.checked = checked;
			const label = cb.closest('label');
			const couleur = cb.dataset.couleur;
			if (checked) {
				label.style.borderColor = couleur;
				label.style.background  = couleur + '20';
				label.style.color       = couleur;
			} else {
				label.style.borderColor = 'var(--color-border)';
				label.style.background  = 'var(--color-cream)';
				label.style.color       = 'inherit';
			}
		});

		document.getElementById('modal-edit-rayon').classList.remove('hidden');
	}

	document.querySelectorAll('.edit-event-checkbox').forEach(checkbox => {
		checkbox.addEventListener('change', function () {
			const label   = this.closest('label');
			const couleur = this.dataset.couleur;
			if (this.checked) {
				label.style.borderColor = couleur;
				label.style.background  = couleur + '20';
				label.style.color       = couleur;
			} else {
				label.style.borderColor = 'var(--color-border)';
				label.style.background  = 'var(--color-cream)';
				label.style.color       = 'inherit';
			}
		});
	});

	// ── Toggles Live & Expédition sans rechargement ──────────────
	// Remplace le bloc générique existant par celui-ci
		document.querySelectorAll('input[name="live"], input[name="dispo_expedition"], input[name="dispo_emporter"], input[name="nouveaute"]').forEach(checkbox => {
		checkbox.addEventListener('change', function () {
			const form    = this.closest('form');
			const url     = form.action;
			const checked = this.checked;
			const name    = this.name;

			// Construire le body
			const body = new URLSearchParams();
			body.append('_token', document.querySelector('meta[name="csrf-token"]')?.content
				|| '{{ csrf_token() }}');
			body.append('_method', 'PATCH');
			body.append(name, checked ? '1' : '0');

			fetch(url, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: body.toString(),
			})
			.then(res => {
				if (!res.ok) {
					// Annuler visuellement si erreur
					this.checked = !checked;
					console.error('Erreur toggle', name);
				}
			})
			.catch(() => {
				this.checked = !checked;
			});
		});
	});

	//Pagination des Tableaux
	document.querySelectorAll('.table-wrapper').forEach(wrapper => {
		const table = wrapper.querySelector('table');
		const rowsPerPage = 10;
		let currentPage = 1;

		const rows = Array.from(table.querySelectorAll('tbody tr'));
		const totalPages = Math.ceil(rows.length / rowsPerPage);

		function renderTable() {
			rows.forEach((row, index) => {
				row.style.display = (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) ? '' : 'none';
			});
			pageInfo.textContent = `Page ${currentPage} sur ${totalPages}`;
			prevBtn.disabled = currentPage === 1;
			nextBtn.disabled = currentPage === totalPages;
		}

		const paginationControls = document.createElement('div');
		paginationControls.className = 'pagination-controls';
		const prevBtn = document.createElement('button');
		prevBtn.textContent = 'Précédent';
		const nextBtn = document.createElement('button');
		nextBtn.textContent = 'Suivant';
		const pageInfo = document.createElement('span');
		paginationControls.appendChild(prevBtn);
		paginationControls.appendChild(pageInfo);
		paginationControls.appendChild(nextBtn);
		wrapper.appendChild(paginationControls);

		prevBtn.addEventListener('click', () => {
			if (currentPage > 1) {
				currentPage--;
				renderTable();
			}
		});
		nextBtn.addEventListener('click', () => {
			if (currentPage < totalPages) {
				currentPage++;
				renderTable();
			}
		});

		renderTable();
	});

</script>
