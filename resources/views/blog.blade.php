@include('templet.header', [
    'titre' => 'Blog',
    'note'  => 'Douceurs Artisanales',
    'title' => 'Blog'
])

@vite(['resources/css/blog.css'])

<main class="blog-body">

    {{-- TOOLBAR ADMIN --}}
    @if($isAdmin)
    <div class="blog-toolbar" id="adminToolbar">
        <div>
            <h2 class="blog-toolbar__title">Toutes les annonces</h2>
            <p style="font-size:0.875rem;color:var(--color-text-muted);margin-top:4px;">
                <span id="postCount">{{ count($posts) }}</span> publication(s)
            </p>
        </div>
        <button class="btn-new-post" onclick="openModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Nouvelle annonce
        </button>
    </div>
    @endif

    {{-- GRILLE --}}
    <div class="posts-grid" id="postsGrid">
        @forelse($posts as $post)
            <article class="post-card" data-id="{{ $post['id'] }}">
                <div class="post-card__img-wrap">
                    @if($post['image_url'])
                        <img class="post-card__img"
                             src="{{ $post['image_url'] }}"
                             alt="{{ $post['title'] }}"
                             onerror="this.parentElement.innerHTML='<div class=\'post-card__img-placeholder\'>{{ $post['emoji'] }}</div>'">
                    @else
                        <div class="post-card__img-placeholder">{{ $post['emoji'] }}</div>
                    @endif

                    <span class="post-card__category">{{ $post['category'] }}</span>

                    @if($isAdmin)
                    <div class="post-card__admin-actions">
                        <button class="post-card__admin-btn post-card__admin-btn--edit"
                                onclick="editPost({{ json_encode($post) }})"
                                title="Modifier">✏️</button>
                        <button class="post-card__admin-btn post-card__admin-btn--delete"
                                onclick="deletePost({{ $post['id'] }})"
                                title="Supprimer">🗑</button>
                    </div>
                    @endif
                </div>

                <div class="post-card__body">
                    <div class="post-card__meta">
                        <span>{{ $post['date'] }}</span>
                        <span class="post-card__meta-sep">·</span>
                        <span>Par {{ $post['author'] }}</span>
                    </div>
                    <h3 class="post-card__title">{{ $post['title'] }}</h3>
                    <p class="post-card__excerpt">{{ $post['content'] }}</p>
                </div>

                <div class="post-card__footer">
                    <div class="post-card__author">
                        <div class="post-card__author-avatar">
                            {{ strtoupper(substr($post['author'], 0, 2)) }}
                        </div>
                        <span class="post-card__author-name">{{ $post['author'] }}</span>
                    </div>

                    <div class="post-card__reactions">
                        {{-- Like --}}
                        <button class="reaction-btn {{ $post['userReaction'] === 'like' ? 'liked' : '' }}"
                                onclick="react({{ $post['id'] }}, 'like')"
                                @guest disabled title="Connectez-vous pour réagir" @endguest>
                            <svg viewBox="0 0 24 24"
                                 fill="{{ $post['userReaction'] === 'like' ? 'var(--color-primary)' : 'none' }}"
                                 stroke="var(--color-primary)" stroke-width="2">
                                <path d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.3a2 2 0 002-1.7l1.4-9a2 2 0 00-2-2.3H14z"/>
                                <path d="M7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3"/>
                            </svg>
                            <span class="like-count-{{ $post['id'] }}">{{ $post['likes'] }}</span>
                        </button>

                        {{-- Dislike --}}
                        <button class="reaction-btn {{ $post['userReaction'] === 'dislike' ? 'disliked' : '' }}"
                                onclick="react({{ $post['id'] }}, 'dislike')"
                                @guest disabled title="Connectez-vous pour réagir" @endguest>
                            <svg viewBox="0 0 24 24"
                                 fill="{{ $post['userReaction'] === 'dislike' ? 'var(--color-primary-dark)' : 'none' }}"
                                 stroke="var(--color-primary-dark)" stroke-width="2">
                                <path d="M10 15v4a3 3 0 003 3l4-9V2H5.7a2 2 0 00-2 1.7l-1.4 9a2 2 0 002 2.3H10z"/>
                                <path d="M17 2h2.3A2 2 0 0121 4v7a2 2 0 01-2 2H17"/>
                            </svg>
                            <span class="dislike-count-{{ $post['id'] }}">{{ $post['dislikes'] }}</span>
                        </button>
                    </div>
                </div>
            </article>
        @empty
            <div class="blog-empty visible">
                <span class="blog-empty__icon">🍡</span>
                <h3 class="blog-empty__title">Aucune annonce pour l'instant</h3>
                @if($isAdmin)
                    <p>Publiez votre première annonce en cliquant sur « Nouvelle annonce ».</p>
                @else
                    <p>Revenez bientôt pour découvrir nos actualités !</p>
                @endif
            </div>
        @endforelse
    </div>

</main>

{{-- MODAL CRÉATION / ÉDITION (admin seulement) --}}
@if($isAdmin)
<div class="modal-backdrop" id="modalBackdrop" onclick="handleBackdropClick(event)">
    <div class="modal">
        <button class="modal__close" onclick="closeModal()">✕</button>
        <h2 class="modal__title" id="modalTitle">Nouvelle annonce</h2>
        <p class="modal__sub" id="modalSub">Rédigez votre annonce — elle sera visible par tous vos clients.</p>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Titre *</label>
                <input class="form-input" id="fieldTitle" type="text" placeholder="Ex : Nouvelle saveur d'été…">
            </div>
            <div class="form-group">
                <label class="form-label">Catégorie</label>
                <select class="form-select" id="fieldCategory">
                    <option value="Nouveauté">🌸 Nouveauté</option>
                    <option value="Événement">🎉 Événement</option>
                    <option value="Promo">🏷️ Promo</option>
                    <option value="Recette">👩‍🍳 Recette</option>
                    <option value="Actualité">📰 Actualité</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Contenu *</label>
            <textarea class="form-textarea" id="fieldContent" placeholder="Rédigez votre annonce ici…"></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Emoji / Illustration</label>
                <select class="form-select" id="fieldEmoji">
                    <option value="🍬">🍬 Meringue</option>
                    <option value="🌸">🌸 Fleur</option>
                    <option value="🎉">🎉 Fête</option>
                    <option value="✨">✨ Magie</option>
                    <option value="🍋">🍋 Citron</option>
                    <option value="🍓">🍓 Fraise</option>
                    <option value="🌹">🌹 Rose</option>
                    <option value="🍡">🍡 Friandise</option>
                    <option value="📦">📦 Colis</option>
                    <option value="🎁">🎁 Cadeau</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Image URL (optionnel)</label>
                <input class="form-input" id="fieldImage" type="url" placeholder="https://…">
            </div>
        </div>

        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeModal()">Annuler</button>
            <button class="btn-publish" onclick="publishPost()">
                <span id="publishBtnLabel">Publier</span>
            </button>
        </div>
    </div>
</div>
@endif

{{-- TOAST --}}
<div class="toast" id="toast">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <polyline points="20 6 9 17 4 12"/>
    </svg>
    <span id="toastMsg">Publié avec succès !</span>
</div>

<script>
const IS_ADMIN = @json($isAdmin);
const IS_AUTH  = @json(auth()->check());

// URLs des routes Laravel (injectées proprement côté PHP)
const ROUTES = {
    store:   '{{ route('blog.store') }}',
    update:  (id) => `/blog/${id}`,
    destroy: (id) => `/blog/${id}`,
    react:   (id) => `/blog/${id}/react`,
};

// Token CSRF pour les requêtes fetch
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

let editingId = null;

/* ══════════════════════════════
   RÉACTIONS
══════════════════════════════ */
async function react(postId, type) {
    if (!IS_AUTH) {
        showToast('⚠️ Connectez-vous pour réagir.');
        return;
    }

    const res = await fetch(ROUTES.react(postId), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ type }),
    });

    if (!res.ok) return;
    const data = await res.json();

    // Mise à jour des compteurs sans rechargement
    const article = document.querySelector(`.post-card[data-id="${postId}"]`);
    if (!article) return;

    article.querySelector(`.like-count-${postId}`).textContent    = data.likes;
    article.querySelector(`.dislike-count-${postId}`).textContent = data.dislikes;

    const [likeBtn, dislikeBtn] = article.querySelectorAll('.reaction-btn');

    likeBtn.classList.toggle('liked',       data.userReaction === 'like');
    dislikeBtn.classList.toggle('disliked', data.userReaction === 'dislike');

    // Met à jour le fill des SVG
    likeBtn.querySelector('svg').setAttribute('fill',
        data.userReaction === 'like' ? 'var(--color-primary)' : 'none');
    dislikeBtn.querySelector('svg').setAttribute('fill',
        data.userReaction === 'dislike' ? 'var(--color-primary-dark)' : 'none');
}

/* ══════════════════════════════
   MODAL
══════════════════════════════ */
function openModal(id = null) {
    editingId = id;

    if (id) {
        document.getElementById('modalTitle').textContent       = "Modifier l'annonce";
        document.getElementById('modalSub').textContent         = 'Modifiez les informations de votre annonce.';
        document.getElementById('publishBtnLabel').textContent  = 'Enregistrer';
    } else {
        document.getElementById('modalTitle').textContent       = 'Nouvelle annonce';
        document.getElementById('modalSub').textContent         = 'Rédigez votre annonce — elle sera visible par tous vos clients.';
        document.getElementById('publishBtnLabel').textContent  = 'Publier';
        document.getElementById('fieldTitle').value    = '';
        document.getElementById('fieldContent').value = '';
        document.getElementById('fieldImage').value   = '';
        document.getElementById('fieldEmoji').value   = '🍬';
        document.getElementById('fieldCategory').value = 'Nouveauté';
    }

    document.getElementById('modalBackdrop').classList.add('open');
}

function editPost(post) {
    openModal(post.id);
    document.getElementById('fieldTitle').value    = post.title;
    document.getElementById('fieldCategory').value = post.category;
    document.getElementById('fieldContent').value  = post.content;
    document.getElementById('fieldEmoji').value    = post.emoji;
    document.getElementById('fieldImage').value    = post.image_url ?? '';
}

function closeModal() {
    document.getElementById('modalBackdrop').classList.remove('open');
    editingId = null;
}

function handleBackdropClick(e) {
    if (e.target === document.getElementById('modalBackdrop')) closeModal();
}

/* ══════════════════════════════
   PUBLIER / MODIFIER
══════════════════════════════ */
async function publishPost() {
    const title   = document.getElementById('fieldTitle').value.trim();
    const content = document.getElementById('fieldContent').value.trim();

    if (!title || !content) {
        showToast('⚠️ Titre et contenu obligatoires.');
        return;
    }

    const payload = {
        title,
        category:  document.getElementById('fieldCategory').value,
        content,
        emoji:     document.getElementById('fieldEmoji').value,
        image_url: document.getElementById('fieldImage').value.trim() || null,
    };

    const isEdit = !!editingId;
    const url    = isEdit ? ROUTES.update(editingId) : ROUTES.store;
    const method = isEdit ? 'PUT' : 'POST';

    const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify(payload),
    });

    const data = await res.json();
    if (!data.success) { showToast('❌ Une erreur est survenue.'); return; }

    closeModal();
    showToast(isEdit ? '✅ Annonce modifiée !' : '✅ Annonce publiée !');

    // Recharge la page pour afficher le post depuis la BDD
    setTimeout(() => location.reload(), 900);
}

/* ══════════════════════════════
   SUPPRIMER
══════════════════════════════ */
async function deletePost(id) {
    if (!confirm('Supprimer cette annonce définitivement ?')) return;

    const res = await fetch(ROUTES.destroy(id), {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF },
    });

    const data = await res.json();
    if (!data.success) { showToast('❌ Une erreur est survenue.'); return; }

    document.querySelector(`.post-card[data-id="${id}"]`)?.remove();
    const count = document.getElementById('postCount');
    if (count) count.textContent = parseInt(count.textContent) - 1;

    showToast('🗑 Annonce supprimée.');
}

/* ══════════════════════════════
   TOAST
══════════════════════════════ */
let toastTimer;
function showToast(msg) {
    const t = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    t.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => t.classList.remove('show'), 3200);
}
</script>
