@include('templet.header', [
	'titre' => 'Blog',
	'note'  => 'Douceurs Artisanales',
	'title' => 'Blog'
])

@vite(['resources/css/blog.css'])

<div class="blog-body">

	{{-- ══════════════════════════════
		 SIDEBAR GAUCHE
	══════════════════════════════ --}}
	<aside class="blog-sidebar">

		{{-- Carte profil --}}
		<div class="sidebar-card">
			<div class="sidebar-banner"></div>
			<div class="sidebar-profile">
				<div class="sidebar-avatar"><img src="{{ asset('fichier/image/logo.webp') }}" alt="Logo"></div>
				<div class="sidebar-name">La Maison<br>des Meringues</div>
				<div class="sidebar-tagline">Meringue Normandie - Ypreville</div>

				<div class="sidebar-stats">
					<div class="sidebar-stat">
						<div class="sidebar-stat__value" id="postCount">{{ count($posts) }}</div>
						<div class="sidebar-stat__label">Posts</div>
					</div>
					<div class="sidebar-stat">
						<div class="sidebar-stat__value">
							{{ collect($posts)->sum('likes') }}
						</div>
						<div class="sidebar-stat__label">Likes</div>
					</div>
					<div class="sidebar-stat">
						<div class="sidebar-stat__value">
							{{ collect($posts)->pluck('category')->unique()->count() }}
						</div>
						<div class="sidebar-stat__label">Catégs</div>
					</div>
				</div>
			</div>
		</div>

	</aside>

	{{-- ══════════════════════════════
		 FEED CENTRAL
	══════════════════════════════ --}}
	<main class="posts-grid" id="postsGrid">

		{{-- Barre "créer une publication" (admin) --}}
		@if($isAdmin)
		<div class="blog-toolbar" id="adminToolbar">
			<div class="blog-toolbar__avatar">🍬</div>
			<div class="blog-toolbar__fake-input" onclick="openModal()">
				Publier une nouvelle annonce…
			</div>
			<button class="btn-new-post" onclick="openModal()">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
					<line x1="12" y1="5" x2="12" y2="19"/>
					<line x1="5" y1="12" x2="19" y2="12"/>
				</svg>
				Publier
			</button>
		</div>
		@endif

		{{-- Posts --}}
		@forelse($posts as $post)
		<article class="post-card" data-id="{{ $post['id'] }}">

			{{-- Header du post --}}
			<div class="post-card__header">
				<div class="post-card__header-avatar">
					{{ strtoupper(substr($post['author'], 0, 2)) }}
				</div>
				<div class="post-card__header-info">
					<div class="post-card__header-name">{{ $post['author'] }}</div>
					<div class="post-card__header-meta">
						<span>{{ $post['date'] }}</span>
						<span class="post-card__header-meta-sep">·</span>
						<span class="post-card__category-pill">{{ $post['category'] }}</span>
					</div>
				</div>

				{{-- Actions admin --}}
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

			{{-- Texte --}}
			<div class="post-card__body">
				<h3 class="post-card__title">{{ $post['title'] }}</h3>
				<p class="post-card__excerpt">{{ $post['content'] }}</p>
			</div>

			{{-- Image (pleine largeur, sous le texte, comme FB) --}}
			@if($post['image_display'])
			<div class="post-card__img-wrap">
				<img class="post-card__img"
					 src="{{ $post['image_display'] }}"
					 alt="{{ $post['title'] }}"
					 onerror="this.parentElement.innerHTML='<div class=\'post-card__img-placeholder\'>{{ $post['emoji'] }}</div>'">
			</div>
			@endif

			{{-- Footer : réactions --}}
			<div class="post-card__footer">
				<div class="post-card__reactions">
					{{-- Like --}}
					<button class="reaction-btn {{ $post['userReaction'] === 'like' ? 'liked' : '' }}"
							onclick="react({{ $post['id'] }}, 'like')"
							@guest disabled title="Connectez-vous pour réagir" @endguest>
						<svg viewBox="0 0 24 24"
							 fill="{{ $post['userReaction'] === 'like' ? 'var(--c-rose)' : 'none' }}"
							 stroke="currentColor" stroke-width="2">
							<path d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.3a2 2 0 002-1.7l1.4-9a2 2 0 00-2-2.3H14z"/>
							<path d="M7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3"/>
						</svg>
						<span class="like-count-{{ $post['id'] }}">{{ $post['likes'] }}</span>
						J'aime
					</button>

					<div class="reaction-sep"></div>

					{{-- Dislike --}}
					<button class="reaction-btn {{ $post['userReaction'] === 'dislike' ? 'disliked' : '' }}"
							onclick="react({{ $post['id'] }}, 'dislike')"
							@guest disabled title="Connectez-vous pour réagir" @endguest>
						<svg viewBox="0 0 24 24"
							 fill="{{ $post['userReaction'] === 'dislike' ? 'var(--c-rose-dark)' : 'none' }}"
							 stroke="currentColor" stroke-width="2">
							<path d="M10 15v4a3 3 0 003 3l4-9V2H5.7a2 2 0 00-2 1.7l-1.4 9a2 2 0 002 2.3H10z"/>
							<path d="M17 2h2.3A2 2 0 0121 4v7a2 2 0 01-2 2H17"/>
						</svg>
						<span class="dislike-count-{{ $post['id'] }}">{{ $post['dislikes'] }}</span>
						Pas pour moi
					</button>
				</div>

				<div class="post-card__author">
					<span class="post-card__author-name">{{ $post['emoji'] }}</span>
				</div>
			</div>

		</article>
		@empty
			<div class="blog-empty visible">
				<span class="blog-empty__icon">🍡</span>
				<h3 class="blog-empty__title">Aucune annonce pour l'instant</h3>
				@if($isAdmin)
					<p>Publiez votre première annonce en cliquant sur « Publier ».</p>
				@else
					<p>Revenez bientôt pour découvrir nos actualités !</p>
				@endif
			</div>
		@endforelse

	</main>

	{{-- ══════════════════════════════
		 SIDEBAR DROITE
	══════════════════════════════ --}}
	<aside class="blog-aside">

		{{-- Widget catégories --}}
		<div class="aside-widget">
			<div class="aside-widget__title">Catégories</div>
			<ul class="aside-category-list">
				@php
					$categories = collect($posts)->groupBy('category');
				@endphp
				@foreach($categories as $cat => $items)
				<li onclick="filterByCategory('{{ $cat }}')">
					{{ $cat }}
					<span>{{ count($items) }}</span>
				</li>
				@endforeach
				@if($categories->isEmpty())
					<li style="opacity:.5;cursor:default;">Aucune catégorie</li>
				@endif
			</ul>
		</div>

		{{-- Widget à propos --}}
		<div class="aside-widget">
			<div class="aside-widget__title">À propos</div>
			<div class="aside-about">
				<div class="aside-about__ornament">
					<div class="aside-about__dot"></div>
					<div class="aside-about__line"></div>
				</div>
				<p>Création des meringues fait maison. Les Meringues sont fait avec des ingrédients de de très bonne qualité et fait avec
					beaucoup d'amour. Nous sommes situé en Normandie, plus précisément à Ypreville. Nous espérons que nos meringues vous
					plairont !
				</p>
			</div>
		</div>

	</aside>

</div>

{{-- ══════════════════════════════
	 MODAL (admin seulement)
══════════════════════════════ --}}
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
				<label class="form-label">Image (optionnel)</label>
				<div class="img-tabs" id="imgTabs">
					<button type="button" class="img-tab img-tab--active" id="tabUrl"  onclick="switchImageTab('url')">🔗 Lien URL</button>
					<button type="button" class="img-tab"                 id="tabFile" onclick="switchImageTab('file')">📁 Importer</button>
				</div>
				<div id="panelUrl">
					<input class="form-input" id="fieldImage" type="url" placeholder="https://…">
				</div>
				<div id="panelFile" style="display:none;">
					<label class="file-drop-zone" id="fileDropZone" for="fieldImageFile">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="28" height="28" style="margin-bottom:5px;opacity:.5">
							<path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
							<polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
						</svg>
						<span id="fileDropLabel">Cliquer ou déposer une image ici</span>
						<span style="font-size:.72rem;opacity:.45;margin-top:2px;">JPG, PNG, GIF, WEBP — max 4 Mo</span>
						<input type="file" id="fieldImageFile" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none" onchange="handleFileSelect(this)">
					</label>
					<div id="filePreviewWrap" style="display:none;margin-top:8px;text-align:center;">
						<img id="filePreview" src="" alt="Prévisualisation"
							 style="max-height:110px;max-width:100%;border-radius:8px;object-fit:cover;">
						<button type="button" onclick="clearFileInput()"
								style="display:block;margin:4px auto 0;font-size:.78rem;color:#c0395a;background:none;border:none;cursor:pointer;">
							✕ Supprimer
						</button>
					</div>
				</div>
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

const ROUTES = {
	store:   '{{ route('blog.store') }}',
	update:  (id) => `/blog/${id}`,
	destroy: (id) => `/blog/${id}`,
	react:   (id) => `/blog/${id}/react`,
};

const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
let editingId      = null;
let activeImageTab = 'url';

/* ── Filtrer par catégorie ── */
function filterByCategory(cat) {
	document.querySelectorAll('.post-card').forEach(card => {
		const pill = card.querySelector('.post-card__category-pill');
		card.style.display = (!pill || pill.textContent.trim() === cat) ? '' : 'none';
	});
}

/* ── Onglets image ── */
function switchImageTab(tab) {
	activeImageTab = tab;
	document.getElementById('tabUrl').classList.toggle('img-tab--active',  tab === 'url');
	document.getElementById('tabFile').classList.toggle('img-tab--active', tab === 'file');
	document.getElementById('panelUrl').style.display  = tab === 'url'  ? '' : 'none';
	document.getElementById('panelFile').style.display = tab === 'file' ? '' : 'none';
	if (tab === 'url')  clearFileInput();
	if (tab === 'file') document.getElementById('fieldImage').value = '';
}

function handleFileSelect(input) {
	const file = input.files[0];
	if (!file) return;
	document.getElementById('fileDropLabel').textContent = file.name;
	const reader = new FileReader();
	reader.onload = (e) => {
		document.getElementById('filePreview').src = e.target.result;
		document.getElementById('filePreviewWrap').style.display = '';
	};
	reader.readAsDataURL(file);
}

function clearFileInput() {
	document.getElementById('fieldImageFile').value = '';
	document.getElementById('fileDropLabel').textContent = 'Cliquer ou déposer une image ici';
	document.getElementById('filePreviewWrap').style.display = 'none';
	document.getElementById('filePreview').src = '';
}

document.addEventListener('DOMContentLoaded', () => {
	const zone = document.getElementById('fileDropZone');
	if (!zone) return;
	zone.addEventListener('dragover',  (e) => { e.preventDefault(); zone.classList.add('drag-over'); });
	zone.addEventListener('dragleave', ()  => zone.classList.remove('drag-over'));
	zone.addEventListener('drop', (e) => {
		e.preventDefault(); zone.classList.remove('drag-over');
		const file = e.dataTransfer.files[0];
		if (file && file.type.startsWith('image/')) {
			const input = document.getElementById('fieldImageFile');
			const dt = new DataTransfer(); dt.items.add(file); input.files = dt.files;
			handleFileSelect(input);
		}
	});
});

/* ── Réactions ── */
async function react(postId, type) {
	if (!IS_AUTH) { showToast('⚠️ Connectez-vous pour réagir.'); return; }
	const res = await fetch(ROUTES.react(postId), {
		method: 'POST',
		headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
		body: JSON.stringify({ type }),
	});
	if (!res.ok) return;
	const data = await res.json();
	const article = document.querySelector(`.post-card[data-id="${postId}"]`);
	if (!article) return;
	article.querySelector(`.like-count-${postId}`).textContent    = data.likes;
	article.querySelector(`.dislike-count-${postId}`).textContent = data.dislikes;
	const [likeBtn, , dislikeBtn] = article.querySelectorAll('.reaction-btn');
	likeBtn.classList.toggle('liked',       data.userReaction === 'like');
	dislikeBtn.classList.toggle('disliked', data.userReaction === 'dislike');
	likeBtn.querySelector('svg').setAttribute('fill',    data.userReaction === 'like'    ? 'var(--c-rose)' : 'none');
	dislikeBtn.querySelector('svg').setAttribute('fill', data.userReaction === 'dislike' ? 'var(--c-rose-dark)' : 'none');
}

/* ── Modal ── */
function openModal(id = null) {
	editingId = id;
	if (id) {
		document.getElementById('modalTitle').textContent      = "Modifier l'annonce";
		document.getElementById('modalSub').textContent        = 'Modifiez les informations de votre annonce.';
		document.getElementById('publishBtnLabel').textContent = 'Enregistrer';
	} else {
		document.getElementById('modalTitle').textContent      = 'Nouvelle annonce';
		document.getElementById('modalSub').textContent        = 'Rédigez votre annonce — elle sera visible par tous vos clients.';
		document.getElementById('publishBtnLabel').textContent = 'Publier';
		document.getElementById('fieldTitle').value    = '';
		document.getElementById('fieldContent').value = '';
		document.getElementById('fieldImage').value   = '';
		document.getElementById('fieldEmoji').value   = '🍬';
		document.getElementById('fieldCategory').value = 'Nouveauté';
		clearFileInput(); switchImageTab('url');
	}
	document.getElementById('modalBackdrop').classList.add('open');
}

function editPost(post) {
	openModal(post.id);
	document.getElementById('fieldTitle').value    = post.title;
	document.getElementById('fieldCategory').value = post.category;
	document.getElementById('fieldContent').value  = post.content;
	document.getElementById('fieldEmoji').value    = post.emoji;
	if (post.image_path) {
		switchImageTab('file');
		document.getElementById('fileDropLabel').textContent = '📎 Image existante — sélectionner pour remplacer';
	} else {
		switchImageTab('url');
		document.getElementById('fieldImage').value = post.image_url ?? '';
	}
}

function closeModal() {
	document.getElementById('modalBackdrop').classList.remove('open');
	editingId = null;
}

function handleBackdropClick(e) {
	if (e.target === document.getElementById('modalBackdrop')) closeModal();
}

/* ── Publier / Modifier ── */
async function publishPost() {
	const title   = document.getElementById('fieldTitle').value.trim();
	const content = document.getElementById('fieldContent').value.trim();
	if (!title || !content) { showToast('⚠️ Titre et contenu obligatoires.'); return; }

	const isEdit = !!editingId;
	const url    = isEdit ? ROUTES.update(editingId) : ROUTES.store;
	const fd     = new FormData();
	fd.append('title',    title);
	fd.append('category', document.getElementById('fieldCategory').value);
	fd.append('content',  content);
	fd.append('emoji',    document.getElementById('fieldEmoji').value);

	if (activeImageTab === 'url') {
		const imageUrl = document.getElementById('fieldImage').value.trim();
		if (imageUrl) fd.append('image_url', imageUrl);
	} else {
		const fileInput = document.getElementById('fieldImageFile');
		if (fileInput.files[0]) fd.append('image_file', fileInput.files[0]);
	}

	if (isEdit) fd.append('_method', 'PUT');

	const res  = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF }, body: fd });
	const data = await res.json();
	if (!data.success) { showToast('❌ Une erreur est survenue.'); return; }

	closeModal();
	showToast(isEdit ? '✅ Annonce modifiée !' : '✅ Annonce publiée !');
	setTimeout(() => location.reload(), 900);
}

/* ── Supprimer ── */
async function deletePost(id) {
	if (!confirm('Supprimer cette annonce définitivement ?')) return;
	const res  = await fetch(ROUTES.destroy(id), { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF } });
	const data = await res.json();
	if (!data.success) { showToast('❌ Une erreur est survenue.'); return; }
	document.querySelector(`.post-card[data-id="${id}"]`)?.remove();
	const count = document.getElementById('postCount');
	if (count) count.textContent = parseInt(count.textContent) - 1;
	showToast('🗑 Annonce supprimée.');
}

/* ── Toast ── */
let toastTimer;
function showToast(msg) {
	const t = document.getElementById('toast');
	document.getElementById('toastMsg').textContent = msg;
	t.classList.add('show');
	clearTimeout(toastTimer);
	toastTimer = setTimeout(() => t.classList.remove('show'), 3200);
}
</script>
