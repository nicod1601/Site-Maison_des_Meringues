<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex">
	<title>Redirection vers le paiement sécurisé — La Maison des Meringues</title>
	<style>
		*, *::before, *::after { box-sizing: border-box; }
		html, body { height: 100%; margin: 0; }
		body {
			display: grid;
			place-items: center;
			padding: 1.5rem;
			background:
				radial-gradient(800px 360px at 100% 0%, rgba(192, 57, 90, .08), transparent 70%),
				radial-gradient(700px 320px at 0% 100%, rgba(201, 168, 76, .10), transparent 70%),
				#FAF6EE;
			font-family: 'Lato', 'Helvetica Neue', Arial, sans-serif;
			color: #2E1A10;
		}
		.box {
			width: 100%;
			max-width: 380px;
			padding: 2.5rem 2rem 2.25rem;
			text-align: center;
			background: #fff;
			border: 1px solid #D9C49A;
			border-radius: 24px;
			box-shadow: 0 20px 60px rgba(192, 57, 90, .14), 0 8px 32px rgba(46, 26, 16, .10);
			animation: rise 500ms cubic-bezier(.22, 1, .36, 1) both;
		}
		.meringue {
			position: relative;
			width: 60px;
			height: 60px;
			margin: 0 auto 1.4rem;
			border-radius: 50%;
			background:
				radial-gradient(circle at 32% 28%, rgba(255, 255, 255, .6), rgba(255, 255, 255, 0) 42%),
				conic-gradient(from -20deg,
					#F3AFAE 0deg,   #E2572B 26deg,  #F6CBC7 52deg,  #D9481F 78deg,
					#F3AFAE 104deg, #E2572B 130deg, #F6CBC7 156deg, #D9481F 182deg,
					#F3AFAE 208deg, #E2572B 234deg, #F6CBC7 260deg, #D9481F 286deg,
					#F3AFAE 312deg, #E2572B 338deg, #F3AFAE 360deg);
			box-shadow: 0 8px 18px rgba(192, 57, 90, .28), inset 0 0 0 1px rgba(255, 255, 255, .35);
			animation: spin 1.6s linear infinite;
		}
		.meringue::after {
			content: '';
			position: absolute;
			top: 50%; left: 50%;
			width: 12px; height: 12px;
			border-radius: 50%;
			transform: translate(-50%, -50%);
			background: radial-gradient(circle at 34% 28%, #fff, #D9D0BC 55%, #A69A80 100%);
			box-shadow: 0 1px 3px rgba(26, 20, 16, .35);
		}
		h1 { margin: 0 0 .5rem; font-family: 'Playfair Display', Georgia, serif; font-size: 1.3rem; color: #7A1C3B; }
		p  { margin: 0; font-size: .88rem; line-height: 1.6; color: #6B4C3B; }
		.secure {
			display: inline-flex;
			align-items: center;
			gap: .4rem;
			margin-top: 1.4rem;
			font-size: .75rem;
			font-weight: 700;
			color: #2D6A4F;
		}
		.secure svg { width: 15px; height: 15px; }
		.fallback { margin-top: 1.4rem; }
		.fallback button {
			padding: .75rem 1.6rem;
			border: none;
			border-radius: 999px;
			background: linear-gradient(135deg, #C0395A, #7A1C3B);
			color: #fff;
			font: inherit;
			font-weight: 800;
			cursor: pointer;
		}
		@keyframes spin { to { transform: rotate(360deg); } }
		@keyframes rise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
		@media (prefers-reduced-motion: reduce) { .meringue { animation-duration: 3s; } .box { animation: none; } }
	</style>
</head>
<body>
	<div class="box" role="status" aria-live="polite">
		<div class="meringue" aria-hidden="true"></div>
		<h1>Redirection en cours…</h1>
		<p>Vous allez être redirigé vers la page de paiement sécurisée de la banque.<br>Merci de ne pas fermer cette page.</p>

		<span class="secure">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
			Paiement sécurisé CIC Monetico
		</span>

		<form id="monetico-form" action="{{ $url }}" method="POST">
			@foreach($fields as $name => $value)
				<input type="hidden" name="{{ $name }}" value="{{ $value }}">
			@endforeach

			{{-- Sans JavaScript, le client peut continuer manuellement --}}
			<noscript>
				<div class="fallback">
					<button type="submit">Continuer vers le paiement</button>
				</div>
			</noscript>
		</form>
	</div>

	<script>document.getElementById('monetico-form').submit();</script>
</body>
</html>
