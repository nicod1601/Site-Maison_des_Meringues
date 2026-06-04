@include('templet.header', ['titre' => 'Paiement refusé', 'title' => 'Erreur paiement'])

<main style="max-width:600px;margin:3rem auto;text-align:center;padding:0 1rem;">
	<div style="font-size:4rem;">❌</div>
	<h2 style="font-family:var(--font-serif);margin:1rem 0;">Paiement non abouti</h2>
	<p style="color:var(--color-text-muted);">
		Votre paiement n'a pas pu être traité. Votre panier est conservé.
	</p>
	<a href="{{ route('panier.index') }}"
	   style="display:inline-block;margin-top:2rem;padding:.75rem 2rem;background:var(--color-primary);color:#fff;border-radius:8px;text-decoration:none;font-weight:700;">
		Retour au panier
	</a>
</main>

@include('templet.footer')
