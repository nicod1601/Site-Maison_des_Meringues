@foreach($images as $image)
<div class="image-card" data-search="{{ strtolower(($image->produit->nom_produit ?? '') . ' ' . ($image->formeCondi->forme->nom_forme ?? '') . ' ' . ($image->formeCondi->conditionnement->type ?? '')) }}">
    <div class="image-card__preview" onclick="ouvrirLightbox('{{ asset($image->url) }}', '{{ $image->produit->nom_produit ?? 'Image #'.$image->id_image }}')">
        <img
            src="{{ asset($image->url) }}"
            alt="{{ $image->produit->nom_produit ?? 'Image produit' }}"
            loading="lazy"
            onerror="
                this.style.display='none';
                this.nextElementSibling.style.display='none';
                this.closest('.image-card__preview').querySelector('.image-error-placeholder').style.display='flex';
            "
        >
        <div class="image-card__overlay"><span>🔍 Voir</span></div>
        <div class="image-error-placeholder" style="display:none;">
            <span>⚠️</span><span>Image introuvable</span>
        </div>
    </div>
    <div class="image-card__body">
        <p class="image-card__name">{{ $image->produit->nom_produit ?? '— Produit #'.$image->id_produit }}</p>
        @if($image->formeCondi)
            <div class="image-card__meta">
                <span class="chip">{{ $image->formeCondi->forme->nom_forme ?? '—' }}</span>
                <span class="chip">{{ $image->formeCondi->conditionnement->type ?? '—' }}</span>
            </div>
        @endif
        <p class="image-card__path" title="{{ $image->url }}">
            <span class="path-icon">🔗</span>{{ $image->url }}
        </p>
        <div class="image-card__actions">
            <button
                class="btn-icon btn-icon--edit"
                title="Remplacer l'image (conserve le lien)"
                onclick="ouvrirModalRemplaceImage({{ $image->id_image }}, '{{ asset($image->url) }}', '{{ $image->url }}', '{{ $image->produit->nom_produit ?? '' }}')"
            >🔄</button>
            <button
                class="btn-icon btn-icon--copy"
                title="Copier le lien"
                onclick="copierLien('{{ $image->url }}')"
            >📋</button>
            <form action="{{ route('image.destroy', $image->id_image) }}" method="POST" class="form-delete" onsubmit="return confirmSuppr('cette image')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-icon btn-icon--delete" title="Supprimer">🗑️</button>
            </form>
        </div>
    </div>
</div>
@endforeach
