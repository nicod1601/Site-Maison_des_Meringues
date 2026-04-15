@include('templet.header',
    ['titre' => 'Les Données'],
    ['note' => 'Modifier, supprimer vos données'],
    ['style' => 'resources/css/data.css'])

<div class="tabs">
    <button class="tab active" data-tab="produits">🍬 Produits</button>
    <button class="tab" data-tab="formes">🔷 Formes</button>
    <button class="tab" data-tab="conditionnements">📦 Conditionnements</button>
</div>


{{-- ══ PRODUITS ══ --}}
<div id="tab-produits" class="tab-panel">

    <div class="data-toolbar">
        <h2 class="data-toolbar__title">Produits <span class="data-count">{{ count($produits) }}</span></h2>
        <a href="/gestion/produit/create" class="btn btn--primary btn--sm">+ Nouveau produit</a>
    </div>

    @if(count($produits) > 0)
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Parfum</th>
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
                    <td class="td-name">{{ $produit->parfums->nom_parfum }}</td>
                    <td class="td-desc text-muted">{{ Str::limit($produit->description, 60) }}</td>
                    <td><span class="chip">{{ $produit->forme->nom_forme ?? '—' }}</span></td>
                    <td><span class="chip chip--gold">{{ $produit->conditionnement->type ?? '—' }}</span></td>
                    <td class="td-prix">{{ number_format($produit->prix, 2, ',', ' ') }} €</td>
                    <td>
                        @if($produit->quantite > 0)
                            <span class="stock-badge stock-badge--ok">{{ $produit->quantite }}</span>
                        @else
                            <span class="stock-badge stock-badge--rupture">Rupture</span>
                        @endif
                    </td>
                    <td class="td-actions">
                        <a href="/gestion/produit/{{ $produit->id_produit }}/edit" class="btn-icon btn-icon--edit" title="Modifier">✏️</a>
                        <form action="/gestion/produit/{{ $produit->id_produit }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('ce produit')">
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
                    <th>Description</th>
                    <th>Produits liés</th>
                    <th class="th-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($formes as $forme)
                <tr>
                    <td class="td-id">{{ $forme->id_forme }}</td>
                    <td class="td-name">{{ $forme->nom_forme }}</td>
                    <td class="td-desc text-muted">{{ $forme->description ?? '—' }}</td>
                    <td><span class="chip">{{ $forme->produits_count ?? 0 }} produit(s)</span></td>
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
                    <th>Capacité</th>
                    <th>Description</th>
                    <th>Produits liés</th>
                    <th class="th-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($conditionnements as $cond)
                <tr>
                    <td class="td-id">{{ $cond->id_conditionnement }}</td>
                    <td class="td-name">{{ $cond->type }}</td>
                    <td>{{ $cond->capacite ?? '—' }}</td>
                    <td class="td-desc text-muted">{{ $cond->description ?? '—' }}</td>
                    <td><span class="chip chip--gold">{{ $cond->produits_count ?? 0 }} produit(s)</span></td>
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
        <a href="/gestion/conditionnement/create" class="btn btn--primary">Créer un conditionnement</a>
    </div>
    @endif

</div>

</main>

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
</script>
