@include('templet.header', ['titre' => 'Récapitulatif de commande', 'title' => 'Checkout'])
@vite('resources/css/boutique.css')

@php
	$expeditionAutorisee = $panier->lignes->every(function ($ligne) {
		$formeCondi = $ligne->formeCondi;
		$nomForme   = strtolower($formeCondi->forme->nom_forme ?? '');
		$typeCondi  = strtolower($formeCondi->conditionnement->type ?? '');

		return str_contains($nomForme, 'mini')
			&& $typeCondi === 'individuel';
	});
@endphp

<main style="max-width:700px;margin:2rem auto;padding:0 1rem;">

	<h2 style="font-family:var(--font-serif);margin-bottom:1.5rem;">Ma Liste</h2>

	{{-- Lignes du panier --}}
	@foreach($panier->lignes as $ligne)
		<div style="display:flex;justify-content:space-between;padding:.75rem 0;border-bottom:1px solid var(--color-border);">
			<div>
				<strong>{{ $ligne->produit->forme->nom_forme }} — {{ $ligne->produit->parfum->nom_parfum }}</strong>
				<span style="color:var(--color-text-muted);font-size:.85rem;margin-left:.5rem;">× {{ $ligne->quantite }}</span>
			</div>
			<span>{{ number_format($ligne->prix_unitaire * $ligne->quantite, 2, ',', ' ') }} €</span>
		</div>
	@endforeach

	{{-- Total --}}
	<div style="display:flex;justify-content:space-between;padding:1rem 0;font-size:1.2rem;font-weight:700;">
		<span>Total</span>
		<span style="color:var(--color-primary);">{{ number_format($panier->total(), 2, ',', ' ') }} €</span>
	</div>

	{{-- ─── Mode de réception ─────────────────────────────── --}}
	<form id="form-checkout" action="{{ route('checkout.payer') }}" method="POST">
		@csrf
		<input type="hidden" name="mode_livraison" id="mode_livraison" value="livraison">

		<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:1rem;">

			{{-- Livraison locale — toujours disponible --}}
			<div class="mode-card active" data-value="livraison" onclick="choisirMode('livraison')"
				style="border:1.5px solid var(--color-primary);border-radius:8px;padding:1rem;cursor:pointer;background:#fff;">
				<strong>🚲 Livraison locale</strong>
				<p style="font-size:.8rem;color:var(--color-text-muted);margin:.5rem 0 0;">
					Livraison en main propre ou locale. Toujours disponible.
				</p>
			</div>

			{{-- Expédition — mini individuel uniquement --}}
			<div class="mode-card {{ $expeditionAutorisee ? '' : 'disabled' }}"
				data-value="expedition"
				onclick="{{ $expeditionAutorisee ? 'choisirMode(\'expedition\')' : '' }}"
				style="border:1.5px solid var(--color-border);border-radius:8px;padding:1rem;
						cursor:{{ $expeditionAutorisee ? 'pointer' : 'not-allowed' }};
						opacity:{{ $expeditionAutorisee ? '1' : '.5' }};
						background:{{ $expeditionAutorisee ? '#fff' : 'var(--color-bg-muted,#f5f5f5)' }};">
				<strong>📦 Expédition</strong>
				@unless($expeditionAutorisee)
					<span style="font-size:.75rem;color:var(--color-danger,#c0392b);margin-left:.5rem;">Non disponible</span>
				@endunless
				<p style="font-size:.8rem;color:var(--color-text-muted);margin:.5rem 0 0;">
					@if($expeditionAutorisee)
						Envoi postal sécurisé.
					@else
						Réservée aux commandes de meringues mini individuelles uniquement.
					@endif
				</p>
			</div>

		</div>

		@unless($expeditionAutorisee)
			<p style="font-size:.75rem;color:var(--color-text-muted);margin-bottom:1rem;">
				ℹ️ L'expédition postale n'est possible que si votre panier contient
				uniquement des meringues au format <strong>mini individuel</strong>.
			</p>
		@endunless

		<button type="submit"
				style="width:100%;padding:1rem;background:var(--color-primary);color:#fff;border:none;border-radius:8px;font-size:1.05rem;font-weight:700;cursor:pointer;">
			Payer {{ number_format($panier->total(), 2, ',', ' ') }} € en sécurisé →
		</button>

	</form>

	<p style="font-size:.75rem;color:var(--color-text-muted);text-align:center;margin-top:.75rem;">
		🔒 Paiement sécurisé par CIC Monetico
	</p>

</main>

<script>
function choisirMode(valeur) {
	document.getElementById('mode_livraison').value = valeur;

	document.querySelectorAll('.mode-card:not(.disabled)').forEach(card => {
		const actif = card.dataset.value === valeur;
		card.style.borderColor = actif ? 'var(--color-primary)' : 'var(--color-border)';
	});
}
</script>

@include('templet.footer')
