@include('templet.header', [
	'titre' => 'Paiement non abouti',
	'note'  => 'Votre panier est conservé',
	'title' => 'Erreur paiement',
])

@vite('resources/css/checkout.css')
@include('checkout._icons')

<main class="co">
	<div class="co__inner">

		@include('checkout._steps', ['etape' => 2, 'erreur' => true])

		<section class="co-result co-result--error" aria-labelledby="co-result-title">
			<div class="co-result__icon">
				<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path class="co-result__draw" d="M15 15l18 18"/>
					<path class="co-result__draw co-result__draw--2" d="M33 15L15 33"/>
				</svg>
			</div>

			<h2 class="co-result__title" id="co-result-title">Paiement non abouti</h2>
			<p class="co-result__text">
				Votre paiement n'a pas pu être traité. Pas d'inquiétude&nbsp;: votre panier est conservé
				et vous pouvez réessayer quand vous le souhaitez.
			</p>

			<div class="co-result__actions">
				<a href="{{ route('checkout.index') }}" class="co-btn co-btn--primary">
					{!! coicon('refresh') !!} Réessayer le paiement
				</a>
				<a href="{{ route('panier.index') }}" class="co-btn co-btn--ghost">
					Retour au panier
				</a>
			</div>

			<p class="co-result__help">
				Un souci persistant&nbsp;? Appelez-nous au
				<a href="tel:+33235102449">02 35 10 24 49</a>, nous vous aidons volontiers.
			</p>
		</section>

	</div>
</main>

@include('templet.footer')