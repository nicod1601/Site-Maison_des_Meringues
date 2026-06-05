@include('templet.header', ['titre' => 'Commande', 'title' => 'Checkout'])
@vite('resources/css/boutique.css')

@php
	$expeditionAutorisee = $panier->lignes->every(function ($ligne) {
		$formeCondi = $ligne->formeCondi;
		$nomForme   = strtolower($formeCondi->forme->nom_forme ?? '');
		$typeCondi  = strtolower($formeCondi->conditionnement->type ?? '');
		return str_contains($nomForme, 'mini') && $typeCondi === 'individuel';
	});

	$tauxTVA    = 0.10;
	$totalTTC   = $panier->total();
	$totalHT    = $totalTTC / (1 + $tauxTVA);
	$montantTVA = $totalTTC - $totalHT;
@endphp

{{-- ── ICÔNES SVG (sprite inline) ──────────────────────────────────────────── --}}
<svg xmlns="http://www.w3.org/2000/svg" style="display:none">
	{{-- shopping-cart --}}
	<symbol id="ico-cart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
		<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
	</symbol>
	{{-- truck --}}
	<symbol id="ico-truck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
		<circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
	</symbol>
	{{-- bike --}}
	<symbol id="ico-bike" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<circle cx="5.5" cy="17.5" r="3.5"/><circle cx="18.5" cy="17.5" r="3.5"/>
		<path d="M15 6a1 1 0 0 0-1-1h-1"/>
		<path d="m9 15 2-6 4 4 2-4"/>
		<path d="m9 15-3.5 2.5"/>
	</symbol>
	{{-- package --}}
	<symbol id="ico-package" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
		<polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
	</symbol>
	{{-- info --}}
	<symbol id="ico-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
	</symbol>
	{{-- lock --}}
	<symbol id="ico-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
		<path d="M7 11V7a5 5 0 0 1 10 0v4"/>
	</symbol>
	{{-- chevron-left --}}
	<symbol id="ico-chevron-left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<polyline points="15 18 9 12 15 6"/>
	</symbol>
	{{-- receipt / TVA --}}
	<symbol id="ico-receipt" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/>
		<path d="M16 8H8m8 4H8m5 4H8"/>
	</symbol>
</svg>

<style>
.ico {
	display: inline-block;
	width: 1em;
	height: 1em;
	vertical-align: middle;
	flex-shrink: 0;
}

/* ── RESET & BASE ──────────────────────────────────────────── */
.co-page {
	max-width: 1100px;
	margin: 2rem auto;
	padding: 0 1.25rem 4rem;
	display: grid;
	grid-template-columns: 1fr 360px;
	gap: 2rem;
	align-items: start;
	font-family: 'DM Sans', sans-serif;
}

/* ── SECTION TITLES ────────────────────────────────────────── */
.co-section-title {
	font-family: var(--font-serif, 'Playfair Display', serif);
	font-size: 1rem;
	font-weight: 600;
	color: var(--color-text, #2E1A10);
	margin: 0 0 1rem;
	padding-bottom: .6rem;
	border-bottom: 1.5px solid var(--color-border, #E8DEC8);
	display: flex;
	align-items: center;
	gap: .5rem;
}

.co-section-title .ico {
	width: 1.1rem;
	height: 1.1rem;
	color: var(--color-primary, #C0395A);
}

/* ── CARD CONTAINER ────────────────────────────────────────── */
.co-card {
	background: #fff;
	border: 1px solid var(--color-border, #E8DEC8);
	border-radius: 14px;
	padding: 1.5rem;
	margin-bottom: 1.25rem;
}

/* ── PANIER LINES ──────────────────────────────────────────── */
.co-line {
	display: grid;
	grid-template-columns: 68px 1fr auto;
	gap: 1rem;
	align-items: center;
	padding: .85rem 0;
	border-bottom: 1px dashed var(--color-border, #E8DEC8);
}

.co-line:last-child { border-bottom: none; }

.co-line__img {
	width: 68px;
	height: 68px;
	border-radius: 10px;
	overflow: hidden;
	background: #f5f0eb;
	flex-shrink: 0;
}

.co-line__img img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	display: block;
}

.co-line__name {
	font-family: var(--font-serif, 'Playfair Display', serif);
	font-size: .95rem;
	font-weight: 600;
	color: var(--color-text, #2E1A10);
	margin: 0 0 3px;
}

.co-line__sub {
	font-size: .78rem;
	color: var(--color-text-muted, #8C7B6A);
	margin: 0;
}

.co-line__qty {
	display: inline-block;
	margin-top: 5px;
	background: #f5f0eb;
	color: var(--color-text-muted, #8C7B6A);
	font-size: .72rem;
	font-weight: 600;
	padding: 2px 9px;
	border-radius: 999px;
}

.co-line__price {
	font-weight: 700;
	font-size: 1rem;
	color: var(--color-primary, #C0395A);
	white-space: nowrap;
	text-align: right;
}

.co-line__unit {
	font-size: .72rem;
	color: var(--color-text-muted, #8C7B6A);
	font-weight: 400;
}

/* ── MODE LIVRAISON ────────────────────────────────────────── */
.co-modes {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: .85rem;
}

.co-mode {
	border: 2px solid var(--color-border, #E8DEC8);
	border-radius: 12px;
	padding: 1rem 1.1rem;
	cursor: pointer;
	transition: border-color .15s, background .15s;
	background: #fff;
	position: relative;
}

.co-mode.active {
	border-color: var(--color-primary, #C0395A);
	background: #fdf5f7;
}

.co-mode.disabled {
	opacity: .5;
	cursor: not-allowed;
	background: #f9f6f1;
}

.co-mode__icon {
	font-size: 1.4rem;
	margin-bottom: .45rem;
	display: block;
	color: var(--color-text-muted, #8C7B6A);
}

.co-mode__icon .ico {
	width: 1.4rem;
	height: 1.4rem;
}

.co-mode.active .co-mode__icon { color: var(--color-primary, #C0395A); }

.co-mode__title {
	font-weight: 700;
	font-size: .9rem;
	color: var(--color-text, #2E1A10);
	margin: 0 0 4px;
	display: flex;
	align-items: center;
	gap: 6px;
}

.co-mode__desc {
	font-size: .75rem;
	color: var(--color-text-muted, #8C7B6A);
	margin: 0;
	line-height: 1.5;
}

.co-mode__badge {
	position: absolute;
	top: .65rem;
	right: .75rem;
	font-size: .65rem;
	font-weight: 700;
	padding: 2px 8px;
	border-radius: 999px;
	background: var(--color-primary, #C0395A);
	color: #fff;
}

.co-mode__badge--warn {
	background: #e5a000;
	color: #fff;
}

.co-mode__radio {
	width: 16px;
	height: 16px;
	border-radius: 50%;
	border: 2px solid var(--color-border, #E8DEC8);
	display: inline-block;
	flex-shrink: 0;
	position: relative;
	transition: border-color .15s;
}

.co-mode.active .co-mode__radio {
	border-color: var(--color-primary, #C0395A);
}

.co-mode.active .co-mode__radio::after {
	content: '';
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	width: 7px;
	height: 7px;
	border-radius: 50%;
	background: var(--color-primary, #C0395A);
}

/* ── STICKY RÉCAP ──────────────────────────────────────────── */
.co-sticky {
	position: sticky;
	top: 100px;
}

.co-recap {
	background: #fff;
	border: 1px solid var(--color-border, #E8DEC8);
	border-radius: 14px;
	padding: 1.5rem;
	overflow: hidden;
}

.co-recap__header {
	font-family: var(--font-serif, 'Playfair Display', serif);
	font-size: 1.1rem;
	font-weight: 700;
	color: var(--color-text, #2E1A10);
	margin: 0 0 1.25rem;
	padding-bottom: .75rem;
	border-bottom: 1.5px solid var(--color-border, #E8DEC8);
}

.co-recap__line {
	display: flex;
	justify-content: space-between;
	align-items: center;
	font-size: .85rem;
	padding: .4rem 0;
	color: var(--color-text-muted, #8C7B6A);
}

.co-recap__line strong {
	color: var(--color-text, #2E1A10);
	font-weight: 600;
}

.co-recap__line--tva {
	font-size: .78rem;
	color: var(--color-text-muted, #8C7B6A);
	font-style: italic;
}

.co-recap__divider {
	border: none;
	border-top: 1px dashed var(--color-border, #E8DEC8);
	margin: .75rem 0;
}

.co-recap__total {
	display: flex;
	justify-content: space-between;
	align-items: baseline;
	margin: .5rem 0 1.25rem;
}

.co-recap__total-label {
	font-family: var(--font-serif, 'Playfair Display', serif);
	font-size: 1rem;
	font-weight: 700;
	color: var(--color-text, #2E1A10);
}

.co-recap__total-amount {
	font-family: var(--font-serif, 'Playfair Display', serif);
	font-size: 1.7rem;
	font-weight: 700;
	color: var(--color-primary, #C0395A);
}

/* ── BOUTON PAYER ──────────────────────────────────────────── */
.co-pay-btn {
	display: block;
	width: 100%;
	padding: .95rem 1.5rem;
	background: var(--color-primary, #C0395A);
	color: #fff;
	border: none;
	border-radius: 10px;
	font-family: var(--font-serif, 'Playfair Display', serif);
	font-size: 1.05rem;
	font-weight: 700;
	cursor: pointer;
	transition: background .15s, transform .1s;
	text-align: center;
	letter-spacing: .01em;
}

.co-pay-btn:hover { background: #a32d4a; }
.co-pay-btn:active { transform: scale(.98); }

.co-pay-btn:disabled {
	background: var(--color-border, #E8DEC8);
	color: var(--color-text-muted, #8C7B6A);
	cursor: not-allowed;
}

/* ── SECURE BADGE ──────────────────────────────────────────── */
.co-secure {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
	margin-top: .85rem;
	font-size: .72rem;
	color: var(--color-text-muted, #8C7B6A);
}

.co-secure .ico {
	width: .85rem;
	height: .85rem;
	color: #2d6a4f;
}

/* ── NOTICE EXPÉDITION ─────────────────────────────────────── */
.co-notice {
	background: #fffbea;
	border: 1px solid #f0d080;
	border-radius: 8px;
	padding: .7rem .95rem;
	font-size: .78rem;
	color: #7a5a00;
	margin-top: .85rem;
	line-height: 1.55;
	display: flex;
	gap: .5rem;
	align-items: flex-start;
}

.co-notice .ico {
	width: 1rem;
	height: 1rem;
	flex-shrink: 0;
	margin-top: .1rem;
	color: #e5a000;
}

/* ── MOBILE ────────────────────────────────────────────────── */
@media (max-width: 760px) {
	.co-page {
		grid-template-columns: 1fr;
	}
	.co-sticky { position: static; }
	.co-modes  { grid-template-columns: 1fr; }
	.co-sticky { order: -1; }
}

/* ── MINI PRODUIT THUMBNAILS DANS LE RÉCAP ─────────────────── */
.co-recap__thumbs {
	display: flex;
	gap: 6px;
	flex-wrap: wrap;
	margin-bottom: 1rem;
	padding-bottom: 1rem;
	border-bottom: 1px dashed var(--color-border, #E8DEC8);
}

.co-recap__thumb {
	width: 42px;
	height: 42px;
	border-radius: 8px;
	overflow: hidden;
	background: #f5f0eb;
	border: 1px solid var(--color-border, #E8DEC8);
	flex-shrink: 0;
}

.co-recap__thumb img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	display: block;
}

/* ── LIEN RETOUR ───────────────────────────────────────────── */
.co-back {
	display: inline-flex;
	align-items: center;
	gap: 5px;
	font-size: .8rem;
	color: var(--color-text-muted, #8C7B6A);
	text-decoration: none;
	margin-bottom: 1.25rem;
	transition: color .15s;
}

.co-back .ico {
	width: .85rem;
	height: .85rem;
}

.co-back:hover { color: var(--color-primary, #C0395A); }
</style>

<main style="background: var(--color-cream, #FAF6EE); min-height: 60vh; padding: 1rem 0;">
<form id="form-checkout" action="{{ route('checkout.payer') }}" method="POST">
@csrf
<input type="hidden" name="mode_livraison" id="mode_livraison" value="livraison">

<div class="co-page">

	{{-- ══════════════════════════════════════════
		 COLONNE GAUCHE
	══════════════════════════════════════════ --}}
	<div>

		<a href="{{ route('panier.index') }}" class="co-back">
			<svg class="ico"><use href="#ico-chevron-left"/></svg>
			Retour au panier
		</a>

		{{-- ── ARTICLES ── --}}
		<div class="co-card">
			<h2 class="co-section-title">
				<svg class="ico"><use href="#ico-cart"/></svg>
				Articles ({{ $panier->lignes->count() }})
			</h2>

			@foreach($panier->lignes as $ligne)
			<div class="co-line">
				<div class="co-line__img">
					<img
						src="{{ asset($ligne->produit->image($ligne->id_forme_condi)) }}"
						alt="{{ $ligne->produit->forme->nom_forme }}"
						onerror="this.style.display='none'"
					>
				</div>
				<div>
					<p class="co-line__name">
						{{ $ligne->produit->forme->nom_forme }}
						—
						{{ $ligne->produit->parfum->nom_parfum }}
					</p>
					<p class="co-line__sub">{{ $ligne->formeCondi->conditionnement->type }}</p>
					<span class="co-line__qty">× {{ $ligne->quantite }}</span>
				</div>
				<div class="co-line__price">
					{{ number_format($ligne->prix_unitaire * $ligne->quantite, 2, ',', ' ') }} €
					<div class="co-line__unit">
						{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} € / unité
					</div>
				</div>
			</div>
			@endforeach
		</div>

		{{-- ── MODE DE LIVRAISON ── --}}
		<div class="co-card">
			<h2 class="co-section-title">
				<svg class="ico"><use href="#ico-truck"/></svg>
				Mode de livraison
			</h2>

			<div class="co-modes">

				{{-- Click & Collect — toujours dispo --}}
				<div class="co-mode active" id="mode-livraison" onclick="choisirMode('livraison')">
					<span class="co-mode__badge">Gratuit</span>
					<span class="co-mode__icon">
						<svg class="ico"><use href="#ico-bike"/></svg>
					</span>
					<p class="co-mode__title">
						<span class="co-mode__radio"></span>
						Click & Collect
					</p>
					<p class="co-mode__desc">
						Retrait en main propre ou livraison locale.<br>
						Toujours disponible.
					</p>
				</div>

				{{-- Expédition --}}
				<div class="co-mode {{ $expeditionAutorisee ? '' : 'disabled' }}"
					 id="mode-expedition"
					 @if($expeditionAutorisee) onclick="choisirMode('expedition')" @endif>

					@if($expeditionAutorisee)
						<span class="co-mode__badge">Colissimo</span>
					@else
						<span class="co-mode__badge co-mode__badge--warn">Non dispo</span>
					@endif

					<span class="co-mode__icon">
						<svg class="ico"><use href="#ico-package"/></svg>
					</span>
					<p class="co-mode__title">
						<span class="co-mode__radio"></span>
						Expédition postale
					</p>
					<p class="co-mode__desc">
						@if($expeditionAutorisee)
							Envoi sécurisé partout en France.<br>
							Délai estimé : 2 – 4 jours ouvrés.
						@else
							Réservée aux meringues
							<strong>mini individuelles</strong> uniquement.
						@endif
					</p>
				</div>

			</div>

			@unless($expeditionAutorisee)
			<div class="co-notice">
				<svg class="ico"><use href="#ico-info"/></svg>
				<span>
					L'expédition postale n'est possible que si votre panier contient
					uniquement des meringues au format <strong>mini individuel</strong>.
					Modifiez votre panier pour débloquer cette option.
				</span>
			</div>
			@endunless
		</div>

	</div>{{-- /colonne gauche --}}

	{{-- ══════════════════════════════════════════
		 COLONNE DROITE — RÉCAP STICKY
	══════════════════════════════════════════ --}}
	<div class="co-sticky">
		<div class="co-recap">

			<p class="co-recap__header">Récapitulatif</p>

			{{-- Miniatures des produits --}}
			<div class="co-recap__thumbs">
				@foreach($panier->lignes as $ligne)
				<div class="co-recap__thumb" title="{{ $ligne->produit->forme->nom_forme }} — {{ $ligne->produit->parfum->nom_parfum }}">
					<img
						src="{{ asset($ligne->produit->image($ligne->id_forme_condi)) }}"
						alt=""
						onerror="this.style.display='none'"
					>
				</div>
				@endforeach
			</div>

			{{-- Lignes de détail --}}
			@foreach($panier->lignes as $ligne)
			<div class="co-recap__line">
				<span>
					{{ $ligne->produit->forme->nom_forme }}
					<span style="color: var(--color-text-muted, #8C7B6A); font-size:.8em;">
						× {{ $ligne->quantite }}
					</span>
				</span>
				<strong>{{ number_format($ligne->prix_unitaire * $ligne->quantite, 2, ',', ' ') }} €</strong>
			</div>
			@endforeach

			<hr class="co-recap__divider">

			<div class="co-recap__line">
				<span>Sous-total HT</span>
				<strong>{{ number_format($totalHT, 2, ',', ' ') }} €</strong>
			</div>

			<div class="co-recap__line co-recap__line--tva">
				<span>TVA (10 %)</span>
				<strong>{{ number_format($montantTVA, 2, ',', ' ') }} €</strong>
			</div>

			<div class="co-recap__line" id="recap-livraison">
				<span>Livraison</span>
				<strong style="color: #2d6a4f;">Gratuite</strong>
			</div>

			<hr class="co-recap__divider">

			<div class="co-recap__total">
				<span class="co-recap__total-label">Total TTC</span>
				<span class="co-recap__total-amount">
					{{ number_format($totalTTC, 2, ',', ' ') }} €
				</span>
			</div>

			{{-- Bouton payer --}}
			<button type="submit" class="co-pay-btn" id="btn-payer">
				Payer {{ number_format($totalTTC, 2, ',', ' ') }} €
			</button>

			{{-- Sécurité --}}
			<div class="co-secure">
				<svg class="ico"><use href="#ico-lock"/></svg>
				Paiement sécurisé CIC Monetico
			</div>

			{{-- Logos CB --}}
			<div style="display:flex; justify-content:center; gap:8px; margin-top:.85rem; opacity:.6; font-size:.7rem; color: var(--color-text-muted, #8C7B6A);">
				CB · Visa · Mastercard · Apple Pay
			</div>

		</div>

		{{-- Lien retour boutique --}}
		<div style="text-align:center; margin-top:.75rem;">
			<a href="{{ route('shop.index', 1) }}"
			   style="font-size:.78rem; color: var(--color-text-muted, #8C7B6A); text-decoration:none;">
				← Continuer mes achats
			</a>
		</div>

	</div>{{-- /colonne droite --}}

</div>{{-- /co-page --}}
</form>
</main>

<script>
function choisirMode(valeur) {
	document.getElementById('mode_livraison').value = valeur;

	const livraison   = document.getElementById('mode-livraison');
	const expedition  = document.getElementById('mode-expedition');
	const recapLivr   = document.getElementById('recap-livraison');

	livraison.classList.toggle('active',  valeur === 'livraison');
	expedition.classList.toggle('active', valeur === 'expedition');

	if (valeur === 'expedition') {
		recapLivr.querySelector('strong').textContent = 'Selon tarif Colissimo';
		recapLivr.querySelector('strong').style.color = 'var(--color-text, #2E1A10)';
	} else {
		recapLivr.querySelector('strong').textContent = 'Gratuite';
		recapLivr.querySelector('strong').style.color = '#2d6a4f';
	}
}
</script>

@include('templet.footer')
