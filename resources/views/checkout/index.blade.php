@include('templet.header', ['titre' => 'Finaliser ma commande', 'title' => 'Checkout'])
@vite('resources/css/boutique.css')

<main style="max-width:700px;margin:2rem auto;padding:0 1rem;">

    <h2 style="font-family:var(--font-serif);margin-bottom:1.5rem;">Récapitulatif de commande</h2>

    @foreach($panier->lignes as $ligne)
        <div style="display:flex;justify-content:space-between;padding:.75rem 0;border-bottom:1px solid var(--color-border);">
            <div>
                <strong>{{ $ligne->produit->forme->nom_forme }} — {{ $ligne->produit->parfum->nom_parfum }}</strong>
                <span style="color:var(--color-text-muted);font-size:.85rem;margin-left:.5rem;">× {{ $ligne->quantite }}</span>
            </div>
            <span>{{ number_format($ligne->prix_unitaire * $ligne->quantite, 2, ',', ' ') }} €</span>
        </div>
    @endforeach

    <div style="display:flex;justify-content:space-between;padding:1rem 0;font-size:1.2rem;font-weight:700;">
        <span>Total</span>
        <span style="color:var(--color-primary);">{{ number_format($panier->total(), 2, ',', ' ') }} €</span>
    </div>

    <form action="{{ route('checkout.payer') }}" method="POST" style="margin-top:1rem;">
        @csrf
        <button type="submit"
                style="width:100%;padding:1rem;background:var(--color-primary);color:#fff;border:none;border-radius:8px;font-size:1.05rem;font-weight:700;cursor:pointer;">
            Payer {{ number_format($panier->total(), 2, ',', ' ') }} € en sécurisé →
        </button>
    </form>

    <p style="font-size:.75rem;color:var(--color-text-muted);text-align:center;margin-top:.75rem;">
        🔒 Paiement sécurisé par CIC Monetico
    </p>

</main>

@include('templet.footer')
