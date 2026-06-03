@php
	use Illuminate\Support\Facades\Auth;
	$user    = Auth::user();
	$isAdmin = $user->role === 'admin';
@endphp


@include('templet.header', [
	'titre' => 'Les Tickets des commandes',
])

@vite(['resources/css/ticket.css'])

<h2 class="sr-only" style="position:absolute;left:-9999px">Page des commandes — Maison des Meringues</h2>

<div class="page">

  <aside class="sidebar">
	<p class="sidebar__heading">Commandes</p>
	<nav class="nav-card">
	  <div class="nav-item active" data-filter="toutes">
		<i class="ti ti-receipt" aria-hidden="true"></i> Mes tickets
	  </div>
	  <div class="nav-item" data-filter="en_attente">
		<i class="ti ti-clock" aria-hidden="true"></i> En attente
	  </div>
	  <div class="nav-item" data-filter="payee">
		<i class="ti ti-package" aria-hidden="true"></i> Payées
	  </div>
	  <div class="nav-item" data-filter="expediee">
		<i class="ti ti-truck-delivery" aria-hidden="true"></i> Expédiées
	  </div>
	  <div class="nav-item" data-filter="annulee">
		<i class="ti ti-x" aria-hidden="true"></i> Annulées
	  </div>
	</nav>

	{{-- Résumé rapide --}}
	<div style="margin-top:1.5rem;background:var(--bg-card,#fff);border:1px solid var(--border,#e8dec8);border-radius:12px;padding:1rem;">
	  <p style="font-size:.65rem;text-transform:uppercase;letter-spacing:.12em;color:var(--text-muted,#8c7b6a);font-weight:600;margin-bottom:.75rem;">Résumé</p>
	  <div style="display:flex;flex-direction:column;gap:.5rem;">
		<div style="display:flex;justify-content:space-between;font-size:.8rem;">
		  <span style="color:var(--text-muted,#8c7b6a);">Total commandes</span>
		  <strong>{{ $commandes->count() }}</strong>
		</div>
		<div style="display:flex;justify-content:space-between;font-size:.8rem;">
		  <span style="color:var(--text-muted,#8c7b6a);">Total dépensé</span>
		  <strong style="color:#C0395A;">{{ number_format($commandes->sum('montant'), 2, ',', ' ') }} €</strong>
		</div>
	  </div>
	</div>
  </aside>

  <main class="content">
	<h2 class="section-title">Mes commandes</h2>
	<p class="section-sub">Retrouvez le détail de chacune de vos commandes</p>

	@if(session('success'))
	  <div style="display:flex;align-items:center;gap:8px;padding:.65rem 1rem;border-radius:8px;font-size:.82rem;font-weight:500;margin-bottom:1rem;background:#d4edda;color:#2d6a4f;">
		<i class="ti ti-circle-check" aria-hidden="true"></i>
		{{ session('success') }}
	  </div>
	@endif

	<div class="filter-row">
	  <button class="filter-btn active" data-filter="toutes">Toutes</button>
	  <button class="filter-btn" data-filter="en_attente">En attente</button>
	  <button class="filter-btn" data-filter="payee">Payées</button>
	  <button class="filter-btn" data-filter="expediee">Expédiées</button>
	  <button class="filter-btn" data-filter="annulee">Annulées</button>
	</div>

	<div class="ticket-wrap" id="ticket-wrap">

	  @forelse($commandes as $commande)

		@php
		  $statusMap = [
			'en_attente' => ['label' => 'En attente', 'class' => 'attente'],
			'payee'      => ['label' => 'Payée',      'class' => 'pret'],
			'expediee'   => ['label' => 'Expédiée',   'class' => 'livre'],
			'annulee'    => ['label' => 'Annulée',    'class' => 'annule'],
		  ];
		  $s = $statusMap[$commande->statut] ?? ['label' => $commande->statut, 'class' => ''];
		@endphp

		<div class="receipt" data-statut="{{ $commande->statut }}">

		  <div class="receipt__head">
			<div class="brand">Maison des <em>Meringues</em></div>
			<div class="sub">Confiserie artisanale — Normandie</div>
		  </div>

		  <div class="receipt__meta">
			<div>Commande <span>{{ $commande->reference }}</span></div>
			<div>Date <span>{{ $commande->created_at->translatedFormat('d M Y') }}</span></div>
			<div>Heure <span>{{ $commande->created_at->format('H\hi') }}</span></div>
		  </div>

		  <div class="receipt__status">
			<span style="font-size:.73rem;color:var(--text-muted)">Statut de la commande</span>
			<span class="status-badge {{ $s['class'] }}">{{ $s['label'] }}</span>
		  </div>

		  {{-- Infos client --}}
		  <div style="background:var(--bg-muted,#faf6ee);border:1px dashed var(--border,#e0d5c0);border-radius:8px;padding:.65rem .85rem;margin:.65rem 0;font-size:.78rem;">
			<p style="font-size:.65rem;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted,#8c7b6a);font-weight:600;margin-bottom:.45rem;">Client</p>
			<div style="display:flex;align-items:center;gap:7px;margin-bottom:.3rem;color:var(--text,#2e1a10);">
			  <i class="ti ti-user" style="font-size:13px;flex-shrink:0;" aria-hidden="true"></i>
			  <span>{{ $commande->user->name ?? '—' }}</span>
			</div>
			<div style="display:flex;align-items:center;gap:7px;margin-bottom:.3rem;color:var(--text,#2e1a10);">
			  <i class="ti ti-mail" style="font-size:13px;flex-shrink:0;" aria-hidden="true"></i>
			  <span>{{ $commande->user->email ?? '—' }}</span>
			</div>
			@if(!empty($commande->user->phone))
			  <div style="display:flex;align-items:center;gap:7px;color:var(--text,#2e1a10);">
				<i class="ti ti-phone" style="font-size:13px;flex-shrink:0;" aria-hidden="true"></i>
				<span>{{ $commande->user->phone }}</span>
			  </div>
			@endif
		  </div>

		  {{-- Mode de livraison --}}
		  <div style="display:flex;align-items:center;gap:8px;padding:.5rem 0;border-bottom:1px dashed var(--border,#e0d5c0);margin-bottom:.5rem;font-size:.78rem;color:var(--text-muted,#8c7b6a);">
			@if($commande->mode_livraison === 'expedition')
			  <i class="ti ti-package" aria-hidden="true"></i>
			  Expédition postale
			@else
			  <i class="ti ti-bike" aria-hidden="true"></i>
			  Livraison locale
			@endif
		  </div>

		  <div class="receipt__divider">— articles commandés —</div>

		  <div class="receipt__items">
			<div class="header-row">
			  <span>Désignation</span>
			  <span class="qty">Qté</span>
			  <span class="price">Montant</span>
			</div>

			@forelse($commande->lignes as $ligne)
			  <div class="item-row">
				<span class="name">{{ $ligne->designation }}</span>
				<span class="qty">× {{ $ligne->quantite }}</span>
				<span class="price">{{ number_format($ligne->prix_unitaire * $ligne->quantite, 2, ',', ' ') }} €</span>
				@if($ligne->sous_designation)
				  <span class="subname">{{ $ligne->sous_designation }}</span>
				@endif
			  </div>
			@empty
			  <div class="item-row">
				<span class="name" style="color:var(--text-muted,#8c7b6a);font-style:italic;">Aucun article enregistré</span>
				<span class="qty">—</span>
				<span class="price">—</span>
			  </div>
			@endforelse
		  </div>

		  <div class="receipt__totals">
			<div class="total-row">
			  <span>Sous-total</span>
			  <span>{{ number_format($commande->montant, 2, ',', ' ') }} €</span>
			</div>
			@if($commande->mode_livraison === 'expedition')
			  <div class="total-row">
				<span>Frais d'expédition</span>
				<span>selon tarif Colissimo</span>
			  </div>
			@else
			  <div class="total-row">
				<span>Livraison locale</span>
				<span>—</span>
			  </div>
			@endif
			<div class="total-row big">
			  <span>Total TTC</span>
			  <span>{{ number_format($commande->montant, 2, ',', ' ') }} €</span>
			</div>
		  </div>

		  @if($commande->monetico_reference)
			<div class="receipt__note">
			  <i class="ti ti-credit-card" style="font-size:14px;margin-top:1px;flex-shrink:0" aria-hidden="true"></i>
			  <span>Paiement validé — réf. Monetico : <strong>{{ $commande->monetico_reference }}</strong></span>
			</div>
		  @endif

		  <div class="receipt__footer">
			<div class="ty">Merci pour votre confiance !</div>
			<div class="barcode" aria-hidden="true" id="bc{{ $commande->id_commande }}"></div>
			<div class="order-num">{{ $commande->reference }}</div>
		  </div>

		  {{-- Bouton supprimer --}}
		  <form
			action="{{ route('ticket.destroy', $commande->id_commande) }}"
			method="POST"
			style="margin-top:.75rem;padding-top:.75rem;border-top:1px dashed var(--border,#e0d5c0);text-align:center;"
			onsubmit="return confirm('Supprimer définitivement cette commande et toutes ses lignes ?')"
		  >
			@csrf
			@method('DELETE')
			<button type="submit"
				style="display:inline-flex;align-items:center;gap:6px;padding:.4rem 1rem;background:none;border:1px solid #e57373;border-radius:999px;color:#c62828;font-size:.75rem;font-weight:600;cursor:pointer;transition:background .15s;">
			  <i class="ti ti-trash" style="font-size:13px;" aria-hidden="true"></i>
			  Supprimer cette commande
			</button>
		  </form>

		</div>

	  @empty

		<div style="text-align:center;padding:4rem 2rem;color:var(--text-muted,#8c7b6a);">
		  <p style="font-size:3rem;margin-bottom:1rem;">🧾</p>
		  <p style="font-size:1.1rem;font-weight:600;margin-bottom:.5rem;color:var(--text,#2e1a10);">Aucune commande pour l'instant</p>
		  <p style="font-size:.85rem;margin-bottom:1.5rem;">Passez votre première commande dans notre boutique !</p>
		  <a href="{{ route('shop.index', 1) }}"
			 style="display:inline-block;padding:.6rem 1.5rem;background:#C0395A;color:#fff;border-radius:999px;text-decoration:none;font-weight:600;font-size:.85rem;">
			Aller à la boutique →
		  </a>
		</div>

	  @endforelse

	</div>
  </main>
</div>

<script>
const statusLabels = {
  en_attente: 'attente',
  payee:      'pret',
  expediee:   'livre',
  annulee:    'annule',
};

function renderBarcode(id) {
  const el = document.getElementById(id);
  if (!el) return;
  const seed = id.replace(/\D/g, '') || '1';
  let n = parseInt(seed);
  for (let i = 0; i < 36; i++) {
	n = (n * 1103515245 + 12345) & 0x7fffffff;
	const w = (n % 3) + 1;
	const s = document.createElement('span');
	s.style.width = (w * 2.5) + 'px';
	el.appendChild(s);
  }
}

@foreach($commandes as $commande)
  renderBarcode('bc{{ $commande->id_commande }}');
@endforeach

const filterBtns = document.querySelectorAll('.filter-btn');
const navItems   = document.querySelectorAll('.nav-item[data-filter]');
const receipts   = document.querySelectorAll('.receipt[data-statut]');

function applyFilter(filter) {
  receipts.forEach(r => {
	r.style.display = (filter === 'toutes' || r.dataset.statut === filter) ? '' : 'none';
  });

  filterBtns.forEach(b => b.classList.toggle('active', b.dataset.filter === filter));
  navItems.forEach(i   => i.classList.toggle('active',  i.dataset.filter === filter));

  const wrap    = document.getElementById('ticket-wrap');
  const visible = [...receipts].filter(r => r.style.display !== 'none');
  const empty   = document.getElementById('filter-empty');

  if (visible.length === 0 && !empty) {
	const div = document.createElement('div');
	div.id = 'filter-empty';
	div.style.cssText = 'text-align:center;padding:3rem 2rem;color:var(--text-muted,#8c7b6a);font-size:.85rem;';
	div.textContent = 'Aucune commande dans cette catégorie.';
	wrap.appendChild(div);
  } else if (visible.length > 0 && empty) {
	empty.remove();
  }
}

filterBtns.forEach(btn => btn.addEventListener('click', () => applyFilter(btn.dataset.filter)));
navItems.forEach(item  => item.addEventListener('click', () => applyFilter(item.dataset.filter)));
</script>
