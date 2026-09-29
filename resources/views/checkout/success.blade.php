@include('templet.header', [
	'titre' => 'Merci pour votre commande !',
	'note'  => 'Votre paiement a bien été reçu',
	'title' => 'Commande confirmée',
])

@vite('resources/css/checkout.css')
@include('checkout._icons')

<main class="co">
	<div class="co__inner">

		@include('checkout._steps', ['etape' => 3])

		<section class="co-result co-result--success" aria-labelledby="co-result-title">
			<div class="co-result__icon">
				<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path class="co-result__draw" d="M12 25l8 8 16-18"/>
				</svg>
			</div>

			<h2 class="co-result__title" id="co-result-title">Commande confirmée</h2>
			<p class="co-result__text">
				Merci pour votre confiance&nbsp;! Votre paiement a bien été reçu et notre atelier
				s'occupe de préparer vos meringues avec soin.
			</p>

			@if($reference = session('monetico_reference'))
				<p class="co-result__ref">
					{!! coicon('receipt') !!} Référence <strong>{{ $reference }}</strong>
				</p>
			@endif

			<p class="co-result__hint">
				Retrouvez votre commande à tout moment dans «&nbsp;Mes commandes&nbsp;».
				Elle peut mettre quelques instants à apparaître, le temps que la banque valide le paiement.
			</p>

			<div class="co-result__actions">
				<a href="{{ route('ticketCommande') }}" class="co-btn co-btn--primary">
					{!! coicon('receipt') !!} Voir mes commandes
				</a>
				<a href="{{ route('shop.index', 1) }}" class="co-btn co-btn--ghost">
					Retour à la boutique
				</a>
			</div>
		</section>

	</div>
</main>

@include('templet.footer')