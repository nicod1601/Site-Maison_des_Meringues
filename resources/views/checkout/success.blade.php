@include('templet.header', ['titre' => 'Merci pour votre commande !', 'title' => 'Commande confirmée'])

<main style="max-width:600px;margin:3rem auto;text-align:center;padding:0 1rem;">
	<div style="font-size:4rem;">🎉</div>
	<h2 style="font-family:var(--font-serif);margin:1rem 0;">Commande confirmée !</h2>
	<p style="color:var(--color-text-muted);">
		Votre paiement a bien été reçu. Vous allez recevoir un email de confirmation.
	</p>
	<a href="{{ route('shop.index', 1) }}"
	   style="display:inline-block;margin-top:2rem;padding:.75rem 2rem;background:var(--color-primary);color:#fff;border-radius:8px;text-decoration:none;font-weight:700;">
		Retour à la boutique
	</a>
</main>

@include('templet.footer')
