@include('templet.header', [
	'titre' => 'Mon Panier',
	'note'  => 'Meringues & Douceurs Artisanales',
	'title' => 'Panier'
])

@vite('resources/css/boutique.css')

<main class="boutique-body">

	<div style="max-width:900px;margin:0 auto;padding:var(--space-lg);">

		<h1 style="font-family:var(--font-serif);font-size:2rem;margin-bottom:var(--space-lg);color:var(--color-text);">
			🛒 Mon Panier
		</h1>

		{{-- Message succès --}}
		@if(session('success'))
			<div style="background:#d4edda;color:#155724;padding:var(--space-sm) var(--space-md);border-radius:8px;margin-bottom:var(--space-md);">
				{{ session('success') }}
			</div>
		@endif

		@if($panier->lignes->isEmpty())
			{{-- Panier vide --}}
			<div class="boutique-empty">
				<div class="boutique-empty__icon">🛒</div>
				<h2 class="boutique-empty__title">Votre panier est vide</h2>
				<p class="boutique-empty__text">Découvrez nos meringues artisanales et ajoutez-les à votre panier !</p>
				<a href="{{ route('shop.index', 1) }}"
				   style="display:inline-block;margin-top:var(--space-md);padding:var(--space-sm) var(--space-lg);background:var(--color-primary);color:#fff;border-radius:8px;text-decoration:none;font-weight:600;">
					Voir la boutique
				</a>
			</div>

		@else
			{{-- Lignes du panier --}}
			<div style="display:flex;flex-direction:column;gap:var(--space-md);">

				@foreach($panier->lignes as $ligne)
				<div style="display:flex;align-items:center;gap:var(--space-md);background:#fff;border-radius:12px;padding:var(--space-md);box-shadow:0 2px 8px rgba(0,0,0,.07);">

					{{-- Image --}}
					<div style="width:80px;height:80px;border-radius:8px;overflow:hidden;flex-shrink:0;background:#f5f0eb;">
						<img src="{{ asset($ligne->produit->image($ligne->id_forme_condi)) }}"
							 alt="{{ $ligne->produit->nom_produit }}"
							 style="width:100%;height:100%;object-fit:cover;"
							 onerror="this.style.display='none'">
					</div>

					{{-- Infos produit --}}
					<div style="flex:1;">
						<h3 style="font-family:var(--font-serif);font-size:1.1rem;margin:0 0 4px;">
							{{ $ligne->produit->forme->nom_forme }} — {{ $ligne->produit->parfum->nom_parfum }}
						</h3>
						<p style="color:var(--color-text-muted);font-size:.85rem;margin:0 0 4px;">
							{{ $ligne->formeCondi->conditionnement->type }}
						</p>
						<p style="color:var(--color-primary);font-weight:700;margin:0;">
							{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} € / unité
						</p>
					</div>

					{{-- Quantité --}}
					<form action="{{ route('panier.modifier', $ligne->id_ligne) }}" method="POST"
						  style="display:flex;align-items:center;gap:8px;">
						@csrf
						@method('PATCH')
						<input type="number" name="quantite" value="{{ $ligne->quantite }}"
							   min="1" max="99"
							   style="width:60px;padding:6px;border:1px solid var(--color-border);border-radius:6px;text-align:center;font-size:.95rem;">
						<button type="submit"
								style="padding:6px 12px;background:var(--color-primary);color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:.85rem;">
							✓
						</button>
					</form>

					{{-- Sous-total --}}
					<div style="min-width:80px;text-align:right;">
						<p style="font-weight:700;font-size:1.05rem;margin:0;">
							{{ number_format($ligne->prix_unitaire * $ligne->quantite, 2, ',', ' ') }} €
						</p>
					</div>

					{{-- Supprimer --}}
					<form action="{{ route('panier.supprimer', $ligne->id_ligne) }}" method="POST">
						@csrf
						@method('DELETE')
						<button type="submit"
								style="background:none;border:none;cursor:pointer;font-size:1.2rem;color:#ccc;"
								title="Supprimer"
								onclick="return confirm('Retirer ce produit du panier ?')">✕</button>
					</form>

				</div>
				@endforeach

			</div>

			{{-- Récapitulatif --}}
			<div style="margin-top:var(--space-lg);background:#fff;border-radius:12px;padding:var(--space-lg);box-shadow:0 2px 8px rgba(0,0,0,.07);">

				<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-md);">
					<span style="font-size:1.1rem;color:var(--color-text-muted);">Total</span>
					<span style="font-family:var(--font-serif);font-size:1.8rem;font-weight:700;color:var(--color-primary);">
						{{ number_format($panier->total(), 2, ',', ' ') }} €
					</span>
				</div>

				<div style="display:flex;gap:var(--space-md);flex-wrap:wrap;">

					{{-- Vider le panier --}}
					<form action="{{ route('panier.vider') }}" method="POST">
						@csrf
						<button type="submit"
								style="padding:var(--space-sm) var(--space-lg);background:none;border:2px solid var(--color-border);border-radius:8px;cursor:pointer;color:var(--color-text-muted);"
								onclick="return confirm('Vider tout le panier ?')">
							🗑 Vider le panier
						</button>
					</form>

					{{-- Continuer les achats --}}
					<a href="{{ route('shop.index', 1) }}"
					   style="padding:var(--space-sm) var(--space-lg);background:none;border:2px solid var(--color-primary);border-radius:8px;color:var(--color-primary);text-decoration:none;font-weight:600;">
						← Continuer mes achats
					</a>

					{{-- Commander --}}
					@auth
						<a href="{{ route('checkout.index') }}"
						   style="margin-left:auto;padding:var(--space-sm) var(--space-xl);background:var(--color-primary);color:#fff;border-radius:8px;text-decoration:none;font-weight:700;font-size:1.05rem;">
							Commander →
						</a>
					@else
						<a href="{{ route('login') }}"
						   style="margin-left:auto;padding:var(--space-sm) var(--space-xl);background:var(--color-primary);color:#fff;border-radius:8px;text-decoration:none;font-weight:700;font-size:1.05rem;">
							Se connecter pour commander →
						</a>
					@endauth

				</div>
			</div>

		@endif
	</div>

</main>

@include('templet.footer')

<script>
	const profileBtn = document.querySelector('.navbar__profile');
	const dropdown   = document.querySelector('.navbar__dropdown');
	if (profileBtn && dropdown) {
		profileBtn.addEventListener('click', function () {
			const isOpen = dropdown.classList.contains('open');
			dropdown.classList.toggle('open', !isOpen);
			profileBtn.setAttribute('aria-expanded', !isOpen);
		});
		document.addEventListener('click', function (e) {
			if (!profileBtn.contains(e.target)) {
				dropdown.classList.remove('open');
				profileBtn.setAttribute('aria-expanded', false);
			}
		});
	}
</script>

</body>
</html>
