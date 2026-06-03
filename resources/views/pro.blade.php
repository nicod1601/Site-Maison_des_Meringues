@include('templet.header', [
	'titre' => 'Espace Professionnel',
	'note'  => 'présentation des produits + infos + contact',
	'title' => 'Professionnel'
])
@vite('resources/css/pro.css')

<main class="pro-body" id="pro-contenu">

	{{-- ── Hero ── --}}
	<section class="hero" aria-label="Introduction professionnelle">
		<div class="hero__inner">
			<span class="hero__eyebrow">Espace Professionnel</span>
			<h1 class="hero__title">Des meringues&nbsp;d'exception<br>pour votre activité</h1>
			<p class="hero__sub">Nous proposons nos créations artisanales aux revendeurs, épiceries fines, hôtels &amp; restaurants qui partagent notre exigence de qualité.</p>
			<a href="#contact" class="btn-hero">
				<i class="ti ti-mail" aria-hidden="true"></i>
				Prendre contact
			</a>
		</div>
		<div class="hero__deco" aria-hidden="true">
			<span class="hero__deco-ring hero__deco-ring--1"></span>
			<span class="hero__deco-ring hero__deco-ring--2"></span>
			<span class="hero__deco-ring hero__deco-ring--3"></span>
		</div>
	</section>

	{{-- ── Pourquoi nous choisir ── --}}
	<section class="avantages" aria-labelledby="titre-avantages">
		<div class="avantages__inner">
			<h2 class="section-title" id="titre-avantages">Pourquoi nous choisir&nbsp;?</h2>
			<div class="avantages__grid">
				<div class="avantage-card">
					<div class="avantage-card__icon"><i class="ti ti-leaf" aria-hidden="true"></i></div>
					<h3 class="avantage-card__title">Fabrication artisanale</h3>
					<p class="avantage-card__text">Chaque meringue est confectionnée à la main dans notre atelier normand, sans additif ni conservateur.</p>
				</div>
				<div class="avantage-card">
					<div class="avantage-card__icon"><i class="ti ti-palette" aria-hidden="true"></i></div>
					<h3 class="avantage-card__title">Large choix de parfums</h3>
					<p class="avantage-card__text">Une gamme de saveurs variées pour séduire tous les palais et enrichir votre offre produit.</p>
				</div>
				<div class="avantage-card">
					<div class="avantage-card__icon"><i class="ti ti-package" aria-hidden="true"></i></div>
					<h3 class="avantage-card__title">Conditionnements adaptés</h3>
					<p class="avantage-card__text">Sachets et boîtes pensés pour la revente, la dégustation ou l'offre cadeau, prêts à poser en rayon.</p>
				</div>
				<div class="avantage-card">
					<div class="avantage-card__icon"><i class="ti ti-truck" aria-hidden="true"></i></div>
					<h3 class="avantage-card__title">Commandes sur mesure</h3>
					<p class="avantage-card__text">Volumes, formes, personnalisation : contactez-nous pour construire une offre taillée à vos besoins.</p>
				</div>
			</div>
		</div>
	</section>

	{{-- ── Catalogue ── --}}
	<section class="catalogue" aria-labelledby="titre-catalogue">
		<div class="catalogue__inner">
			<h2 class="section-title" id="titre-catalogue">Notre catalogue</h2>

			<div class="catalogue__layout">

				{{-- Colonne gauche : Formes + Conditionnements --}}
				<div class="catalogue__left">

					{{-- Formes --}}
					<div class="cat-block">
						<h3 class="cat-block__title">
							<i class="ti ti-circles" aria-hidden="true"></i>
							Les Formes
						</h3>
						<div class="products-grid">
							@foreach ($formes as $forme)
							<article class="prod-card" aria-label="{{ $forme->nom_forme }}">
								<div class="prod-card__img-wrap">
									<img
										src="{{ asset('fichier/image/meringues/' . $forme->nom_forme . '.webp') }}"
										class="prod-card__img"
										alt="{{ $forme->nom_forme }}"
										loading="lazy" decoding="async"
									>
								</div>
								<p class="prod-card__name">{{ $forme->nom_forme }}</p>
							</article>
							@endforeach
						</div>
					</div>

					{{-- Conditionnements --}}
					<div class="cat-block">
						<h3 class="cat-block__title">
							<i class="ti ti-package" aria-hidden="true"></i>
							Les Conditionnements
						</h3>
						<div class="products-grid">
							@php
								$diffCondi = [];
								$sachetDejaAjoute = false;
								$boiteDejaAjoute  = false;
								$typesDejaAjoutes = [];
								foreach ($conditionnements as $c) {
									if ($c->type === 'sachet_de_10' || $c->type === 'sachet_de_4') {
										if (!$sachetDejaAjoute) {
											$diffCondi[] = (object)['type' => 'sachet'];
											$sachetDejaAjoute = true;
										}
									} elseif ($c->type === 'boite_de_8') {
										if (!$boiteDejaAjoute) {
											$diffCondi[] = (object)['type' => 'boite'];
											$boiteDejaAjoute = true;
										}
									} else {
										if (!in_array($c->type, $typesDejaAjoutes)) {
											$diffCondi[] = $c;
											$typesDejaAjoutes[] = $c->type;
										}
									}
								}
							@endphp

							@foreach ($diffCondi as $condi)
							<article class="prod-card" aria-label="{{ $condi->type }}">
								<div class="prod-card__img-wrap">
									<img
										src="{{ asset('fichier/image/meringues/' . $condi->type . '.webp') }}"
										class="prod-card__img"
										alt="{{ $condi->type }}"
										loading="lazy" decoding="async"
									>
								</div>
								<p class="prod-card__name">{{ ucfirst($condi->type) }}</p>
								@if ($condi->type === 'sachet')
								<div class="prod-card__badges">
									<span class="badge">× 4</span>
									<span class="badge">× 10</span>
								</div>
								@endif
								@if ($condi->type === 'boite')
								<div class="prod-card__badges">
									<span class="badge">× 8</span>
								</div>
								@endif
							</article>
							@endforeach
						</div>
					</div>

				</div>

				{{-- Colonne droite : Parfums --}}
				<aside class="catalogue__right" aria-label="Liste des parfums">
					<div class="parfums-panel">
						<h3 class="cat-block__title">
							<i class="ti ti-flower" aria-hidden="true"></i>
							Les Parfums
						</h3>
						<ul class="parfums-list" role="list">
							@foreach ($parfums as $parfum)
							<li class="parfum-item">
								<span class="parfum-item__dot" aria-hidden="true"></span>
								{{ $parfum->nom_parfum }}
							</li>
							@endforeach
						</ul>
						<p class="parfums-note">D'autres saveurs peuvent être développées sur demande.</p>
					</div>
				</aside>

			</div>
		</div>
	</section>

	{{-- ── Contact ── --}}
	<section class="contact" id="contact" aria-labelledby="titre-contact">
		<div class="contact__inner">
			<h2 class="section-title section-title--light" id="titre-contact">Nous contacter</h2>
			<p class="contact__sub">Vous êtes professionnel et souhaitez référencer nos produits&nbsp;? Écrivez-nous ou appelez-nous directement.</p>

			<div class="contact__grid">

				<div class="contact-info-card">
					<div class="contact-info-card__icon"><i class="ti ti-phone" aria-hidden="true"></i></div>
					<div>
						<div class="contact-info-card__label">Téléphone</div>
						<a href="tel:+33235102449" class="contact-info-card__val">02 35 10 24 49</a>
					</div>
				</div>

				<div class="contact-info-card">
					<div class="contact-info-card__icon"><i class="ti ti-mail" aria-hidden="true"></i></div>
					<div>
						<div class="contact-info-card__label">Email</div>
						<a href="mailto:contact@lamaisondesmeringues.fr" class="contact-info-card__val">contact@lamaisondesmeringues.fr</a>
					</div>
				</div>

				<div class="contact-info-card">
					<div class="contact-info-card__icon"><i class="ti ti-map-pin" aria-hidden="true"></i></div>
					<div>
						<div class="contact-info-card__label">Adresse</div>
						<div class="contact-info-card__val">18 rte de Fécamp<br>Ypreville-Biville, 76540</div>
					</div>
				</div>

				<div class="contact-info-card">
					<div class="contact-info-card__icon"><i class="ti ti-clock" aria-hidden="true"></i></div>
					<div>
						<div class="contact-info-card__label">Horaires</div>
						<div class="contact-info-card__val">Lun – Sam : 9h – 18h</div>
					</div>
				</div>

			</div>
		</div>
	</section>

</main>

<script>
document.querySelectorAll('.prod-card__img').forEach(img => {
	img.addEventListener('error', () => {
		img.src = '/fichier/image/meringues/oups.webp';
	});
});
</script>

</body>
@include('templet.footer')
</html>
