@vite(['resources/css/ticket.css'])

<h2 class="sr-only" style="position:absolute;left:-9999px">Page des commandes — Maison des Meringues</h2>

<div class="page">

  <aside class="sidebar">
	<p class="sidebar__heading">Commandes</p>
	<nav class="nav-card">
	  <div class="nav-item active"><i class="ti ti-receipt" aria-hidden="true"></i> Mes tickets</div>
	  <div class="nav-item"><i class="ti ti-history" aria-hidden="true"></i> Historique</div>
	  <div class="nav-item"><i class="ti ti-truck-delivery" aria-hidden="true"></i> Suivi livraison</div>
	</nav>
  </aside>

  <main class="content">
	<h2 class="section-title">Mes commandes</h2>
	<p class="section-sub">Retrouvez le détail de chacune de vos commandes</p>

	<div class="filter-row">
	  <button class="filter-btn active">Toutes</button>
	  <button class="filter-btn">En attente</button>
	  <button class="filter-btn">Prêtes</button>
	  <button class="filter-btn">Livrées</button>
	</div>

	<div class="ticket-wrap">

	  <!-- TICKET 1 -->
	  <div class="receipt">
		<div class="receipt__head">
		  <div class="brand">Maison des <em>Meringues</em></div>
		  <div class="sub">Confiserie artisanale — Caen, Normandie</div>
		</div>

		<div class="receipt__meta">
		  <div>Commande <span>#2025-0041</span></div>
		  <div>Date <span>14 mai 2025</span></div>
		  <div>Heure <span>10h32</span></div>
		</div>

		<div class="receipt__status">
		  <span style="font-size:.73rem;color:var(--text-muted)">Statut de la commande</span>
		  <span class="status-badge livre"><i class="ti ti-circle-check" style="font-size:11px" aria-hidden="true"></i> Livrée</span>
		</div>

		<div class="receipt__divider">— articles commandés —</div>

		<div class="receipt__items">
		  <div class="header-row">
			<span>Désignation</span>
			<span class="qty">Qté</span>
			<span class="price">Montant</span>
		  </div>
		  <div class="item-row">
			<span class="name">Meringue vanille</span>
			<span class="qty">× 6</span>
			<span class="price">4,20 €</span>
		  </div>
		  <div class="item-row">
			<span class="name">Pavlova framboise</span>
			<span class="qty">× 1</span>
			<span class="price">9,50 €</span>
			<span class="subname">format individuel — garni framboise fraîche</span>
		  </div>
		  <div class="item-row">
			<span class="name">Assortiment mini-meringues</span>
			<span class="qty">× 2</span>
			<span class="price">7,60 €</span>
			<span class="subname">boîte de 12 pièces — citron, chocolat, rose</span>
		  </div>
		  <div class="item-row">
			<span class="name">Tarte meringuée citron</span>
			<span class="qty">× 1</span>
			<span class="price">12,00 €</span>
		  </div>
		</div>

		<div class="receipt__totals">
		  <div class="total-row"><span>Sous-total</span><span>33,30 €</span></div>
		  <div class="total-row"><span>Frais de livraison</span><span>2,50 €</span></div>
		  <div class="total-row"><span>Remise fidélité (−5 %)</span><span>−1,67 €</span></div>
		  <div class="total-row big"><span>Total TTC</span><span>34,13 €</span></div>
		</div>

		<div class="receipt__note">
		  <i class="ti ti-notes" style="font-size:14px;margin-top:1px;flex-shrink:0" aria-hidden="true"></i>
		  <span><strong>Note :</strong> Merci de laisser la commande au gardien si absent. Sonnette 2ème gauche.</span>
		</div>

		<div class="receipt__footer">
		  <div class="ty">Merci pour votre confiance !</div>
		  <div class="barcode" aria-hidden="true" id="bc1"></div>
		  <div class="order-num">CMD — 2025 — 0041</div>
		</div>
	  </div>

	  <!-- TICKET 2 -->
	  <div class="receipt">
		<div class="receipt__head">
		  <div class="brand">Maison des <em>Meringues</em></div>
		  <div class="sub">Confiserie artisanale — Caen, Normandie</div>
		</div>

		<div class="receipt__meta">
		  <div>Commande <span>#2025-0053</span></div>
		  <div>Date <span>19 mai 2025</span></div>
		  <div>Heure <span>14h07</span></div>
		</div>

		<div class="receipt__status">
		  <span style="font-size:.73rem;color:var(--text-muted)">Statut de la commande</span>
		  <span class="status-badge pret"><i class="ti ti-package" style="font-size:11px" aria-hidden="true"></i> Prête</span>
		</div>

		<div class="receipt__divider">— articles commandés —</div>

		<div class="receipt__items">
		  <div class="header-row">
			<span>Désignation</span>
			<span class="qty">Qté</span>
			<span class="price">Montant</span>
		  </div>
		  <div class="item-row">
			<span class="name">Pavlova grand format</span>
			<span class="qty">× 1</span>
			<span class="price">28,00 €</span>
			<span class="subname">8 personnes — fruits rouges & chantilly</span>
		  </div>
		  <div class="item-row">
			<span class="name">Meringue chocolat noir</span>
			<span class="qty">× 4</span>
			<span class="price">4,80 €</span>
		  </div>
		  <div class="item-row">
			<span class="name">Coffret cadeau prestige</span>
			<span class="qty">× 1</span>
			<span class="price">18,00 €</span>
			<span class="subname">boîte ruban — 20 pièces assorties</span>
		  </div>
		</div>

		<div class="receipt__totals">
		  <div class="total-row"><span>Sous-total</span><span>50,80 €</span></div>
		  <div class="total-row"><span>Retrait en boutique</span><span>0,00 €</span></div>
		  <div class="total-row big"><span>Total TTC</span><span>50,80 €</span></div>
		</div>

		<div class="receipt__footer">
		  <div class="ty">Merci pour votre confiance !</div>
		  <div class="barcode" aria-hidden="true" id="bc2"></div>
		  <div class="order-num">CMD — 2025 — 0053</div>
		</div>
	  </div>

	</div>
  </main>
</div>

<script>
const bars1 = [3,1,2,1,3,2,1,2,1,3,1,2,3,1,2,1,3,2,1,3,2,1,2,3,1,2,1,3,2,1,2,3,1];
const bars2 = [2,1,3,2,1,3,1,2,1,3,2,1,2,3,1,3,2,1,2,1,3,1,2,3,1,2,3,1,2,1,3,2,1];
function renderBarcode(id, bars) {
  const el = document.getElementById(id);
  if (!el) return;
  bars.forEach(w => {
	const s = document.createElement('span');
	s.style.width = (w * 3) + 'px';
	el.appendChild(s);
  });
}
renderBarcode('bc1', bars1);
renderBarcode('bc2', bars2);

document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', () => {
	document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
	btn.classList.add('active');
  });
});
</script>
