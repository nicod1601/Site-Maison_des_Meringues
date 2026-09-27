@include('templet.header', [
	'titre' => 'Bienvenue à La Maison des Meringues',
])
@vite('resources/css/index.css')

<main>

	{{-- ── ACTIVITÉS ── --}}
	<section class="section section--cream reveal">
		<div class="container">
			<div class="section__header">
				<p class="subtitle">Ce que nous faisons</p>
				<h2>Nos activités</h2>
			</div>
			<div class="grid grid-3">

				<div class="product-card reveal reveal--delay-1">
					<div class="act-icon-circle">
						<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
							<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
							<polyline points="9 22 9 12 15 12 15 22"/>
						</svg>
					</div>
					<h3 class="card__title mt-md">Vente en boutique</h3>
					<p class="text-muted text-small">Découvrez nos meringues artisanales directement dans notre boutique à Arques-la-Bataille.</p>
				</div>

				<div class="product-card reveal reveal--delay-2">
					<div class="act-icon-circle">
						<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
							<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
							<circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
						</svg>
					</div>
					<h3 class="card__title mt-md">Expédition à domicile</h3>
					<p class="text-muted text-small">Recevez vos commandes chez vous en 24 à 48h. Emballage soigné pour une arrivée parfaite.</p>
				</div>

				<div class="product-card reveal reveal--delay-3">
					<div class="act-icon-circle">
						<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
							<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.09a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 15z"/>
						</svg>
					</div>
					<h3 class="card__title mt-md">Commande sur mesure</h3>
					<p class="text-muted text-small">Mariages, anniversaires, événements… Contactez-nous pour une création personnalisée.</p>
				</div>

			</div>
		</div>
	</section>

	{{-- ── PRODUITS (carousel) ── --}}
	<section class="section reveal">
		<div class="container">
			<div class="section__header">
				<p class="subtitle">Nos créations</p>
				<h2>Produits phares</h2>
			</div>

			<div class="produits-carousel-wrap">
				<button type="button" class="carousel-arrow carousel-arrow--prev" id="carousel-prev" aria-label="Produit précédent">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
				</button>

				<div class="produits-carousel"></div>

				<button type="button" class="carousel-arrow carousel-arrow--next" id="carousel-next" aria-label="Produit suivant">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
				</button>
			</div>

			<div class="carousel-dots" id="carousel-dots"></div>
		</div>
	</section>

	{{-- ── HORAIRES + CARTE ── --}}
	<section class="section section--cream reveal" id="nous-trouver">
		<div class="container">
			<div class="section__header">
				<p class="subtitle">Nous trouver</p>
				<h2>Horaires & Adresse</h2>
			</div>
			<div class="grid grid-2">

				{{-- Horaires --}}
				<div class="card reveal reveal--delay-1">
					<div class="card__body">
						<div class="flex-between mb-lg">
							<h3 class="card__title" style="margin:0;">Horaires Service Client</h3>
							@php
								$jourOuvre = !now()->isSunday();
							@endphp
							<span class="badge {{ $jourOuvre ? 'badge--new' : 'badge--close' }}">
								{{ $jourOuvre ? 'Ouvert aujourd\'hui' : 'Fermé aujourd\'hui' }}
							</span>
						</div>
						<table class="horaires-table">
							@php
								$jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
								$aujourdhui = $jours[now()->dayOfWeekIso - 1];
							@endphp
							<tbody>
								@foreach($jours as $jour)
									<tr class="{{ $jour === $aujourdhui ? 'horaires-table__today' : '' }}">
										<td>{{ $jour }}</td>
										<td>
											@if($jour === 'Dimanche')
												<span class="badge badge--close">Fermé</span>
											@else
												9h00 – 18h00 <span class="badge badge--new">Ouvert</span>
											@endif
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						<p class="text-muted text-small mt-md">
							Nous restons joignables tous les jours : n'hésitez pas à nous écrire ou nous appeler,
							même en dehors des horaires affichés.
						</p>
					</div>
				</div>

				{{-- Carte --}}
				<div class="card overflow-hidden reveal reveal--delay-2">
					<div class="map-wrap">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3069.1685964485832!2d0.5293422864106478!3d49.69474343409136!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e067d52aa8e20b%3A0x150c859b4824c705!2sAuberge%20d&#39;Ypreville!5e0!3m2!1sfr!2sfr!4v1776329319224!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
					</div>
					<div class="card__body">
						<p class="card__tag">Adresse</p>
						<p class="text-muted text-small">Auberge d'Ypreville — 18 Rte de Fécamp, 76540 Ypreville-Biville</p>
					</div>
				</div>

			</div>
		</div>
	</section>

	{{-- ── LIVRAISON & EXPÉDITION ── --}}
	<section class="section section--dark reveal">
		<div class="container">
			<div class="section__header">
				<p class="subtitle" style="color: var(--color-gold);">Nos services</p>
				<h2>Livraison & Expédition</h2>
			</div>
			<div class="grid grid-3">

				<div class="livraison-card reveal reveal--delay-1">
					<div class="livraison-card__icon">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
							<rect x="1" y="3" width="15" height="13"/>
							<polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
							<circle cx="5.5" cy="18.5" r="2.5"/>
							<circle cx="18.5" cy="18.5" r="2.5"/>
						</svg>
					</div>
					<h3>Livraison locale</h3>
					<p>En Seine-Maritime sous 24h. Offerte dès 30 € d'achat.</p>
					<span class="badge badge--promo mt-md">Dès 3,90 €</span>
				</div>

				<div class="livraison-card reveal reveal--delay-2">
					<div class="livraison-card__icon">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
							<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
						</svg>
					</div>
					<h3>Expédition France</h3>
					<p>Colissimo avec emballage isotherme. Livraison en 48h partout en France.</p>
					<span class="badge badge--promo mt-md">Dès 5,90 €</span>
				</div>

				<div class="livraison-card reveal reveal--delay-3">
					<div class="livraison-card__icon">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
							<circle cx="12" cy="12" r="10"/>
							<line x1="2" y1="12" x2="22" y2="12"/>
							<path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>
						</svg>
					</div>
					<h3>Click & Collect</h3>
					<p>Commandez en ligne, retirez en boutique sans attente. Disponible sous 2h.</p>
					<span class="badge badge--gold mt-md">Gratuit</span>
				</div>

			</div>
		</div>
	</section>

</main>

@include('templet.footer')

<script>
	const carousel   = document.querySelector('.produits-carousel');
	const dotsWrap   = document.getElementById('carousel-dots');
	const btnPrev    = document.getElementById('carousel-prev');
	const btnNext    = document.getElementById('carousel-next');
	const listImages = @json($images);
	const produits   = @json($produits);

	// Indexer les produits par id_produit pour accès rapide
	const produitsMap = {};
	produits.forEach(p => { produitsMap[p.id_produit] = p; });

	const nomsVus      = new Set();
	const imagesFiltrees = listImages.filter(img => {
		const produit = produitsMap[img.id_produit];
		const nom     = produit
			? (produit.forme?.nom_forme ?? '') + ' — ' + (produit.parfum?.nom_parfum ?? '')
			: 'Produit';

		if (nomsVus.has(nom)) return false;
		nomsVus.add(nom);
		return true;
	});

	imagesFiltrees.forEach((img, i) => {
		const produit = produitsMap[img.id_produit];
		const nom     = produit
			? (produit.forme?.nom_forme ?? '') + ' — ' + (produit.parfum?.nom_parfum ?? '')
			: 'Produit';

		const div = document.createElement('div');
		div.classList.add('produit-item');
		div.innerHTML = `
			<div class="produit-item__img-wrap">
				<span class="produit-item__label">${nom}</span>
				<img src="${img.url}"
					class="produit-item__img"
					alt="${nom}"
					loading="lazy"
					decoding="async"
					fetchpriority="low"
					onerror="
						this.dataset.error = 'true';
						this.onerror = null;
						this.src='/fichier/image/oups.png';
					"
				>
			</div>
		`;
		carousel.appendChild(div);

		if (dotsWrap) {
			const dot = document.createElement('button');
			dot.type = 'button';
			dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
			dot.setAttribute('aria-label', `Aller au produit ${i + 1}`);
			dot.addEventListener('click', () => { index = i; showSlide(index); resetAutoplay(); });
			dotsWrap.appendChild(dot);
		}
	});

	const items = document.querySelectorAll('.produit-item');
	const dots  = document.querySelectorAll('.carousel-dot');
	let index = 0;
	let autoplayTimer = null;

	function showSlide(i) {
		items.forEach(item => {
			item.style.transform = `translateX(-${i * 100}%)`;
		});
		dots.forEach((d, di) => d.classList.toggle('active', di === i));
	}

	function nextSlide() {
		if (!items.length) return;
		index = (index + 1) % items.length;
		showSlide(index);
	}

	function prevSlide() {
		if (!items.length) return;
		index = (index - 1 + items.length) % items.length;
		showSlide(index);
	}

	function resetAutoplay() {
		clearInterval(autoplayTimer);
		autoplayTimer = setInterval(nextSlide, 5000);
	}

	btnNext?.addEventListener('click', () => { nextSlide(); resetAutoplay(); });
	btnPrev?.addEventListener('click', () => { prevSlide(); resetAutoplay(); });

	const carouselWrap = document.querySelector('.produits-carousel-wrap');
	carouselWrap?.addEventListener('mouseenter', () => clearInterval(autoplayTimer));
	carouselWrap?.addEventListener('mouseleave', resetAutoplay);

	resetAutoplay();

	// ── Animations au scroll ────────────────────────────────────
	const reveals = document.querySelectorAll('.reveal');
	if ('IntersectionObserver' in window) {
		const observer = new IntersectionObserver(entries => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					entry.target.classList.add('reveal--visible');
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });

		reveals.forEach(el => observer.observe(el));
	} else {
		reveals.forEach(el => el.classList.add('reveal--visible'));
	}
</script>