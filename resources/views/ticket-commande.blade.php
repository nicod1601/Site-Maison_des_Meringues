@php
	use Illuminate\Support\Facades\Auth;
	$user    = Auth::user();
	$isAdmin = $user->isAdmin();
@endphp

@include('templet.header', [
	'titre' => $isAdmin ? 'Gestion des commandes' : 'Mes commandes',
	'title' => 'Tickets'
])

@once
@php
if (!function_exists('gicon')) {
	function gicon($name, $class = '') {
		$icons = [
			'receipt'   => '<path d="M4 3h16v18l-3-2-3 2-3-2-3 2-3-2-1 1Z"/><path d="M8 8h8M8 12h8M8 16h5"/>',
			'coin'      => '<circle cx="12" cy="12" r="9"/><path d="M9 9.5c0-1.1 1.2-2 3-2s3 .9 3 2-1.2 1.5-3 1.5-3 .6-3 1.7 1.2 2 3 2 3-.9 3-2"/><path d="M12 6.5v11"/>',
			'package'   => '<path d="M21 8 12 3 3 8v8l9 5 9-5Z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/>',
			'bag'       => '<path d="M6 7h12l1 13a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2Z"/><path d="M9 7V5a3 3 0 0 1 6 0v2"/>',
			'user'      => '<circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>',
			'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
			'phone'     => '<path d="M15.05 5A6 6 0 0 1 19 8.95M15.05 1A10 10 0 0 1 23 8.94M3.6 8.7a15.1 15.1 0 0 0 6.6 6.7l1.8-2.1a2 2 0 0 1 2.1-.5c1 .3 2.1.5 3.2.5a2 2 0 0 1 2 2v2.8a2 2 0 0 1-2.2 2A18.6 18.6 0 0 1 2 4.2 2 2 0 0 1 4 2h2.8a2 2 0 0 1 2 2c0 1.1.2 2.2.5 3.2a2 2 0 0 1-.5 2.1Z"/>',
			'home'      => '<path d="m3 10 9-7 9 7"/><path d="M5 9v11h14V9"/><path d="M9 20v-6h6v6"/>',
			'card'      => '<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/>',
			'trash'     => '<path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/>',
			'printer'   => '<path d="M6 9V3h12v6"/><rect x="4" y="9" width="16" height="8" rx="2"/><path d="M6 17h12v5H6z"/>',
			'search'    => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/>',
			'check'     => '<path d="M20 6 9 17l-5-5"/>',
			'chevron-r' => '<path d="M9 18l6-6-6-6"/>',
			'copy'      => '<rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/>',
			'inbox'     => '<path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11Z"/>',
			'filter-x'  => '<path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3Z"/><path d="m17 17 5 5M22 17l-5 5"/>',
			'sparkle'   => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4"/><circle cx="12" cy="12" r="3"/>',
			'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/>',
			'x-circle'  => '<circle cx="12" cy="12" r="9"/><path d="m9 9 6 6M15 9l-6 6"/>',
		];
		$path = $icons[$name] ?? '';
		return '<svg class="icon '.$class.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$path.'</svg>';
	}
}
@endphp
@endonce

<style>
/* ── BASE ───────────────────────────────────────────────────── */
.tk-page {
	max-width: 1120px;
	margin: 2.25rem auto;
	padding: 0 1.25rem 5rem;
	display: grid;
	grid-template-columns: 214px 1fr;
	gap: 2rem;
	align-items: start;
	font-family: 'DM Sans', sans-serif;
}

.icon { width: 1em; height: 1em; flex-shrink: 0; vertical-align: -0.15em; }

/* ── SIDEBAR ────────────────────────────────────────────────── */
.tk-sidebar { position: sticky; top: 90px; }

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
}

.tk-nav__item {
	display: flex;
	align-items: center;
	gap: 8px;
	padding: .7rem 1rem;
	font-size: .82rem;
	font-weight: 500;
	color: var(--color-text-muted, #8C7B6A);
	cursor: pointer;
	border-left: 2.5px solid transparent;
	border-bottom: 1px solid var(--color-border, #E8DEC8);
	transition: background .15s, color .15s, border-color .15s;
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

.tk-nav__icon {
	width: 26px;
	height: 26px;
	border-radius: 8px;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
	transition: transform .15s;
}
.tk-nav__icon .icon { width: 14px; height: 14px; }
.tk-nav__item:hover .tk-nav__icon { transform: scale(1.08); }
.tk-nav__label { flex: 1; }

.tk-nav__count {
	font-size: .7rem;
	font-weight: 700;
	color: var(--color-text-muted, #8C7B6A);
	background: #f5f0eb;
	border-radius: 999px;
	min-width: 20px;
	height: 20px;
	padding: 0 6px;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	transition: background .15s, color .15s;
}

.tk-nav__item.active .tk-nav__count { color: #fff; background: var(--color-primary, #C0395A); }

.tk-sidebar__help {
	margin-top: 1.25rem;
	padding: .9rem 1rem;
	background: #faf6ee;
	border: 1px dashed var(--color-border, #E8DEC8);
	border-radius: 14px;
	font-size: .76rem;
	color: var(--color-text-muted, #8C7B6A);
	line-height: 1.5;
}
.tk-sidebar__help strong { color: var(--color-text, #2E1A10); }

/* ── CONTENU PRINCIPAL ──────────────────────────────────────── */
.tk-stats {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: .85rem;
	margin-bottom: 1.5rem;
}
.tk-stats--3 { grid-template-columns: repeat(3, 1fr); }

.tk-stat-card {
	background: #fff;
	border: 1px solid var(--color-border, #E8DEC8);
	border-radius: 14px;
	padding: 1rem 1.15rem;
	display: flex;
	align-items: center;
	gap: .85rem;
	transition: box-shadow .15s, transform .15s;
}
.tk-stat-card:hover { box-shadow: 0 8px 22px rgba(46,26,16,.08); transform: translateY(-1px); }

.tk-stat-card__icon {
	width: 42px;
	height: 42px;
	border-radius: 11px;
	background: linear-gradient(135deg, #fdf5f7, #fbe4ea);
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 1.15rem;
	color: var(--color-primary, #C0395A);
	flex-shrink: 0;
}
.tk-stat-card__icon .icon { width: 19px; height: 19px; }
.tk-stat-card__icon--money { background: linear-gradient(135deg, #f0f7ee, #dcf0d6); color: #1b7a52; }
.tk-stat-card__icon--wait  { background: linear-gradient(135deg, #fff8e1, #fbebb8); color: #8a6400; }

.tk-stat-card__label {
	font-size: .68rem;
	text-transform: uppercase;
	letter-spacing: .08em;
	color: var(--color-text-muted, #8C7B6A);
	font-weight: 600;
	margin-bottom: 2px;
}

.tk-stat-card__value {
	font-family: var(--font-serif, 'Playfair Display', serif);
	font-size: 1.35rem;
	font-weight: 700;
	color: var(--color-text, #2E1A10);
	line-height: 1.1;
}
.tk-stat-card__value--accent { color: var(--color-primary, #C0395A); }

.tk-header {
	display: flex;
	align-items: baseline;
	justify-content: space-between;
	gap: 1rem;
	margin-bottom: 1.1rem;
	flex-wrap: wrap;
}

.tk-title {
	font-family: var(--font-serif, 'Playfair Display', serif);
	font-size: 1.2rem;
	font-weight: 700;
	color: var(--color-text, #2E1A10);
	margin: 0;
}

.tk-subtitle { font-size: .78rem; color: var(--color-text-muted, #8C7B6A); }

/* ── RECHERCHE ──────────────────────────────────────────────── */
.tk-search {
	position: relative;
	margin-bottom: 1.1rem;
}

.tk-search .icon {
	position: absolute;
	left: 13px;
	top: 50%;
	transform: translateY(-50%);
	color: var(--color-text-muted, #8C7B6A);
	width: 15px;
	height: 15px;
	pointer-events: none;
}

.tk-search input {
	width: 100%;
	padding: .65rem .9rem .65rem 38px;
	border-radius: 999px;
	border: 1.5px solid var(--color-border, #E8DEC8);
	background: #fff;
	font-size: .84rem;
	font-family: inherit;
	color: var(--color-text, #2E1A10);
	outline: none;
	transition: border-color .15s;
}
.tk-search input:focus { border-color: var(--color-primary, #C0395A); }
.tk-search input::placeholder { color: var(--color-text-muted, #8C7B6A); }

.tk-search__clear {
	position: absolute;
	right: 8px;
	top: 50%;
	transform: translateY(-50%);
	width: 24px;
	height: 24px;
	border-radius: 50%;
	border: none;
	background: #f5f0eb;
	color: var(--color-text-muted, #8C7B6A);
	display: none;
	align-items: center;
	justify-content: center;
	cursor: pointer;
}
.tk-search__clear .icon { position: static; transform: none; width: 12px; height: 12px; }
.tk-search__clear.show { display: flex; }

/* ── TICKET CARD ────────────────────────────────────────────── */
.tk-card {
	background: #fff;
	border: 1px solid var(--color-border, #E8DEC8);
	border-left: 4px solid var(--tk-accent, #ccc);
	border-radius: 16px;
	overflow: hidden;
	margin-bottom: 1.1rem;
	transition: box-shadow .15s, border-color .15s, transform .15s;
	animation: tk-fade-in .35s ease both;
}

.tk-card:hover { box-shadow: 0 10px 28px rgba(46,26,16,.10); border-color: #ddcda8; transform: translateY(-1px); }

@keyframes tk-fade-in {
	from { opacity: 0; transform: translateY(8px); }
	to   { opacity: 1; transform: translateY(0); }
}

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
	display: inline-flex;
	align-items: center;
	gap: 7px;
	font-family: var(--font-serif, 'Playfair Display', serif);
	font-size: .95rem;
	font-weight: 700;
	color: var(--color-text, #2E1A10);
	background: none;
	border: none;
	padding: 0;
	cursor: pointer;
}
.tk-card__ref .icon { color: var(--color-text-muted, #8C7B6A); width: 15px; height: 15px; }
.tk-card__ref:hover .tk-card__copy-icon { color: var(--color-primary, #C0395A); }
.tk-card__copy-icon { width: 13px !important; height: 13px !important; color: #c9bda3; transition: color .15s; }

.tk-card__date {
	font-size: .75rem;
	color: var(--color-text-muted, #8C7B6A);
	margin-top: 2px;
}

.tk-card__head-right { display: flex; align-items: center; gap: .75rem; }

.tk-delivery-tag {
	display: inline-flex;
	align-items: center;
	gap: 5px;
	font-size: .75rem;
	color: var(--color-text-muted, #8C7B6A);
}
.tk-delivery-tag .icon { width: 13px; height: 13px; }

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
.tk-card__body { padding: 1.1rem 1.25rem; }

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

.tk-client-block__icon { color: var(--color-text-muted, #8C7B6A); width: 15px; height: 15px; }
.tk-client-block__val { color: var(--color-text, #2E1A10); font-weight: 500; }

/* Mode livraison ─ */
.tk-delivery {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	font-size: .77rem;
	color: var(--color-text-muted, #8C7B6A);
	padding: .3rem .75rem;
	background: #f5f0eb;
	border-radius: 999px;
	margin-bottom: .9rem;
}
.tk-delivery .icon { width: 13px; height: 13px; }

/* Table articles ─ */
.tk-items { width: 100%; border-collapse: collapse; font-size: .82rem; }
.tk-items thead tr { border-bottom: 1px solid var(--color-border, #E8DEC8); }

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

.tk-items tbody tr { border-bottom: 1px dashed #f0e8da; }
.tk-items tbody tr:last-child { border-bottom: none; }
.tk-items tbody td { padding: .55rem .5rem .55rem 0; color: var(--color-text, #2E1A10); vertical-align: top; }
.tk-items tbody td:last-child { text-align: right; font-weight: 700; white-space: nowrap; }

/* Totaux ─ */
.tk-totals { margin-top: .75rem; padding-top: .75rem; border-top: 1.5px solid var(--color-border, #E8DEC8); }

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
.tk-monetico .icon { width: 14px; height: 14px; }

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
.tk-admin-actions { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; }

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
.tk-check-label .icon { width: 14px; height: 14px; }

.tk-check-label:hover { border-color: #7e57c2; color: #4527a0; background: #f3eeff; }
.tk-check-label.done  { border-color: #7e57c2; color: #4527a0; background: #ede7f6; }
.tk-check-label input[type="checkbox"] { accent-color: #7e57c2; width: 15px; height: 15px; cursor: pointer; }

.tk-check-label--emporter:hover { border-color: #2e7d32; color: #1b5e20; background: #f1f8e9; }
.tk-check-label--emporter.done  { border-color: #43a047; color: #1b5e20; background: #e8f5e9; }
.tk-check-label--emporter input[type="checkbox"] { accent-color: #43a047; }

.tk-check-label--expedier:hover { border-color: #1565c0; color: #0d47a1; background: #e3f2fd; }
.tk-check-label--expedier.done  { border-color: #42a5f5; color: #0d47a1; background: #e3f2fd; }
.tk-check-label--expedier input[type="checkbox"] { accent-color: #42a5f5; }

.tk-actions-sep { color: var(--color-text-muted, #8C7B6A); }
.tk-actions-sep .icon { width: 13px; height: 13px; }

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
.tk-btn-delete .icon { width: 14px; height: 14px; }
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
.tk-btn-print .icon { width: 14px; height: 14px; }
.tk-btn-print:hover { background: #f5f0eb; border-color: #8C7B6A; }

/* ── VIDE ────────────────────────────────────────────────────── */
.tk-empty {
	text-align: center;
	padding: 3.5rem 2rem;
	background: #fff;
	border: 1px dashed var(--color-border, #E8DEC8);
	border-radius: 16px;
	color: var(--color-text-muted, #8C7B6A);
}

.tk-empty__icon {
	width: 56px;
	height: 56px;
	margin: 0 auto 1rem;
	border-radius: 50%;
	background: #fdf5f7;
	display: flex;
	align-items: center;
	justify-content: center;
	color: var(--color-text-disabled, #c9bda3);
}
.tk-empty__icon .icon { width: 26px; height: 26px; }

.tk-empty__title { font-size: 1.05rem; font-weight: 700; color: var(--color-text, #2E1A10); margin-bottom: .35rem; font-family: var(--font-serif, 'Playfair Display', serif); }
.tk-empty__sub { font-size: .82rem; margin-bottom: 1.5rem; }

.tk-btn-shop {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: .65rem 1.6rem;
	background: var(--color-primary, #C0395A);
	color: #fff;
	border-radius: 999px;
	text-decoration: none;
	font-weight: 700;
	font-size: .82rem;
	transition: filter .15s, transform .15s;
}
.tk-btn-shop:hover { filter: brightness(1.06); transform: translateY(-1px); }
.tk-btn-shop .icon { width: 14px; height: 14px; }

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
.tk-alert .icon { width: 15px; height: 15px; }
.tk-alert--success { background: #d4edda; color: #1b5e20; }

/* Toast copié */
.tk-copy-toast {
	position: fixed;
	bottom: 24px;
	left: 50%;
	transform: translateX(-50%) translateY(10px);
	background: #2E1A10;
	color: #fff;
	padding: .55rem 1.1rem;
	border-radius: 999px;
	font-size: .8rem;
	font-weight: 600;
	display: flex;
	align-items: center;
	gap: 7px;
	opacity: 0;
	pointer-events: none;
	transition: opacity .2s, transform .2s;
	z-index: 100;
}
.tk-copy-toast .icon { width: 14px; height: 14px; color: #7ee19a; }
.tk-copy-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

/* ── RESPONSIVE ──────────────────────────────────────────────── */
@media (max-width: 720px) {
	.tk-page { grid-template-columns: 1fr; }
	.tk-sidebar { position: static; }
	.tk-nav { display: flex; overflow-x: auto; border-radius: 999px; -webkit-overflow-scrolling: touch; }
	.tk-nav__item { border-bottom: none; border-right: 1px solid var(--color-border, #E8DEC8); white-space: nowrap; }
	.tk-nav__item.active { border-left-color: transparent; border-bottom: 2.5px solid var(--color-primary, #C0395A); }
	.tk-nav__item:last-child { border-right: none; }
	.tk-sidebar__help { display: none; }
	.tk-stats { grid-template-columns: 1fr; }
	.tk-client-block { grid-template-columns: auto 1fr; }
}

/* ── IMPRESSION PDF ──────────────────────────────────────────── */
@media print {
	body * { visibility: hidden; }
	.tk-card--printing, .tk-card--printing * { visibility: visible; }
	.tk-card--printing {
		position: fixed !important;
		top: 0; left: 0; width: 100%;
		margin: 0 !important;
		border: none !important;
		border-radius: 0 !important;
		box-shadow: none !important;
	}
	.tk-admin-actions, .tk-btn-print, .tk-btn-delete { display: none !important; }
	.tk-card__footer { background: #f9f9f9 !important; }
	.tk-card__body   { background: #fff !important; }
	.tk-card__head   { background: #fff !important; }
}
/* ── ÉCRAN DE CHARGEMENT ─────────────────────────────────────── */
#tk-loading-overlay {
	position: fixed;
	inset: 0;
	background: rgba(46, 26, 16, 0.5);
	z-index: 999;
	display: none;
	align-items: center;
	justify-content: center;
	backdrop-filter: blur(6px);
}
#tk-loading-overlay.visible { display: flex; }

.tk-loading-box {
	background: #fff;
	border: 1px solid var(--color-border, #E8DEC8);
	border-radius: 20px;
	padding: 2.2rem 2.6rem;
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 1.1rem;
	box-shadow: 0 20px 60px rgba(192,57,90,.16), 0 8px 32px rgba(46,26,16,.18);
	text-align: center;
	min-width: 220px;
	animation: tk-loading-in 220ms cubic-bezier(.22,1,.36,1) both;
}
@keyframes tk-loading-in {
	from { opacity: 0; transform: translateY(6px) scale(.97); }
	to   { opacity: 1; transform: translateY(0) scale(1); }
}
.tk-loading-box p {
	font-size: .85rem;
	color: var(--color-text, #2E1A10);
	margin: 0;
	font-weight: 700;
}

.tk-meringue {
	position: relative;
	width: 50px;
	height: 50px;
	border-radius: 50%;
	background:
		radial-gradient(circle at 32% 28%, rgba(255,255,255,.6), rgba(255,255,255,0) 42%),
		conic-gradient(from -20deg,
			#F3AFAE 0deg,   #E2572B 26deg,
			#F6CBC7 52deg,  #D9481F 78deg,
			#F3AFAE 104deg, #E2572B 130deg,
			#F6CBC7 156deg, #D9481F 182deg,
			#F3AFAE 208deg, #E2572B 234deg,
			#F6CBC7 260deg, #D9481F 286deg,
			#F3AFAE 312deg, #E2572B 338deg,
			#F3AFAE 360deg
		);
	box-shadow: 0 8px 18px rgba(192,57,90,.28), inset 0 0 0 1px rgba(255,255,255,.35);
	animation: tk-meringue-spin 1.6s linear infinite;
}
.tk-meringue::after {
	content: '';
	position: absolute;
	top: 50%; left: 50%;
	width: 10px; height: 10px;
	border-radius: 50%;
	transform: translate(-50%, -50%);
	background: radial-gradient(circle at 34% 28%, #fff, #D9D0BC 55%, #A69A80 100%);
	box-shadow: 0 1px 3px rgba(26,20,16,.35);
}
@keyframes tk-meringue-spin { to { transform: rotate(360deg); } }

</style>



@php
	$statusMap = [
		'en_attente' => ['label' => 'En attente', 'class' => 'attente',  'dot' => '#f0d080', 'icon' => 'clock'],
		'payee'      => ['label' => 'Payée',      'class' => 'payee',    'dot' => '#66bb6a', 'icon' => 'check'],
		'expediee'   => ['label' => 'Expédiée',   'class' => 'expediee', 'dot' => '#42a5f5', 'icon' => 'package'],
		'terminee'   => ['label' => 'Terminée',   'class' => 'terminee', 'dot' => '#7e57c2', 'icon' => 'sparkle'],
		'emportee'   => ['label' => 'Emportée',   'class' => 'emportee', 'dot' => '#43a047', 'icon' => 'bag'],
		'annulee'    => ['label' => 'Annulée',    'class' => 'annulee',  'dot' => '#ef5350', 'icon' => 'x-circle'],
	];
@endphp

<div class="tk-page">

	{{-- ══ SIDEBAR — filtre unique ══ --}}
	<aside class="tk-sidebar">

		<p class="tk-sidebar__heading">Filtrer</p>

		<nav class="tk-nav">
			<div class="tk-nav__item active" data-filter="toutes">
				<span class="tk-nav__icon" style="background:#8C7B6A1a; color:#8C7B6A;">{!! gicon('inbox') !!}</span>
				<span class="tk-nav__label">Toutes</span>
				<span class="tk-nav__count">{{ $commandes->count() }}</span>
			</div>
			@foreach($statusMap as $key => $s)
			<div class="tk-nav__item" data-filter="{{ $key }}">
				<span class="tk-nav__icon" style="background:{{ $s['dot'] }}1a; color:{{ $s['dot'] }};">{!! gicon($s['icon']) !!}</span>
				<span class="tk-nav__label">{{ $s['label'] }}s</span>
				<span class="tk-nav__count">{{ $commandes->where('statut', $key)->count() }}</span>
			</div>
			@endforeach
		</nav>

		@unless($isAdmin)
		<div class="tk-sidebar__help">
			Une question sur une commande ? <strong>Contactez-nous</strong>, référence en main — on vous répond rapidement.
		</div>
		@endunless

	</aside>

	{{-- ══ CONTENU ══ --}}
	<main>

		{{-- Cartes de synthèse --}}
		<div class="tk-stats {{ $isAdmin ? 'tk-stats--3' : '' }}">
			<div class="tk-stat-card">
				<span class="tk-stat-card__icon">{!! gicon('receipt') !!}</span>
				<div>
					<div class="tk-stat-card__label">Commandes</div>
					<div class="tk-stat-card__value">{{ $commandes->count() }}</div>
				</div>
			</div>
			<div class="tk-stat-card">
				<span class="tk-stat-card__icon tk-stat-card__icon--money">{!! gicon('coin') !!}</span>
				<div>
					<div class="tk-stat-card__label">Total TTC</div>
					<div class="tk-stat-card__value tk-stat-card__value--accent">{{ number_format($commandes->sum('montant'), 2, ',', ' ') }} €</div>
				</div>
			</div>
			@if($isAdmin)
			<div class="tk-stat-card">
				<span class="tk-stat-card__icon tk-stat-card__icon--wait">{!! gicon('clock') !!}</span>
				<div>
					<div class="tk-stat-card__label">En attente</div>
					<div class="tk-stat-card__value">{{ $commandes->where('statut', 'en_attente')->count() }}</div>
				</div>
			</div>
			@endif
		</div>

		<div class="tk-header">
			<div>
				<h1 class="tk-title">
					{{ $isAdmin ? 'Toutes les commandes' : 'Historique de commandes' }}
				</h1>
				@if($isAdmin)
				<p class="tk-subtitle">Vue administrateur — filtrez par statut ou recherchez ci-dessous</p>
				@endif
			</div>
		</div>

		{{-- Flash --}}
		@if(session('success'))
		<div class="tk-alert tk-alert--success">
			{!! gicon('check') !!} {{ session('success') }}
		</div>
		@endif

		{{-- Recherche live --}}
		<div class="tk-search">
			{!! gicon('search') !!}
			<input
				type="search"
				id="tk-search-input"
				placeholder="{{ $isAdmin ? 'Rechercher une référence ou un client…' : 'Rechercher une référence…' }}"
				autocomplete="off"
			>
			<button type="button" class="tk-search__clear" id="tk-search-clear" title="Effacer la recherche">
				{!! gicon('filter-x') !!}
			</button>
		</div>

		{{-- ── LISTE DES TICKETS ── --}}
		<div id="tk-wrap">

		@forelse($commandes as $commande)

		@php
			$s = $statusMap[$commande->statut] ?? ['label' => $commande->statut, 'class' => 'attente', 'dot' => '#ccc', 'icon' => 'receipt'];
		@endphp

		<div
			class="tk-card"
			data-statut="{{ $commande->statut }}"
			data-search="{{ strtolower($commande->reference . ' ' . ($isAdmin ? ($commande->user->name ?? '') : '')) }}"
			style="--tk-accent: {{ $s['dot'] }};"
		>

			{{-- HEAD ── --}}
			<div class="tk-card__head">
				<div>
					<button type="button" class="tk-card__ref" onclick="copyRef(this, '{{ $commande->reference }}')" title="Copier la référence">
						{!! gicon('receipt') !!}
						{{ $commande->reference }}
						{!! gicon('copy', 'tk-card__copy-icon') !!}
					</button>
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
					<span class="tk-delivery-tag">
						@if($commande->mode_livraison === 'expedition')
							{!! gicon('package') !!} Expédition
						@else
							{!! gicon('bag') !!} Click & Collect
						@endif
					</span>
				</div>
			</div>

			{{-- BODY ── --}}
			<div class="tk-card__body">

				{{-- Bloc client (admin seulement) --}}
				@if($isAdmin)
				<div class="tk-client-block">
					<span class="tk-client-block__label">Informations client</span>
					<span class="tk-client-block__icon">{!! gicon('user') !!}</span>
					<span class="tk-client-block__val">{{ $commande->user->name ?? '—' }}</span>
					<span class="tk-client-block__icon">{!! gicon('mail') !!}</span>
					<span class="tk-client-block__val">{{ $commande->user->email ?? '—' }}</span>
					@if(!empty($commande->user->phone))
						<span class="tk-client-block__icon">{!! gicon('phone') !!}</span>
						<span class="tk-client-block__val">{{ $commande->user->phone }}</span>
					@endif

					@if(!empty($commande->user->adresse ) && !empty($commande->user->ville) && !empty($commande->user->code_postal))
						<span class="tk-client-block__icon">{!! gicon('home') !!}</span>
						<span class="tk-client-block__val">{{ ($commande->user->adresse.' '.
						$commande->user->ville .' '. $commande->user->code_postal)  ?? '—' }}</span>
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
					{!! gicon('card') !!} Paiement validé — réf. Monetico :
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
						{!! gicon('check') !!} Terminée
					</label>

					<span class="tk-actions-sep">{!! gicon('chevron-r') !!}</span>

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
							{!! gicon('package') !!} Expédier
						</label>
						@else
						{{-- Commande click & collect → "Emporter" --}}
						<label class="tk-check-label tk-check-label--emporter">
							<input
								type="checkbox"
								onchange="this.form.submit()"
								title="Marquer comme emportée"
							>
							{!! gicon('bag') !!} Emporter
						</label>
						@endif
					</form>

					{{-- ③ Statut final atteint (expédiée ou emportée) --}}
					@elseif($commande->statut === 'expediee')
					<label class="tk-check-label tk-check-label--expedier done" style="cursor:default;">
						<input type="checkbox" checked disabled>
						{!! gicon('package') !!} Expédiée
					</label>

					@elseif($commande->statut === 'emportee')
					<label class="tk-check-label tk-check-label--emporter done" style="cursor:default;">
						<input type="checkbox" checked disabled>
						{!! gicon('bag') !!} Emportée
					</label>
					@endif

					{{-- Supprimer --}}
					<form
						action="{{ route('ticket.destroy', $commande->id_commande) }}"
						method="POST"
						onsubmit="return tkConfirmSuppr()"
					>
						@csrf
						@method('DELETE')
						<button type="submit" class="tk-btn-delete">
							{!! gicon('trash') !!} Supprimer
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
					{!! gicon('printer') !!} PDF
				</button>

				<span class="tk-card__footer-ref">{{ $commande->reference }}</span>

			</div>

		</div>{{-- /tk-card --}}

		@empty

		<div class="tk-empty">
			<span class="tk-empty__icon">{!! gicon('inbox') !!}</span>
			<p class="tk-empty__title">Aucune commande pour l'instant</p>
			<p class="tk-empty__sub">
				{{ $isAdmin ? "Les commandes des clients apparaîtront ici dès qu'elles seront passées." : "Passez votre première commande dans notre boutique !" }}
			</p>
			@unless($isAdmin)
			<a href="{{ route('shop.index', 1) }}" class="tk-btn-shop">
				Aller à la boutique {!! gicon('chevron-r') !!}
			</a>
			@endunless
		</div>

		@endforelse

		{{-- Message filtre/recherche vide (injecté par JS) --}}
		<div id="tk-filter-empty" style="display:none;" class="tk-empty">
			<span class="tk-empty__icon">{!! gicon('search') !!}</span>
			<p class="tk-empty__title" id="tk-filter-empty-title">Aucune commande dans cette catégorie</p>
			<p class="tk-empty__sub">Essayez un autre filtre ou une autre recherche.</p>
		</div>

		</div>{{-- /tk-wrap --}}

	</main>

</div>{{-- /tk-page --}}

<div class="tk-copy-toast" id="tk-copy-toast">
	{!! gicon('check') !!} Référence copiée
</div>

<div id="tk-loading-overlay">
	<div class="tk-loading-box">
		<div class="tk-meringue"></div>
		<p>Mise à jour en cours…</p>
	</div>
</div>

<script>
// ── Filtres + recherche combinés (source unique : la barre latérale) ──
const navItems    = document.querySelectorAll('.tk-nav__item[data-filter]');
const cards        = document.querySelectorAll('.tk-card[data-statut]');
const emptyMsg      = document.getElementById('tk-filter-empty');
const emptyTitle    = document.getElementById('tk-filter-empty-title');
const searchInput   = document.getElementById('tk-search-input');
const searchClear   = document.getElementById('tk-search-clear');

let currentFilter = 'toutes';

function applyFilters() {
	const query = searchInput.value.trim().toLowerCase();
	let visible = 0;

	cards.forEach(card => {
		const matchesStatus = currentFilter === 'toutes' || card.dataset.statut === currentFilter;
		const matchesSearch = !query || card.dataset.search.includes(query);
		const show = matchesStatus && matchesSearch;
		card.style.display = show ? '' : 'none';
		if (show) visible++;
	});

	if (cards.length > 0 && visible === 0) {
		emptyMsg.style.display = 'block';
		emptyTitle.textContent = query ? 'Aucun résultat pour cette recherche' : 'Aucune commande dans cette catégorie';
	} else {
		emptyMsg.style.display = 'none';
	}

	searchClear.classList.toggle('show', query.length > 0);
}

navItems.forEach(item => item.addEventListener('click', () => {
	currentFilter = item.dataset.filter;
	navItems.forEach(i => i.classList.toggle('active', i === item));
	applyFilters();
}));

searchInput?.addEventListener('input', applyFilters);
searchClear?.addEventListener('click', () => {
	searchInput.value = '';
	applyFilters();
	searchInput.focus();
});

// ── Copier la référence ─────────────────────────────────────────
function copyRef(btn, ref) {
	navigator.clipboard?.writeText(ref).then(() => {
		const toast = document.getElementById('tk-copy-toast');
		toast.classList.add('show');
		clearTimeout(window.__tkCopyTimer);
		window.__tkCopyTimer = setTimeout(() => toast.classList.remove('show'), 1800);
	});
}

// ── Écran de chargement ──────────────────────────────────────
const tkLoader = document.getElementById('tk-loading-overlay');
function tkAfficherLoader() { tkLoader?.classList.add('visible'); }

function tkConfirmSuppr() {
	const ok = confirm('Supprimer définitivement cette commande ?');
	if (ok) tkAfficherLoader();
	return ok;
}

// Les cases "terminer / expédier / emporter" soumettent leur formulaire au clic :
// on affiche le loader dès qu'un de ces formulaires part (la suppression est gérée à part ci-dessus).
document.querySelectorAll('.tk-card form:not([onsubmit])').forEach(form => {
	form.addEventListener('submit', tkAfficherLoader);
});

// Impression PDF : on désactive le loader pour ne pas gêner window.print()
function printTicket(btn) {
	const card = btn.closest('.tk-card');
	card.classList.add('tk-card--printing');
	window.print();
	card.classList.remove('tk-card--printing');
}
</script>

@include('templet.footer')