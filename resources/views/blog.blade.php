@include('templet.header', [
	'titre' => 'Blog',
	'note'  => 'Douceurs Artisanales',
	'title' => 'Blog',
	'hideHeader' => true,
])

@vite(['resources/css/blog.css'])

@php
if (!function_exists('bicon')) {
	function bicon(string $name, string $class = ''): string {
		$icons = [
			'home'    => '<path d="m3 10 9-7 9 7"/><path d="M5 9v11h14V9"/><path d="M9 20v-6h6v6"/>',
			'bag'     => '<path d="M6 7h12l1 13a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2Z"/><path d="M9 7V5a3 3 0 0 1 6 0v2"/>',
			'brief'   => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
			'pen'     => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
			'trash'   => '<path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>',
			'x'       => '<path d="M18 6 6 18M6 6l12 12"/>',
			'link'    => '<path d="M9 17H7A5 5 0 0 1 7 7h2"/><path d="M15 7h2a5 5 0 1 1 0 10h-2"/><path d="M8 12h8"/>',
			'upload'  => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>',
			'inbox'   => '<path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11Z"/>',
			'check'   => '<path d="M20 6 9 17l-5-5"/>',
			'alert'   => '<path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4"/><path d="M12 17h.01"/>',
			'grid'    => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
			'lock'    => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
		];
		$path = $icons[$name] ?? '';
		return '<svg class="bicon ' . $class . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
	}
}
@endphp

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

		<nav class="sidebar-card sidebar-nav" aria-label="Navigation rapide">
			<a href="/" class="sidebar-nav__item">{!! bicon('home') !!} Accueil</a>
			<a href="{{ route('shop.index', 1) }}" class="sidebar-nav__item">{!! bicon('bag') !!} Boutique</a>
			<a href="/pro" class="sidebar-nav__item">{!! bicon('brief') !!} Professionnels</a>
		</nav>

	</aside>

	{{-- ══════════════════════════════
		 FEED CENTRAL
	══════════════════════════════ --}}
	<main class="posts-grid" id="postsGrid">

		{{-- Barre "créer une publication" (admin) --}}
		@if($isAdmin)
		<div class="blog-toolbar reveal" id="adminToolbar">
			<div class="blog-toolbar__avatar"><img src="{{ asset('fichier/image/logo.webp') }}" alt="Logo"></div>
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
		<article class="post-card reveal" data-id="{{ $post['id'] }}" data-category="{{ $post['category'] }}">

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
							aria-label="Modifier l'annonce" title="Modifier">
						{!! bicon('pen') !!}
					</button>

					<button class="post-card__admin-btn post-card__admin-btn--delete"
							onclick="deletePost({{ $post['id'] }})"
							aria-label="Supprimer l'annonce" title="Supprimer">
						{!! bicon('trash') !!}
					</button>
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
			<div class="blog-empty visible" id="blogEmptyOriginal">
				<span class="blog-empty__icon">{!! bicon('inbox') !!}</span>
				<h3 class="blog-empty__title">Aucune annonce pour l'instant</h3>
				@if($isAdmin)
					<p>Publiez votre première annonce en cliquant sur « Publier ».</p>
				@else
					<p>Revenez bientôt pour découvrir nos actualités !</p>
				@endif
			</div>
		@endforelse

		{{-- Message affiché par JS quand un filtre de catégorie ne donne aucun résultat --}}
		<div class="blog-empty" id="blogEmptyFilter">
			<span class="blog-empty__icon">{!! bicon('grid') !!}</span>
			<h3 class="blog-empty__title">Aucune annonce dans cette catégorie</h3>
			<p>
				<a href="#" onclick="filterByCategory('__all__'); return false;" class="blog-empty__reset">
					Afficher toutes les annonces
				</a>
			</p>
		</div>

	</main>

	{{-- ══════════════════════════════
		 SIDEBAR DROITE
	══════════════════════════════ --}}
	<aside class="blog-aside">

		{{-- Widget catégories --}}
		<div class="aside-widget">
			<div class="aside-widget__title">Catégories</div>
			<ul class="aside-category-list" id="categoryList">
				@php
					$categories = collect($posts)->groupBy('category');
				@endphp
				@if($categories->isNotEmpty())
				<li class="active" data-category="__all__" onclick="filterByCategory('__all__')">
					Toutes
					<span>{{ count($posts) }}</span>
				</li>
				@endif
				@foreach($categories as $cat => $items)
				<li data-category="{{ $cat }}" onclick="filterByCategory('{{ $cat }}')">
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
				<p>Des meringues faites maison, avec des ingrédients de très bonne qualité et beaucoup d'amour.
					Nous sommes situés en Normandie, plus précisément à Ypreville. Nous espérons que nos
					meringues vous plairont&nbsp;!
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
		<button class="modal__close" onclick="closeModal()" aria-label="Fermer">{!! bicon('x') !!}</button>
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
					<button type="button" class="img-tab img-tab--active" id="tabUrl"  onclick="switchImageTab('url')">{!! bicon('link') !!} Lien URL</button>
					<button type="button" class="img-tab"                 id="tabFile" onclick="switchImageTab('file')">{!! bicon('upload') !!} Importer</button>
				</div>
				<div id="panelUrl">
					<input class="form-input" id="fieldImage" type="url" placeholder="https://…">
				</div>
				<div id="panelFile" style="display:none;">
					<label class="file-drop-zone" id="fileDropZone" for="fieldImageFile">
						{!! bicon('upload', 'file-drop-zone__icon') !!}
						<span id="fileDropLabel">Cliquer ou déposer une image ici</span>
						<span style="font-size:.72rem;opacity:.45;margin-top:2px;">JPG, PNG, GIF, WEBP — max 4 Mo</span>
						<input type="file" id="fieldImageFile" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none" onchange="handleFileSelect(this)">
					</label>
					<div id="filePreviewWrap" style="display:none;margin-top:8px;text-align:center;">
						<img id="filePreview" src="" alt="Prévisualisation"
							 style="max-height:110px;max-width:100%;border-radius:8px;object-fit:cover;">
						<button type="button" onclick="clearFileInput()" class="file-preview__remove">
							{!! bicon('trash') !!} Supprimer
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
<div class="toast" id="toast" role="status" aria-live="polite">
	<span id="toastIcon"></span>
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
	const cards       = document.querySelectorAll('.post-card');
	const emptyOrigin = document.getElementById('blogEmptyOriginal');
	const emptyFilter = document.getElementById('blogEmptyFilter');
	let visibles = 0;

	cards.forEach(card => {
		const show = (cat === '__all__') || (card.dataset.category === cat);
		card.style.display = show ? '' : 'none';
		if (show) visibles++;
	});

	document.querySelectorAll('#categoryList li[data-category]').forEach(li => {
		li.classList.toggle('active', li.dataset.category === cat);
	});

	if (emptyOrigin) emptyOrigin.style.display = 'none'; // il n'y a alors aucun post du tout
	if (emptyFilter) emptyFilter.classList.toggle('visible', cards.length > 0 && visibles === 0);

	document.getElementById('postsGrid')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
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
	if (!IS_AUTH) { showToast('Connectez-vous pour réagir.', 'warning'); return; }
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
		document.getElementById('fileDropLabel').textContent = 'Image existante — cliquer pour la remplacer';
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
	if (!title || !content) { showToast('Titre et contenu obligatoires.', 'warning'); return; }

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
	if (!data.success) { showToast('Une erreur est survenue.', 'error'); return; }

	closeModal();
	showToast(isEdit ? 'Annonce modifiée !' : 'Annonce publiée !', 'success');
	setTimeout(() => location.reload(), 900);
}

/* ── Supprimer ── */
async function deletePost(id) {
	if (!confirm('Supprimer cette annonce définitivement ?')) return;
	const res  = await fetch(ROUTES.destroy(id), { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF } });
	const data = await res.json();
	if (!data.success) { showToast('Une erreur est survenue.', 'error'); return; }
	document.querySelector(`.post-card[data-id="${id}"]`)?.remove();
	const count = document.getElementById('postCount');
	if (count) count.textContent = parseInt(count.textContent) - 1;
	showToast('Annonce supprimée.', 'success');
}

/* ── Toast ── */
const TOAST_ICONS = {
	success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>',
	error:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>',
	warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>',
};

let toastTimer;
function showToast(msg, type = 'success') {
	const t = document.getElementById('toast');
	document.getElementById('toastMsg').textContent  = msg;
	document.getElementById('toastIcon').innerHTML   = TOAST_ICONS[type] ?? TOAST_ICONS.success;
	t.classList.remove('toast--success', 'toast--error', 'toast--warning');
	t.classList.add('toast--' + type, 'show');
	clearTimeout(toastTimer);
	toastTimer = setTimeout(() => t.classList.remove('show'), 3200);
}

/* ── Animations au scroll ── */
(function () {
	const reveals = document.querySelectorAll('.reveal');
	if (!reveals.length) return;

	if (!('IntersectionObserver' in window)) {
		reveals.forEach(el => el.classList.add('reveal--visible'));
		return;
	}

	const observer = new IntersectionObserver(entries => {
		entries.forEach(entry => {
			if (entry.isIntersecting) {
				entry.target.classList.add('reveal--visible');
				observer.unobserve(entry.target);
			}
		});
	}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

	reveals.forEach(el => observer.observe(el));
})();
</script>