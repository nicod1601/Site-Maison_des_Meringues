@include('templet.header', [
	'titre' => 'Professionnel',
	'note'  => 'présentation des produits + infos + contacte',
	'title' => 'Professionnel'
])
@vite('resources/css/pro.css')

<main class="boutique-body" id="boutique-contenu">

	<div class="boutique-layout">

		{{-- ── Colonne gauche ── --}}
		<div class="boutique-col-left">

			{{-- Formes --}}
			<section class="section-card" aria-labelledby="titre-formes">
				<h2 class="section-card__title" id="titre-formes">
					<i class="ti ti-circles section-card__title-icon" aria-hidden="true"></i>
					Les Formes
				</h2>
				<div class="products-grid">
					@foreach ($formes as $forme)
					<article class="boutique-card" aria-label="{{ $forme->nom_forme }}">
						<div class="boutique-card__img-wrap">
							<div class="boutique-card__img-placeholder" aria-hidden="true">
								<img
									src="{{ asset('fichier/image/meringues/' . $forme->nom_forme . '.webp') }}"
									class="boutique-card__img"
									alt="{{ $forme->nom_forme }}"
									loading="lazy" decoding="async"
								>
							</div>
						</div>
						<div class="boutique-card__body">
							<h3 class="boutique-card__name">{{ $forme->nom_forme }}</h3>
						</div>
					</article>
					@endforeach
				</div>
			</section>

			{{-- Conditionnements --}}
			<section class="section-card" aria-labelledby="titre-condi">
				<h2 class="section-card__title" id="titre-condi">
					<i class="ti ti-package section-card__title-icon" aria-hidden="true"></i>
					Les Conditionnements
				</h2>
				<div class="products-grid">
					@php
						$diffCondi = [];
						$sachetDejaAjoute = false;
						$boiteDejaAjoute = false;
						$typesDejaAjoutes = [];
						foreach ($conditionnements as $c)
						{
							if ($c->type === 'sachet_de_10' || $c->type === 'sachet_de_4')
							{
								if (!$sachetDejaAjoute)
								{
									$diffCondi[] = (object)['type' => 'sachet'];
									$sachetDejaAjoute = true;
								}
							}
							else
							{
								if ($c->type === 'boite_de_8')
								{
									if (!$boiteDejaAjoute)
									{
										$diffCondi[] = (object)['type' => 'boite'];
										$boiteDejaAjoute = true;
									}
								}
								else
								{
									if (!in_array($c->type, $typesDejaAjoutes))
									{
										$diffCondi[] = $c;
										$typesDejaAjoutes[] = $c->type;
									}
								}

							}
						}
					@endphp

					@foreach ($diffCondi as $condi)
					<article class="boutique-card" aria-label="{{ $condi->type }}">
						<div class="boutique-card__img-wrap">
							<div class="boutique-card__img-placeholder" aria-hidden="true">
								<img
									src="{{ asset('fichier/image/meringues/' . $condi->type . '.webp') }}"
									class="boutique-card__img"
									alt="{{ $condi->type }}"
									loading="lazy" decoding="async"
								>
							</div>
						</div>
						<div class="boutique-card__body">
							<h3 class="boutique-card__name">{{ $condi->type }}</h3>
							@if ($condi->type === 'sachet')
							<div class="boutique-card__badges">
								<span class="badge badge--outline">×4</span>
								<span class="badge badge--outline">×10</span>
							</div>
							@endif
                            @if ($condi->type === 'boite')
							<div class="boutique-card__badges">
								<span class="badge badge--outline">×8</span>
							</div>
							@endif
						</div>
					</article>
					@endforeach
				</div>
			</section>

		</div>

		{{-- ── Colonne droite : parfums ── --}}
		<aside class="boutique-col-right" aria-label="Liste des parfums">
			<div class="parfums-card">
				<h2 class="section-card__title">
					<i class="ti ti-flower section-card__title-icon" aria-hidden="true"></i>
					Les Parfums
				</h2>
				@foreach ($parfums as $parfum)
				<div class="parfum-item">
					<span class="parfum-item__dot" aria-hidden="true"></span>
					<span class="parfum-item__name">{{ $parfum->nom_parfum }}</span>
				</div>
				@endforeach
			</div>
		</aside>

	</div>

	{{-- ── Contact ── --}}
	<section class="contact-section" aria-labelledby="titre-contact">
		<h2 class="section-card__title" id="titre-contact">
			<i class="ti ti-mail section-card__title-icon" aria-hidden="true"></i>
			Nous contacter
		</h2>
		<div class="contact-layout">

			<div class="contact-infos">
				<div class="contact-info-row">
					<div class="contact-info-icon"><i class="ti ti-phone" aria-hidden="true"></i></div>
					<div>
						<div class="contact-info-label">Téléphone</div>
						<div class="contact-info-val">02 35 10 24 49</div>
					</div>
				</div>
				<div class="contact-info-row">
					<div class="contact-info-icon"><i class="ti ti-mail" aria-hidden="true"></i></div>
					<div>
						<div class="contact-info-label">Email</div>
						<div class="contact-info-val">contact@lamaisondesmeringues.fr</div>
					</div>
				</div>
				<div class="contact-info-row">
					<div class="contact-info-icon"><i class="ti ti-map-pin" aria-hidden="true"></i></div>
					<div>
						<div class="contact-info-label">Adresse</div>
						<div class="contact-info-val">18 rte de Fécamp<br>Ypreville-Biville<br>76540</div>
					</div>
				</div>
				<div class="contact-info-row">
					<div class="contact-info-icon"><i class="ti ti-clock" aria-hidden="true"></i></div>
					<div>
						<div class="contact-info-label">Horaires</div>
						<div class="contact-info-val">Lun – Sam : 9h – 18h</div>
					</div>
				</div>
			</div>

			<!--<div class="contact-form">
				<div class="form-group">
					<label class="form-label" for="c-nom">Nom / Entreprise</label>
					<input class="form-input" type="text" id="c-nom" placeholder="Ex : Boulangerie Martin">
				</div>
				<div class="form-group">
					<label class="form-label" for="c-tel">Téléphone</label>
					<input class="form-input" type="tel" id="c-tel" placeholder="+33 X XX XX XX XX">
				</div>
				<div class="form-group">
					<label class="form-label" for="c-email">Email</label>
					<input class="form-input" type="email" id="c-email" placeholder="vous@email.fr">
				</div>
				<div class="form-group">
					<label class="form-label" for="c-msg">Message</label>
					<textarea class="form-textarea" id="c-msg" placeholder="Votre demande professionnelle..."></textarea>
				</div>
				<button class="btn btn--primary" type="button">
					<i class="ti ti-send" aria-hidden="true"></i>
					Envoyer la demande
				</button>
			</div>-->

		</div>
	</section>

</main>



<script>
document.querySelectorAll('.boutique-card__img').forEach(img => {
	img.addEventListener('error', () => {
		img.src = '/fichier/image/meringues/oups.webp';
	});
});
</script>

</body>

@include('templet.footer')
</html>

