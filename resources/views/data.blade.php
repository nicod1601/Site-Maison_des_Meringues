@include('templet.header',
	['titre' => 'Les Données'],
	['note' => 'Modifier, supprimer vos données'])
@vite('resources/css/data.css')

	<div class="tabs">
		<button class="tab active" data-tab="produits">🍬 Produits</button>
		<button class="tab" data-tab="formes">🔷 Formes</button>
		<button class="tab" data-tab="conditionnements">📦 Conditionnements</button>
	</div>


	{{-- ══ PRODUITS ══ --}}
	<div id="tab-produits" class="tab-panel">

		<div class="data-toolbar">
			<h2 class="data-toolbar__title">Produits <span class="data-count">{{ count($produits) }}</span></h2>
			<button class="btn btn--primary btn--sm" id="btn-nvproduit">+ Nouveau produit</button>
		</div>

		@if(count($produits) > 0)
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
							<a href="/gestion/produit/{{ $produit->id_produit }}/edit" class="btn-icon btn-icon--edit" title="Modifier">✏️</a>
							<form action="/gestion/produit/{{ $produit->id_produit }}" method="POST" class="form-delete">
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
			<h2 class="data-toolbar__title">Formes <span class="data-count">{{ count($formes) }}</span></h2>
			<a href="/gestion/forme/create" class="btn btn--primary btn--sm">+ Nouvelle forme</a>
		</div>

		@if(count($formes) > 0)
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
							<a href="/gestion/forme/{{ $forme->id_forme }}/edit" class="btn-icon btn-icon--edit" title="Modifier">✏️</a>
							<form action="/gestion/forme/{{ $forme->id_forme }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('cette forme')">
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
			<h2 class="data-toolbar__title">Conditionnements <span class="data-count">{{ count($conditionnements) }}</span></h2>
			<a href="/gestion/conditionnement/create" class="btn btn--primary btn--sm">+ Nouveau conditionnement</a>
		</div>

		@if(count($conditionnements) > 0)
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
						<td class="td-id">{{ $cond->id_conditionnement }}</td>
						<td class="td-name">{{ $cond->type }}</td>
						<td class="td-actions">
							<a href="/gestion/conditionnement/{{ $cond->id_conditionnement }}/edit" class="btn-icon btn-icon--edit" title="Modifier">✏️</a>
							<form action="/gestion/conditionnement/{{ $cond->id_conditionnement }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('ce conditionnement')">
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
			<a href="/data/produit" class="btn btn--primary">Créer un conditionnement</a>
		</div>
		@endif
	</div>
</main>
<div id="modal-produit" class="data-modal hidden" >
	<div class="data-modal-content">
		<h2>Nouveau produit</h2>

		<form method="POST" action="/data/produit">
			@csrf

			<label>Parfum</label>
			<select name="id_parfum">
				@foreach($parfums as $parfum)
					<option value="{{ $parfum->id_parfum }}">{{ $parfum->nom_parfum }}</option>
				@endforeach
			</select>

			<label>Forme / Conditionnement</label>
			<select name="id_forme_condi">
				@foreach($forme_condi as $fc)
					<option value="{{ $fc->id_forme_condi }}">
						{{ $fc->forme->nom_forme }} — {{ $fc->conditionnement->type }}
					</option>
				@endforeach
			</select>

			<label>Description</label>
			<input type="text" name="description" value="Acune description">

			<label>Stock</label>
			<input type="number" name="quantite" min="0" value="0">

			<div class="modal-actions">
				<button type="button" id="close-modal">Annuler</button>
				<button type="submit" class="btn btn--primary" id="creer">Créer</button>
			</div>
		</form>
	</div>
</div>
<script>
	document.querySelectorAll('.tab').forEach(tab => {
		tab.addEventListener('click', () => {
			document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
			document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
			tab.classList.add('active');
			document.getElementById('tab-' + tab.dataset.tab).classList.remove('hidden');
		});
	});

	function confirmSuppr(label) {
		return confirm('Voulez-vous vraiment supprimer ' + label + ' ? Cette action est irréversible.');
	}

	const btnNvProduit = document.getElementById('btn-nvproduit');

	const modal = document.getElementById('modal-produit');

	btnNvProduit.addEventListener('click', () => {
		modal.classList.remove('hidden');
	});

	document.getElementById('close-modal').addEventListener('click', () => {
		modal.classList.add('hidden');
	});
</script>

</body>
</html>

