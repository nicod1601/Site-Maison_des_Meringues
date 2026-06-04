@php
	use Illuminate\Support\Facades\Auth;
	$user    = Auth::user();
	$isAdmin = $user->isAdmin();
@endphp

@include('templet.header', [
	'titre' => $isAdmin ? 'Gestion des commandes' : 'Mes commandes',
	'title' => 'Tickets'
])

<style>
/* ── BASE ───────────────────────────────────────────────────── */
.tk-page {
	max-width: 1060px;
	margin: 2rem auto;
	padding: 0 1.25rem 5rem;
	display: grid;
	grid-template-columns: 200px 1fr;
	gap: 2rem;
	align-items: start;
	font-family: 'DM Sans', sans-serif;
}

/* ── SIDEBAR ────────────────────────────────────────────────── */
.tk-sidebar {
	position: sticky;
	top: 90px;
}

.tk-sidebar__heading {
	font-size: .65rem;
	text-transform: uppercase;
	letter-spacing: .14em;
	color: var(--color-text-muted, #8C7B6A);
	font-weight: 600;
	margin-bottom: .6rem;
	padding-left: 4px;
}

.tk-nav {
	background: #fff;
	border: 1px solid var(--color-border, #E8DEC8);
	border-radius: 14px;
	overflow: hidden;
	margin-bottom: 1.25rem;
}

.tk-nav__item {
	display: flex;
	align-items: center;
	gap: 8px;
	padding: .75rem 1rem;
	font-size: .82rem;
	font-weight: 500;
	color: var(--color-text-muted, #8C7B6A);
	cursor: pointer;
	border-left: 2.5px solid transparent;
	border-bottom: 1px solid var(--color-border, #E8DEC8);
	transition: all .15s;
	user-select: none;
}

.tk-nav__item:last-child { border-bottom: none; }
.tk-nav__item:hover { background: #faf6ee; color: var(--color-text, #2E1A10); }

.tk-nav__item.active {
	color: var(--color-primary, #C0395A);
	background: #fdf5f7;
	border-left-color: var(--color-primary, #C0395A);
	font-weight: 700;
}

.tk-nav__dot {
	width: 8px;
	height: 8px;
	border-radius: 50%;
	flex-shrink: 0;
}

/* ── RÉSUMÉ SIDEBAR ─────────────────────────────────────────── */
.tk-summary {
	background: #fff;
	border: 1px solid var(--color-border, #E8DEC8);
	border-radius: 14px;
	padding: 1rem 1.1rem;
}

.tk-summary__title {
	font-size: .65rem;
	text-transform: uppercase;
	letter-spacing: .12em;
	color: var(--color-text-muted, #8C7B6A);
	font-weight: 600;
	margin-bottom: .75rem;
}

.tk-summary__row {
	display: flex;
	justify-content: space-between;
	font-size: .8rem;
	padding: .3rem 0;
	color: var(--color-text-muted, #8C7B6A);
}

.tk-summary__row strong {
	color: var(--color-text, #2E1A10);
}

.tk-summary__row strong.red {
	color: var(--color-primary, #C0395A);
}

/* ── CONTENU PRINCIPAL ──────────────────────────────────────── */
.tk-content {}

.tk-header {
	margin-bottom: 1.25rem;
}

.tk-title {
	font-family: var(--font-serif, 'Playfair Display', serif);
	font-size: 1.5rem;
	font-weight: 700;
	color: var(--color-text, #2E1A10);
	margin: 0 0 3px;
}

.tk-subtitle {
	font-size: .8rem;
	color: var(--color-text-muted, #8C7B6A);
}

/* ── FILTRES RAPIDES ────────────────────────────────────────── */
.tk-filters {
	display: flex;
	gap: .5rem;
	flex-wrap: wrap;
	margin-bottom: 1.25rem;
}

.tk-filter-btn {
	padding: .35rem .85rem;
	border-radius: 999px;
	border: 1px solid var(--color-border, #E8DEC8);
	background: #fff;
	font-size: .78rem;
	font-weight: 500;
	color: var(--color-text-muted, #8C7B6A);
	cursor: pointer;
	transition: all .15s;
}

.tk-filter-btn:hover { border-color: var(--color-primary, #C0395A); color: var(--color-primary, #C0395A); }
.tk-filter-btn.active { background: var(--color-primary, #C0395A); border-color: var(--color-primary, #C0395A); color: #fff; font-weight: 700; }

/* ── TICKET CARD ────────────────────────────────────────────── */
.tk-card {
	background: #fff;
	border: 1px solid var(--color-border, #E8DEC8);
	border-radius: 16px;
	overflow: hidden;
	margin-bottom: 1.1rem;
	transition: box-shadow .15s;
}

.tk-card:hover { box-shadow: 0 4px 20px rgba(46,26,16,.07); }

/* ── TICKET HEAD ─────────────────────────────────────────────── */
.tk-card__head {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 1rem 1.25rem;
	border-bottom: 1px solid var(--color-border, #E8DEC8);
	flex-wrap: wrap;
	gap: .5rem;
}

.tk-card__ref {
	font-family: var(--font-serif, 'Playfair Display', serif);
	font-size: .95rem;
	font-weight: 700;
	color: var(--color-text, #2E1A10);
}

.tk-card__date {
	font-size: .75rem;
	color: var(--color-text-muted, #8C7B6A);
	margin-top: 2px;
}

.tk-card__head-right {
	display: flex;
	align-items: center;
	gap: .75rem;
}

/* ── STATUS BADGE ────────────────────────────────────────────── */
.tk-status {
	display: inline-flex;
	align-items: center;
	gap: 5px;
	padding: .3rem .85rem;
	border-radius: 999px;
	font-size: .72rem;
	font-weight: 700;
	letter-spacing: .03em;
}

.tk-status::before {
	content: '';
	width: 6px;
	height: 6px;
	border-radius: 50%;
	flex-shrink: 0;
}

.tk-status--attente   { background: #fff8e1; color: #7a5a00; }
.tk-status--attente::before   { background: #f0d080; }
.tk-status--payee     { background: #e8f5e9; color: #1b5e20; }
.tk-status--payee::before     { background: #66bb6a; }
.tk-status--expediee  { background: #e3f2fd; color: #0d47a1; }
.tk-status--expediee::before  { background: #42a5f5; }
.tk-status--terminee  { background: #ede7f6; color: #4527a0; }
.tk-status--terminee::before  { background: #7e57c2; }
.tk-status--annulee   { background: #ffebee; color: #b71c1c; }
.tk-status--annulee::before   { background: #ef5350; }
.tk-status--emportee  { background: #e8f5e9; color: #1b5e20; }
.tk-status--emportee::before  { background: #43a047; }

/* ── TICKET BODY ─────────────────────────────────────────────── */
.tk-card__body {
	padding: 1.1rem 1.25rem;
}

/* Bloc client admin ─ */
.tk-client-block {
	background: #faf6ee;
	border: 1px dashed var(--color-border, #E8DEC8);
	border-radius: 10px;
	padding: .75rem 1rem;
	margin-bottom: 1rem;
	display: grid;
	grid-template-columns: auto 1fr auto 1fr;
	gap: .35rem .75rem;
	align-items: center;
	font-size: .8rem;
}

.tk-client-block__label {
	color: var(--color-text-muted, #8C7B6A);
	font-size: .7rem;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: .08em;
	grid-column: span 4;
	margin-bottom: .1rem;
}

.tk-client-block__icon {
	color: var(--color-text-muted, #8C7B6A);
	font-size: .95rem;
}

.tk-client-block__val {
	color: var(--color-text, #2E1A10);
	font-weight: 500;
}

/* Mode livraison ─ */
.tk-delivery {
	display: inline-flex;
	align-items: center;
	gap: 5px;
	font-size: .77rem;
	color: var(--color-text-muted, #8C7B6A);
	padding: .3rem .75rem;
	background: #f5f0eb;
	border-radius: 999px;
	margin-bottom: .9rem;
}

/* Table articles ─ */
.tk-items {
	width: 100%;
	border-collapse: collapse;
	font-size: .82rem;
}

.tk-items thead tr {
	border-bottom: 1px solid var(--color-border, #E8DEC8);
}

.tk-items thead th {
	font-size: .7rem;
	text-transform: uppercase;
	letter-spacing: .08em;
	color: var(--color-text-muted, #8C7B6A);
	font-weight: 600;
	padding: .35rem .5rem .35rem 0;
	text-align: left;
}

.tk-items thead th:last-child { text-align: right; }

.tk-items tbody tr {
	border-bottom: 1px dashed #f0e8da;
}

.tk-items tbody tr:last-child { border-bottom: none; }

.tk-items tbody td {
	padding: .55rem .5rem .55rem 0;
	color: var(--color-text, #2E1A10);
	vertical-align: top;
}

.tk-items tbody td:last-child { text-align: right; font-weight: 700; white-space: nowrap; }

.tk-items__sub {
	display: block;
	font-size: .72rem;
	color: var(--color-text-muted, #8C7B6A);
	margin-top: 2px;
}

/* Totaux ─ */
.tk-totals {
	margin-top: .75rem;
	padding-top: .75rem;
	border-top: 1.5px solid var(--color-border, #E8DEC8);
}

.tk-totals__row {
	display: flex;
	justify-content: space-between;
	font-size: .82rem;
	padding: .2rem 0;
	color: var(--color-text-muted, #8C7B6A);
}

.tk-totals__row strong { color: var(--color-text, #2E1A10); }

.tk-totals__row--big {
	padding-top: .5rem;
	margin-top: .35rem;
	border-top: 1px dashed var(--color-border, #E8DEC8);
	font-size: 1rem;
	font-weight: 700;
	color: var(--color-text, #2E1A10);
}

.tk-totals__row--big span:last-child {
	color: var(--color-primary, #C0395A);
	font-family: var(--font-serif, 'Playfair Display', serif);
	font-size: 1.1rem;
}

/* Ref Monetico ─ */
.tk-monetico {
	display: flex;
	align-items: center;
	gap: 7px;
	margin-top: .85rem;
	padding: .55rem .85rem;
	background: #f0f7ff;
	border-radius: 8px;
	font-size: .75rem;
	color: #1565c0;
	border: 1px solid #bee3f8;
}

/* ── TICKET FOOTER ───────────────────────────────────────────── */
.tk-card__footer {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: .85rem 1.25rem;
	border-top: 1px dashed var(--color-border, #E8DEC8);
	background: #faf6ee;
	flex-wrap: wrap;
	gap: .5rem;
}

.tk-card__footer-ref {
	font-size: .68rem;
	color: var(--color-text-muted, #8C7B6A);
	font-family: 'Courier New', monospace;
}

/* ── ADMIN ACTIONS ───────────────────────────────────────────── */
.tk-admin-actions {
	display: flex;
	align-items: center;
	gap: .6rem;
	flex-wrap: wrap;
}

/* Checkbox générique ─ */
.tk-check-label {
	display: inline-flex;
	align-items: center;
	gap: 7px;
	cursor: pointer;
	font-size: .8rem;
	font-weight: 600;
	color: var(--color-text-muted, #8C7B6A);
	padding: .4rem .9rem;
	border-radius: 999px;
	border: 1.5px solid var(--color-border, #E8DEC8);
	background: #fff;
	transition: all .15s;
	user-select: none;
}

.tk-check-label:hover {
	border-color: #7e57c2;
	color: #4527a0;
	background: #f3eeff;
}

.tk-check-label.done {
	border-color: #7e57c2;
	color: #4527a0;
	background: #ede7f6;
}

.tk-check-label input[type="checkbox"] {
	accent-color: #7e57c2;
	width: 15px;
	height: 15px;
	cursor: pointer;
}

/* Case "Emporter" (click & collect) ─ */
.tk-check-label--emporter:hover {
	border-color: #2e7d32;
	color: #1b5e20;
	background: #f1f8e9;
}

.tk-check-label--emporter.done {
	border-color: #43a047;
	color: #1b5e20;
	background: #e8f5e9;
}

.tk-check-label--emporter input[type="checkbox"] {
	accent-color: #43a047;
}

/* Case "Expédier" (expédition) ─ */
.tk-check-label--expedier:hover {
	border-color: #1565c0;
	color: #0d47a1;
	background: #e3f2fd;
}

.tk-check-label--expedier.done {
	border-color: #42a5f5;
	color: #0d47a1;
	background: #e3f2fd;
}

.tk-check-label--expedier input[type="checkbox"] {
	accent-color: #42a5f5;
}

/* Séparateur visuel entre les deux cases ─ */
.tk-actions-sep {
	font-size: .75rem;
	color: var(--color-text-muted, #8C7B6A);
	padding: 0 .15rem;
}

/* Bouton supprimer (admin) ─ */
.tk-btn-delete {
	display: inline-flex;
	align-items: center;
	gap: 5px;
	padding: .4rem .9rem;
	border-radius: 999px;
	border: 1.5px solid #fca5a5;
	background: #fff;
	color: #b91c1c;
	font-size: .78rem;
	font-weight: 600;
	cursor: pointer;
	transition: all .15s;
}

.tk-btn-delete:hover { background: #ffebee; border-color: #ef5350; }

/* ── BOUTON PDF ──────────────────────────────────────────────── */
.tk-btn-print {
	display: inline-flex;
	align-items: center;
	gap: 5px;
	padding: .4rem .9rem;
	border-radius: 999px;
	border: 1.5px solid var(--color-border, #E8DEC8);
	background: #fff;
	color: var(--color-text, #2E1A10);
	font-size: .78rem;
	font-weight: 600;
	cursor: pointer;
	transition: all .15s;
}

.tk-btn-print:hover {
	background: #f5f0eb;
	border-color: #8C7B6A;
}

/* ── VIDE ────────────────────────────────────────────────────── */
.tk-empty {
	text-align: center;
	padding: 4rem 2rem;
	color: var(--color-text-muted, #8C7B6A);
}

.tk-empty__icon { font-size: 3rem; margin-bottom: 1rem; display: block; }
.tk-empty__title { font-size: 1rem; font-weight: 700; color: var(--color-text, #2E1A10); margin-bottom: .4rem; }
.tk-empty__sub { font-size: .82rem; margin-bottom: 1.5rem; }

.tk-btn-shop {
	display: inline-block;
	padding: .6rem 1.5rem;
	background: var(--color-primary, #C0395A);
	color: #fff;
	border-radius: 999px;
	text-decoration: none;
	font-weight: 700;
	font-size: .82rem;
}

/* ── ALERTE FLASH ────────────────────────────────────────────── */
.tk-alert {
	display: flex;
	align-items: center;
	gap: 8px;
	padding: .65rem 1rem;
	border-radius: 10px;
	font-size: .82rem;
	font-weight: 500;
	margin-bottom: 1.1rem;
}

.tk-alert--success { background: #d4edda; color: #1b5e20; }

/* ── RESPONSIVE ──────────────────────────────────────────────── */
@media (max-width: 720px) {
	.tk-page { grid-template-columns: 1fr; }
	.tk-sidebar { position: static; }
	.tk-client-block { grid-template-columns: auto 1fr; }
}

/* ── IMPRESSION PDF ──────────────────────────────────────────── */
@media print {
	/* Masquer toute la page */
	body * { visibility: hidden; }

	/* N'afficher que le ticket en cours d'impression */
	.tk-card--printing,
	.tk-card--printing * { visibility: visible; }

	.tk-card--printing {
		position: fixed !important;
		top: 0;
		left: 0;
		width: 100%;
		margin: 0 !important;
		border: none !important;
		border-radius: 0 !important;
		box-shadow: none !important;
	}

	/* Masquer les boutons d'action dans le footer */
	.tk-admin-actions,
	.tk-btn-print,
	.tk-btn-delete { display: none !important; }

	/* Fond clair pour l'impression */
	.tk-card__footer { background: #f9f9f9 !important; }
	.tk-card__body   { background: #fff !important; }
	.tk-card__head   { background: #fff !important; }
}
</style>

<div class="tk-page">

	{{-- ══ SIDEBAR ══ --}}
	<aside class="tk-sidebar">

		<p class="tk-sidebar__heading">Filtres</p>

		<nav class="tk-nav">
			<div class="tk-nav__item active" data-filter="toutes">
				<span class="tk-nav__dot" style="background:#8C7B6A;"></span>
				Toutes
			</div>
			<div class="tk-nav__item" data-filter="en_attente">
				<span class="tk-nav__dot" style="background:#f0d080;"></span>
				En attente
			</div>
			<div class="tk-nav__item" data-filter="payee">
				<span class="tk-nav__dot" style="background:#66bb6a;"></span>
				Payées
			</div>
			<div class="tk-nav__item" data-filter="expediee">
				<span class="tk-nav__dot" style="background:#42a5f5;"></span>
				Expédiées
			</div>
			<div class="tk-nav__item" data-filter="terminee">
				<span class="tk-nav__dot" style="background:#7e57c2;"></span>
				Terminées
			</div>
			<div class="tk-nav__item" data-filter="emportee">
				<span class="tk-nav__dot" style="background:#43a047;"></span>
				Emportées
			</div>
			<div class="tk-nav__item" data-filter="annulee">
				<span class="tk-nav__dot" style="background:#ef5350;"></span>
				Annulées
			</div>
		</nav>

		{{-- Résumé --}}
		<div class="tk-summary">
			<p class="tk-summary__title">Résumé</p>
			<div class="tk-summary__row">
				<span>Total commandes</span>
				<strong>{{ $commandes->count() }}</strong>
			</div>
			<div class="tk-summary__row">
				<span>Total TTC</span>
				<strong class="red">{{ number_format($commandes->sum('montant'), 2, ',', ' ') }} €</strong>
			</div>
			@if($isAdmin)
			<div class="tk-summary__row" style="margin-top:.5rem; padding-top:.5rem; border-top:1px dashed var(--color-border,#E8DEC8);">
				<span>En attente</span>
				<strong>{{ $commandes->where('statut','en_attente')->count() }}</strong>
			</div>
			<div class="tk-summary__row">
				<span>Payées</span>
				<strong>{{ $commandes->where('statut','payee')->count() }}</strong>
			</div>
			<div class="tk-summary__row">
				<span>Terminées</span>
				<strong>{{ $commandes->where('statut','terminee')->count() }}</strong>
			</div>
			<div class="tk-summary__row">
				<span>Expédiées</span>
				<strong>{{ $commandes->where('statut','expediee')->count() }}</strong>
			</div>
			<div class="tk-summary__row">
				<span>Emportées</span>
				<strong>{{ $commandes->where('statut','emportee')->count() }}</strong>
			</div>
			@endif
		</div>

	</aside>

	{{-- ══ CONTENU ══ --}}
	<main>

		<div class="tk-header">
			<h1 class="tk-title">
				{{ $isAdmin ? 'Toutes les commandes' : 'Mes commandes' }}
			</h1>
			<p class="tk-subtitle">
				@if($isAdmin)
					{{ $commandes->count() }} commande(s) au total — vue administrateur
				@else
					Retrouvez ici l'historique de vos commandes
				@endif
			</p>
		</div>

		{{-- Flash --}}
		@if(session('success'))
		<div class="tk-alert tk-alert--success">
			✅ {{ session('success') }}
		</div>
		@endif

		{{-- Filtres rapides --}}
		<div class="tk-filters">
			<button class="tk-filter-btn active" data-filter="toutes">Toutes</button>
			<button class="tk-filter-btn" data-filter="en_attente">En attente</button>
			<button class="tk-filter-btn" data-filter="payee">Payées</button>
			<button class="tk-filter-btn" data-filter="expediee">Expédiées</button>
			<button class="tk-filter-btn" data-filter="terminee">Terminées</button>
			<button class="tk-filter-btn" data-filter="emportee">Emportées</button>
			<button class="tk-filter-btn" data-filter="annulee">Annulées</button>
		</div>

		{{-- ── LISTE DES TICKETS ── --}}
		<div id="tk-wrap">

		@forelse($commandes as $commande)

		@php
			$statusMap = [
				'en_attente' => ['label' => 'En attente', 'class' => 'attente'],
				'payee'      => ['label' => 'Payée',      'class' => 'payee'],
				'expediee'   => ['label' => 'Expédiée',   'class' => 'expediee'],
				'terminee'   => ['label' => 'Terminée',   'class' => 'terminee'],
				'emportee'   => ['label' => 'Emportée',   'class' => 'emportee'],
				'annulee'    => ['label' => 'Annulée',    'class' => 'annulee'],
			];
			$s = $statusMap[$commande->statut] ?? ['label' => $commande->statut, 'class' => 'attente'];
		@endphp

		<div class="tk-card" data-statut="{{ $commande->statut }}">

			{{-- HEAD ── --}}
			<div class="tk-card__head">
				<div>
					<div class="tk-card__ref">
						🧾 {{ $commande->reference }}
					</div>
					<div class="tk-card__date">
						{{ $commande->created_at->translatedFormat('d M Y') }}
						à {{ $commande->created_at->format('H\hi') }}
						@if($isAdmin)
							— <strong style="color:var(--color-text,#2E1A10);">{{ $commande->user->name ?? '—' }}</strong>
						@endif
					</div>
				</div>
				<div class="tk-card__head-right">
					<span class="tk-status tk-status--{{ $s['class'] }}">
						{{ $s['label'] }}
					</span>
					<span style="font-size:.75rem; color:var(--color-text-muted,#8C7B6A);">
						{{ $commande->mode_livraison === 'expedition' ? '📦 Expédition' : '🚲 Click & Collect' }}
					</span>
				</div>
			</div>

			{{-- BODY ── --}}
			<div class="tk-card__body">

				{{-- Bloc client (admin seulement) --}}
				@if($isAdmin)
				<div class="tk-client-block">
					<span class="tk-client-block__label">Informations client</span>
					<span class="tk-client-block__icon">👤</span>
					<span class="tk-client-block__val">{{ $commande->user->name ?? '—' }}</span>
					<span class="tk-client-block__icon">✉️</span>
					<span class="tk-client-block__val">{{ $commande->user->email ?? '—' }}</span>
					@if(!empty($commande->user->phone))
						<span class="tk-client-block__icon">📞</span>
						<span class="tk-client-block__val">{{ $commande->user->phone }}</span>
					@endif
				</div>
				@endif

				{{-- Articles --}}
				<table class="tk-items">
					<thead>
						<tr>
							<th>Désignation</th>
							<th>Conditionnement</th>
							<th style="text-align:center">Qté</th>
							<th>Prix unit.</th>
							<th>Montant</th>
						</tr>
					</thead>
					<tbody>
						@forelse($commande->lignes as $ligne)
						<tr>
							<td>{{ $ligne->designation }}</td>
							<td>
								<span style="font-size:.75rem; color:var(--color-text-muted,#8C7B6A);">
									{{ $ligne->sous_designation ?? '—' }}
								</span>
							</td>
							<td style="text-align:center;">× {{ $ligne->quantite }}</td>
							<td>{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} €</td>
							<td>{{ number_format($ligne->prix_unitaire * $ligne->quantite, 2, ',', ' ') }} €</td>
						</tr>
						@empty
						<tr>
							<td colspan="5" style="color:var(--color-text-muted,#8C7B6A); font-style:italic;">
								Aucun article enregistré
							</td>
						</tr>
						@endforelse
					</tbody>
				</table>

				{{-- Totaux — TVA 10% --}}
				<div class="tk-totals">
					<div class="tk-totals__row">
						<span>Sous-total HT</span>
						<span><strong>{{ number_format($commande->montant / 1.1, 2, ',', ' ') }} €</strong></span>
					</div>
					<div class="tk-totals__row">
						<span>TVA (10%)</span>
						<span><strong>{{ number_format($commande->montant - ($commande->montant / 1.1), 2, ',', ' ') }} €</strong></span>
					</div>
					<div class="tk-totals__row">
						<span>Livraison</span>
						<span style="color:#1b5e20; font-weight:600;">
							{{ $commande->mode_livraison === 'expedition' ? 'Selon tarif Colissimo' : 'Gratuite' }}
						</span>
					</div>
					<div class="tk-totals__row tk-totals__row--big">
						<span>Total TTC</span>
						<span>{{ number_format($commande->montant, 2, ',', ' ') }} €</span>
					</div>
				</div>

				{{-- Référence Monetico (admin seulement) --}}
				@if($isAdmin && $commande->monetico_reference)
				<div class="tk-monetico">
					💳 Paiement validé — réf. Monetico :
					<strong>{{ $commande->monetico_reference }}</strong>
				</div>
				@endif

			</div>

			{{-- FOOTER ── --}}
			<div class="tk-card__footer">
				<span class="tk-card__footer-ref">{{ $commande->reference }}</span>

				@if($isAdmin)
				{{-- Actions admin --}}
				<div class="tk-admin-actions">

					{{-- ① Case "Terminée" (visible si pas encore terminée/expédiée/emportée) --}}
					@if(! in_array($commande->statut, ['terminee', 'expediee', 'emportee']))
					<form action="{{ route('ticket.terminer', $commande->id_commande) }}" method="POST">
						@csrf
						@method('PATCH')
						<label class="tk-check-label">
							<input
								type="checkbox"
								onchange="this.form.submit()"
								title="Marquer comme terminée"
							>
							Marquer terminée
						</label>
					</form>

					{{-- ② Case de finalisation (visible uniquement quand statut = terminee) --}}
					@elseif($commande->statut === 'terminee')

					{{-- Case "Terminée" verrouillée --}}
					<label class="tk-check-label done" style="cursor:default;">
						<input type="checkbox" checked disabled>
						Terminée ✓
					</label>

					<span class="tk-actions-sep">→</span>

					{{-- Case conditionnelle selon mode de livraison --}}
					<form action="{{ route('ticket.finaliser', $commande->id_commande) }}" method="POST">
						@csrf
						@method('PATCH')
						@if($commande->mode_livraison === 'expedition')
						{{-- Commande en expédition → "Expédier" --}}
						<label class="tk-check-label tk-check-label--expedier">
							<input
								type="checkbox"
								onchange="this.form.submit()"
								title="Marquer comme expédiée"
							>
							📦 Expédier
						</label>
						@else
						{{-- Commande click & collect → "Emporter" --}}
						<label class="tk-check-label tk-check-label--emporter">
							<input
								type="checkbox"
								onchange="this.form.submit()"
								title="Marquer comme emportée"
							>
							🚲 Emporter
						</label>
						@endif
					</form>

					{{-- ③ Statut final atteint (expédiée ou emportée) --}}
					@elseif($commande->statut === 'expediee')
					<label class="tk-check-label tk-check-label--expedier done" style="cursor:default;">
						<input type="checkbox" checked disabled>
						📦 Expédiée ✓
					</label>

					@elseif($commande->statut === 'emportee')
					<label class="tk-check-label tk-check-label--emporter done" style="cursor:default;">
						<input type="checkbox" checked disabled>
						🚲 Emportée ✓
					</label>
					@endif

					{{-- Supprimer --}}
					<form
						action="{{ route('ticket.destroy', $commande->id_commande) }}"
						method="POST"
						onsubmit="return confirm('Supprimer définitivement cette commande ?')"
					>
						@csrf
						@method('DELETE')
						<button type="submit" class="tk-btn-delete">
							🗑 Supprimer
						</button>
					</form>

				</div>
				@else
				{{-- Client : lecture seule --}}
				<span style="font-size:.72rem; color:var(--color-text-muted,#8C7B6A);">
					Commande non modifiable
				</span>
				@endif

				{{-- ── BOUTON PDF (visible par tous) ── --}}
				<button
					class="tk-btn-print"
					onclick="printTicket(this)"
					title="Enregistrer en PDF"
				>
					🖨️ PDF
				</button>

				<span class="tk-card__footer-ref">{{ $commande->reference }}</span>

			</div>

		</div>{{-- /tk-card --}}

		@empty

		<div class="tk-empty">
			<span class="tk-empty__icon">🧾</span>
			<p class="tk-empty__title">Aucune commande pour l'instant</p>
			<p class="tk-empty__sub">Passez votre première commande dans notre boutique !</p>
			<a href="{{ route('shop.index', 1) }}" class="tk-btn-shop">
				Aller à la boutique →
			</a>
		</div>

		@endforelse

		{{-- Message filtre vide (injecté par JS) --}}
		<div id="tk-filter-empty" style="display:none;" class="tk-empty">
			<span class="tk-empty__icon">🔍</span>
			<p class="tk-empty__title">Aucune commande dans cette catégorie</p>
		</div>

		</div>{{-- /tk-wrap --}}

	</main>

</div>{{-- /tk-page --}}

<script>
// ── Filtres ───────────────────────────────────────────────────
const filterBtns = document.querySelectorAll('.tk-filter-btn');
const navItems   = document.querySelectorAll('.tk-nav__item[data-filter]');
const cards      = document.querySelectorAll('.tk-card[data-statut]');
const emptyMsg   = document.getElementById('tk-filter-empty');

function applyFilter(filter) {
	let visible = 0;

	cards.forEach(card => {
		const show = filter === 'toutes' || card.dataset.statut === filter;
		card.style.display = show ? '' : 'none';
		if (show) visible++;
	});

	emptyMsg.style.display = visible === 0 ? 'block' : 'none';

	filterBtns.forEach(b => b.classList.toggle('active', b.dataset.filter === filter));
	navItems.forEach(i   => i.classList.toggle('active', i.dataset.filter === filter));
}

filterBtns.forEach(btn => btn.addEventListener('click', () => applyFilter(btn.dataset.filter)));
navItems.forEach(item   => item.addEventListener('click', () => applyFilter(item.dataset.filter)));

// ── Impression PDF ────────────────────────────────────────────
function printTicket(btn) {
	const card = btn.closest('.tk-card');

	// Ajoute la classe qui isole ce ticket dans le @media print
	card.classList.add('tk-card--printing');

	// Lance l'impression
	window.print();

	// Retire la classe après fermeture de la boîte d'impression
	card.classList.remove('tk-card--printing');
}
</script>

@include('templet.footer')
