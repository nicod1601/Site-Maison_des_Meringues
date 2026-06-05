@php
	$user = Auth::user();
	$isAdmin = $user->role === 'admin';
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Paramètres — Maison des Meringues</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<style>
:root {
	--primary:       #C0395A;
	--primary-dark:  #8B2340;
	--primary-light: #e8748f;
	--primary-pale:  rgba(192,57,90,.07);
	--gold:          #C9963A;
	--gold-light:    #F5EDCA;
	--gold-dark:     #8B6820;
	--cream:         #FAF6EE;
	--cream-dark:    #F0E8D5;
	--white:         #FFFFFF;
	--border:        #E8DEC8;
	--text:          #2E1A10;
	--text-muted:    #8C7B6A;
	--success-bg:    #d4edda;
	--success-text:  #2d6a4f;
	--error-bg:      #fdecea;
	--error-text:    #c62828;
	--radius-sm:     6px;
	--radius-md:     10px;
	--radius-lg:     16px;
	--radius-xl:     24px;
	--shadow-sm:     0 1px 4px rgba(46,26,16,.06);
	--shadow-md:     0 4px 20px rgba(46,26,16,.09);
	--font-heading:  'Playfair Display', serif;
	--font-body:     'DM Sans', sans-serif;
	--t:             0.2s ease;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
	font-family: var(--font-body);
	background: var(--cream);
	color: var(--text);
	min-height: 100vh;
	-webkit-font-smoothing: antialiased;
}

/* ── TOPBAR ── */
.topbar {
	background: var(--white);
	border-bottom: 1px solid var(--border);
	padding: 0 2.5rem;
	height: 62px;
	display: flex;
	align-items: center;
	justify-content: space-between;
	position: sticky;
	top: 0;
	z-index: 100;
	box-shadow: var(--shadow-sm);
}

.topbar__brand {
	font-family: var(--font-heading);
	font-size: 1.2rem;
	color: var(--primary-dark);
}
.topbar__brand em { font-style: italic; color: var(--gold); }

.topbar__back {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	font-size: 0.8rem;
	color: var(--text-muted);
	text-decoration: none;
	font-weight: 500;
	padding: 7px 16px;
	border: 1px solid var(--border);
	border-radius: 999px;
	background: var(--white);
	transition: all var(--t);
}
.topbar__back:hover { color: var(--primary); border-color: var(--primary-light); background: var(--primary-pale); }

/* ── PAGE LAYOUT ── */
.page {
	max-width: 1200px;
	margin: 0 auto;
	padding: 2.5rem 2rem 5rem;
	display: grid;
	grid-template-columns: 300px 1fr;
	gap: 2rem;
	align-items: start;
}

/* ── COLONNE GAUCHE ── */
.left-col {
	display: flex;
	flex-direction: column;
	gap: 1.25rem;
	position: sticky;
	top: 82px;
}

/* ── AVATAR CARD ── */
.profile-card {
	background: var(--white);
	border: 1px solid var(--border);
	border-radius: var(--radius-xl);
	box-shadow: var(--shadow-sm);
	overflow: hidden;
	text-align: center;
}

.profile-card__banner {
	height: 70px;
	background: linear-gradient(135deg, var(--primary-light), var(--primary));
}

.profile-card__body {
	padding: 0 1.5rem 1.5rem;
}

.avatar-wrap {
	margin-top: -38px;
	margin-bottom: 0.75rem;
	display: flex;
	justify-content: center;
}

.avatar {
	width: 76px;
	height: 76px;
	border-radius: 50%;
	background: linear-gradient(135deg, var(--primary-light), var(--primary));
	display: flex;
	align-items: center;
	justify-content: center;
	font-family: var(--font-heading);
	font-size: 2rem;
	color: var(--white);
	border: 4px solid var(--white);
	box-shadow: 0 0 0 2px var(--border);
}

.profile-card__name {
	font-family: var(--font-heading);
	font-size: 1.2rem;
	color: var(--primary-dark);
}
.profile-card__email {
	font-size: 0.8rem;
	color: var(--text-muted);
	margin-top: 3px;
}
.role-chip {
	display: inline-flex;
	align-items: center;
	margin-top: 8px;
	padding: 3px 10px;
	border-radius: 999px;
	font-size: 0.65rem;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.09em;
	background: var(--gold-light);
	color: var(--gold-dark);
	border: 1px solid #e8d5a0;
}

.profile-card__meta {
	margin-top: 1rem;
	padding-top: 1rem;
	border-top: 1px solid var(--border);
	display: flex;
	flex-direction: column;
	gap: 0.5rem;
	text-align: left;
}

.meta-row {
	display: flex;
	align-items: center;
	justify-content: space-between;
	font-size: 0.8rem;
}
.meta-row__label {
	color: var(--text-muted);
	display: flex;
	align-items: center;
	gap: 5px;
}
.meta-row__value { font-weight: 600; color: var(--text); }

/* ── GOOGLE REVIEW CARD ── */
.google-card {
	background: var(--white);
	border: 1px solid var(--border);
	border-radius: var(--radius-xl);
	box-shadow: var(--shadow-sm);
	padding: 1.25rem 1.4rem;
}

.google-card__head {
	display: flex;
	align-items: center;
	gap: 10px;
	margin-bottom: 0.75rem;
}

.google-icon {
	width: 36px;
	height: 36px;
	border-radius: var(--radius-md);
	display: flex;
	align-items: center;
	justify-content: center;
	background: #f1f3f4;
	flex-shrink: 0;
}

/* Google "G" SVG inline */
.google-icon svg { width: 20px; height: 20px; }

.google-card__title {
	font-family: var(--font-heading);
	font-size: 0.9rem;
	color: var(--text);
}
.google-card__sub {
	font-size: 0.72rem;
	color: var(--text-muted);
	margin-top: 1px;
}

.stars {
	display: flex;
	gap: 3px;
	margin-bottom: 0.75rem;
}
.star {
	width: 22px;
	height: 22px;
	border-radius: 4px;
	border: 1.5px solid var(--border);
	display: flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;
	transition: all var(--t);
	background: var(--cream);
	color: #ccc;
	font-size: 14px;
}
.star.active, .star:hover { background: #FFF3CD; border-color: #F5C842; color: #F5C842; }

.google-textarea {
	width: 100%;
	padding: 0.6rem 0.85rem;
	border: 1.5px solid var(--border);
	border-radius: var(--radius-md);
	background: var(--cream);
	color: var(--text);
	font-size: 0.82rem;
	font-family: var(--font-body);
	resize: vertical;
	min-height: 70px;
	outline: none;
	transition: border-color var(--t);
	margin-bottom: 0.75rem;
}
.google-textarea:focus { border-color: var(--primary); background: var(--white); }

.google-card__note {
	font-size: 0.68rem;
	color: var(--text-muted);
	line-height: 1.5;
	margin-bottom: 0.75rem;
}

.btn-google {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 7px;
	width: 100%;
	padding: 0.55rem 1rem;
	border-radius: 999px;
	font-family: var(--font-body);
	font-size: 0.78rem;
	font-weight: 600;
	cursor: pointer;
	border: 1.5px solid #dadce0;
	background: var(--white);
	color: #3c4043;
	transition: all var(--t);
}
.btn-google:hover { background: #f8f9fa; box-shadow: 0 1px 4px rgba(0,0,0,.1); }

/* ── COLONNE DROITE ── */
.right-col {
	display: flex;
	flex-direction: column;
	gap: 1.5rem;
}

/* ── SECTION HEADER ── */
.section-head {
	display: flex;
	align-items: center;
	gap: 10px;
	margin-bottom: 1rem;
}
.section-head__icon {
	width: 34px;
	height: 34px;
	border-radius: var(--radius-md);
	background: var(--primary-pale);
	display: flex;
	align-items: center;
	justify-content: center;
	color: var(--primary);
	font-size: 17px;
	flex-shrink: 0;
}
.section-head__icon--danger { background: #fdecea; color: #c62828; }

.section-title {
	font-family: var(--font-heading);
	font-size: 1.25rem;
	color: var(--primary-dark);
}
.section-sub {
	font-size: 0.77rem;
	color: var(--text-muted);
	margin-top: 1px;
}

/* ── CARD ── */
.card {
	background: var(--white);
	border: 1px solid var(--border);
	border-radius: var(--radius-xl);
	box-shadow: var(--shadow-sm);
	overflow: hidden;
}
.card--danger { border-color: #f0b4b4; }

.card__head {
	padding: 1.1rem 1.5rem;
	border-bottom: 1px solid var(--border);
	display: flex;
	align-items: center;
	gap: 10px;
}
.card--danger .card__head { background: #fff8f8; border-bottom-color: #fdd; }

.card__icon {
	width: 32px;
	height: 32px;
	border-radius: var(--radius-sm);
	background: var(--cream-dark);
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 16px;
	color: var(--text-muted);
	flex-shrink: 0;
}
.card--danger .card__icon { background: #fdecea; color: #c62828; }

.card__head-text h3 {
	font-family: var(--font-heading);
	font-size: 1rem;
	color: var(--primary-dark);
	font-weight: 600;
}
.card--danger .card__head-text h3 { color: #c62828; }
.card__head-text p { font-size: 0.73rem; color: var(--text-muted); margin-top: 2px; }

.card__body { padding: 1.5rem; }

/* ── ALERT ── */
.alert {
	display: flex;
	align-items: center;
	gap: 8px;
	padding: 0.7rem 1rem;
	border-radius: var(--radius-md);
	font-size: 0.82rem;
	font-weight: 500;
	margin-bottom: 1.25rem;
}
.alert--success { background: var(--success-bg); color: var(--success-text); }
.alert--error   { background: var(--error-bg);   color: var(--error-text); }

/* ── FORM ── */
.form-grid {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 1.1rem;
	margin-bottom: 1rem;
}
.form-grid--full { grid-template-columns: 1fr; }
.form-grid--3    { grid-template-columns: 1fr 1fr 1fr; }

.form-group { display: flex; flex-direction: column; gap: 5px; }

.form-group label {
	font-size: 0.69rem;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.1em;
	color: var(--text-muted);
	display: flex;
	align-items: center;
	gap: 5px;
}

.form-group input,
.form-group select,
.form-group textarea {
	padding: 0.65rem 0.95rem;
	border: 1.5px solid var(--border);
	border-radius: var(--radius-md);
	background: var(--cream);
	color: var(--text);
	font-size: 0.9rem;
	font-family: var(--font-body);
	outline: none;
	transition: border-color var(--t), box-shadow var(--t), background var(--t);
	appearance: none;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
	border-color: var(--primary);
	box-shadow: 0 0 0 3px rgba(192,57,90,.1);
	background: var(--white);
}
.form-group input.is-invalid { border-color: #e57373; background: #fff8f8; }
.field-error { font-size: 0.71rem; color: var(--error-text); margin-top: 2px; }
.input-hint  { font-size: 0.71rem; color: var(--text-muted); margin-top: 3px; line-height: 1.4; }

/* ── DIVIDER ── */
.form-divider {
	display: flex;
	align-items: center;
	gap: 10px;
	margin: 1.25rem 0;
	font-size: 0.68rem;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.1em;
	color: var(--text-muted);
}
.form-divider::before,
.form-divider::after {
	content: '';
	flex: 1;
	height: 1px;
	background: var(--border);
}

/* ── BOUTONS ── */
.btn-row {
	display: flex;
	gap: 0.7rem;
	margin-top: 1.4rem;
	padding-top: 1.25rem;
	border-top: 1px solid var(--border);
}

.btn {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
	padding: 0.6rem 1.5rem;
	border-radius: 999px;
	font-family: var(--font-body);
	font-size: 0.8rem;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 0.08em;
	cursor: pointer;
	border: 1.5px solid transparent;
	transition: all var(--t);
}
.btn--primary { background: var(--primary); color: #fff; border-color: var(--primary); }
.btn--primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
.btn--ghost { background: transparent; color: var(--text-muted); border-color: var(--border); }
.btn--ghost:hover { background: var(--cream); color: var(--text); }
.btn--danger { background: transparent; color: var(--error-text); border-color: #e57373; }
.btn--danger:hover { background: var(--error-bg); }

/* ── INFO ROW ── */
.info-row {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 0.85rem 0;
	border-bottom: 1px solid var(--border);
	font-size: 0.88rem;
}
.info-row:last-child { border-bottom: none; }
.info-row__label { color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 6px; }
.info-row__value { color: var(--text); font-weight: 600; }

/* ── PASSWORD STRENGTH ── */
.pw-strength { display: flex; gap: 4px; margin-top: 6px; }
.pw-bar { flex: 1; height: 3px; border-radius: 2px; background: var(--border); transition: background .3s; }
.pw-bar.weak   { background: #e57373; }
.pw-bar.medium { background: var(--gold); }
.pw-bar.strong { background: #4caf50; }

/* ── RESPONSIVE ── */
@media (max-width: 900px) {
	.page { grid-template-columns: 1fr; }
	.left-col { position: static; }
	.form-grid--3 { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
	.form-grid, .form-grid--3 { grid-template-columns: 1fr; }
	.page { padding: 1.5rem 1rem 4rem; }
}
</style>
</head>
<body>

{{-- TOPBAR --}}
<header class="topbar">
	<span class="topbar__brand">Maison des <em>Meringues</em></span>
	<a href="{{ route('index') }}" class="topbar__back">
		<i class="ti ti-arrow-left" style="font-size:13px;"></i> Accueil
	</a>
</header>

<div class="page">

	{{-- ══ COLONNE GAUCHE ══ --}}
	<aside class="left-col">

		{{-- Carte profil --}}
		<div class="profile-card">
			<div class="profile-card__banner"></div>
			<div class="profile-card__body">
				<div class="avatar-wrap">
					<div class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
				</div>
				<div class="profile-card__name">{{ $user->name }}</div>
				<div class="profile-card__email">{{ $user->email }}</div>
				<span class="role-chip">
					<i class="ti ti-shield-check" style="font-size:10px;margin-right:3px;"></i>
					{{ ucfirst($user->role) }}
				</span>

				<div class="profile-card__meta">
					<div class="meta-row">
						<span class="meta-row__label">
							<i class="ti ti-hash" style="font-size:13px;"></i> Identifiant
						</span>
						<span class="meta-row__value">#{{ $user->id }}</span>
					</div>
					<div class="meta-row">
						<span class="meta-row__label">
							<i class="ti ti-calendar" style="font-size:13px;"></i> Membre depuis
						</span>
						<span class="meta-row__value">{{ $user->created_at->format('d/m/Y') }}</span>
					</div>
					<div class="meta-row">
						<span class="meta-row__label">
							<i class="ti ti-clock" style="font-size:13px;"></i> Dernière connexion
						</span>
						<span class="meta-row__value">{{ $user->updated_at->diffForHumans() }}</span>
					</div>
				</div>
			</div>
		</div>

		{{-- Carte avis Google --}}
		<div class="google-card">
			<div class="google-card__head">
				<div class="google-icon">
					{{-- Logo Google "G" SVG --}}
					<svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
						<path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
						<path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
						<path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
						<path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
						<path fill="none" d="M0 0h48v48H0z"/>
					</svg>
				</div>
				<div>
					<div class="google-card__title">Laisser un avis</div>
					<div class="google-card__sub">Partagez votre expérience</div>
				</div>
			</div>

			{{-- Étoiles interactives --}}
			<div class="stars" id="stars-row">
				<div class="star" data-val="1"><i class="ti ti-star-filled"></i></div>
				<div class="star" data-val="2"><i class="ti ti-star-filled"></i></div>
				<div class="star" data-val="3"><i class="ti ti-star-filled"></i></div>
				<div class="star" data-val="4"><i class="ti ti-star-filled"></i></div>
				<div class="star" data-val="5"><i class="ti ti-star-filled"></i></div>
			</div>

			<textarea class="google-textarea" id="review-text" placeholder="Décrivez votre expérience avec La Maison des Meringues…"></textarea>

			<p class="google-card__note">
				<i class="ti ti-info-circle" style="font-size:12px;vertical-align:-1px;margin-right:3px;"></i>
				En cliquant sur le bouton vous serez redirigé vers Google pour publier votre avis.
			</p>

			<button class="btn-google" id="btn-google-review">
				<svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;">
					<path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
					<path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
					<path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
					<path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
				</svg>
				Publier sur Google
			</button>
		</div>

	</aside>

	{{-- ══ COLONNE DROITE ══ --}}
	<main class="right-col">

		{{-- ── PROFIL ── --}}
		<div>
			<div class="section-head">
				<div class="section-head__icon">
					<i class="ti ti-user"></i>
				</div>
				<div>
					<div class="section-title">Mon profil</div>
					<div class="section-sub">Vos informations personnelles et de livraison</div>
				</div>
			</div>

			<div class="card">
				<div class="card__head">
					<div class="card__icon"><i class="ti ti-id-badge"></i></div>
					<div class="card__head-text">
						<h3>Informations personnelles</h3>
						<p>Nom, email et numéro de téléphone</p>
					</div>
				</div>
				<div class="card__body">

					@if(session('success_profile'))
						<div class="alert alert--success">
							<i class="ti ti-circle-check" style="font-size:16px;"></i>
							{{ session('success_profile') }}
						</div>
					@endif
					@if($errors->has('name') || $errors->has('email') || $errors->has('phone'))
						<div class="alert alert--error">
							<i class="ti ti-alert-triangle" style="font-size:16px;"></i>
							Veuillez corriger les erreurs ci-dessous.
						</div>
					@endif

					<form action="{{ route('settings.profile') }}" method="POST">
						@csrf

						<div class="form-divider">Identité</div>

						<div class="form-grid--full form-grid" style="margin-bottom:1rem;">
							<div class="form-group">
								<label>
									<i class="ti ti-user" style="font-size:12px;"></i>
									Nom complet
								</label>
								<input
									type="text"
									name="name"
									value="{{ old('name', $user->name) }}"
									class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
									placeholder="Votre nom"
									required
								>
								@error('name') <span class="field-error">{{ $message }}</span> @enderror
							</div>
						</div>

						<div class="form-grid">
							<div class="form-group">
								<label>
									<i class="ti ti-mail" style="font-size:12px;"></i>
									Adresse e-mail
								</label>
								<input
									type="email"
									name="email"
									value="{{ old('email', $user->email) }}"
									class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
									placeholder="votre@email.fr"
									required
								>
								@error('email') <span class="field-error">{{ $message }}</span> @enderror
							</div>
							<div class="form-group">
								<label>
									<i class="ti ti-phone" style="font-size:12px;"></i>
									Téléphone
								</label>
								<input
									type="tel"
									name="phone"
									value="{{ old('phone', $user->phone ?? '') }}"
									class="{{ $errors->has('phone') ? 'is-invalid' : '' }}"
									placeholder="06 12 34 56 78"
								>
								<span class="input-hint">Utilisé pour vos commandes</span>
								@error('phone') <span class="field-error">{{ $message }}</span> @enderror
							</div>
						</div>

						<div class="form-divider">Adresse de livraison</div>

						<div class="form-grid--full form-grid" style="margin-bottom:1rem;">
							<div class="form-group">
								<label>
									<i class="ti ti-map-pin" style="font-size:12px;"></i>
									Adresse
								</label>
								<input
									type="text"
									name="adresse"
									value="{{ old('adresse', $user->adresse ?? '') }}"
									class="{{ $errors->has('adresse') ? 'is-invalid' : '' }}"
									placeholder="123 Rue de la Paix"
								>
								@error('adresse') <span class="field-error">{{ $message }}</span> @enderror
							</div>
						</div>

						<div class="form-grid form-grid--3">
							<div class="form-group" style="grid-column: span 2;">
								<label>
									<i class="ti ti-building" style="font-size:12px;"></i>
									Ville
								</label>
								<input
									type="text"
									name="ville"
									value="{{ old('ville', $user->ville ?? '') }}"
									class="{{ $errors->has('ville') ? 'is-invalid' : '' }}"
									placeholder="Paris"
								>
								@error('ville') <span class="field-error">{{ $message }}</span> @enderror
							</div>
							<div class="form-group">
								<label>
									<i class="ti ti-mail-opened" style="font-size:12px;"></i>
									Code postal
								</label>
								<input
									type="text"
									name="code_postal"
									value="{{ old('code_postal', $user->code_postal ?? '') }}"
									class="{{ $errors->has('code_postal') ? 'is-invalid' : '' }}"
									placeholder="75000"
								>
								@error('code_postal') <span class="field-error">{{ $message }}</span> @enderror
							</div>
						</div>

						<div class="btn-row">
							<button type="submit" class="btn btn--primary">
								<i class="ti ti-device-floppy" style="font-size:14px;"></i>
								Enregistrer
							</button>
							<button type="reset" class="btn btn--ghost">Annuler</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		{{-- ── SÉCURITÉ ── --}}
		<div>
			<div class="section-head">
				<div class="section-head__icon">
					<i class="ti ti-lock"></i>
				</div>
				<div>
					<div class="section-title">Sécurité</div>
					<div class="section-sub">Modifiez votre mot de passe de connexion</div>
				</div>
			</div>

			<div class="card">
				<div class="card__head">
					<div class="card__icon"><i class="ti ti-key"></i></div>
					<div class="card__head-text">
						<h3>Changer le mot de passe</h3>
						<p>Choisissez un mot de passe fort d'au moins 8 caractères</p>
					</div>
				</div>
				<div class="card__body">

					@if(session('success_password'))
						<div class="alert alert--success">
							<i class="ti ti-circle-check" style="font-size:16px;"></i>
							{{ session('success_password') }}
						</div>
					@endif
					@if($errors->has('current_password') || $errors->has('password'))
						<div class="alert alert--error">
							<i class="ti ti-alert-triangle" style="font-size:16px;"></i>
							Veuillez corriger les erreurs ci-dessous.
						</div>
					@endif

					<form action="{{ route('settings.password') }}" method="POST">
						@csrf

						<div class="form-grid--full form-grid" style="margin-bottom:1rem;">
							<div class="form-group">
								<label>
									<i class="ti ti-lock" style="font-size:12px;"></i>
									Mot de passe actuel
								</label>
								<input
									type="password"
									name="current_password"
									placeholder="••••••••"
									class="{{ $errors->has('current_password') ? 'is-invalid' : '' }}"
									required
								>
								@error('current_password') <span class="field-error">{{ $message }}</span> @enderror
							</div>
						</div>

						<div class="form-grid">
							<div class="form-group">
								<label>
									<i class="ti ti-lock-open" style="font-size:12px;"></i>
									Nouveau mot de passe
								</label>
								<input
									type="password"
									name="password"
									id="new-password"
									placeholder="••••••••"
									class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
									required
								>
								<div class="pw-strength" id="pw-strength">
									<div class="pw-bar" id="pb1"></div>
									<div class="pw-bar" id="pb2"></div>
									<div class="pw-bar" id="pb3"></div>
									<div class="pw-bar" id="pb4"></div>
								</div>
								@error('password') <span class="field-error">{{ $message }}</span> @enderror
							</div>
							<div class="form-group">
								<label>
									<i class="ti ti-lock-check" style="font-size:12px;"></i>
									Confirmer le mot de passe
								</label>
								<input
									type="password"
									name="password_confirmation"
									placeholder="••••••••"
									required
								>
							</div>
						</div>

						<div class="btn-row">
							<button type="submit" class="btn btn--primary">
								<i class="ti ti-refresh" style="font-size:14px;"></i>
								Changer le mot de passe
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		{{-- ── ZONE DANGER ── --}}
		<div>
			<div class="section-head">
				<div class="section-head__icon section-head__icon--danger">
					<i class="ti ti-alert-triangle"></i>
				</div>
				<div>
					<div class="section-title" style="color:#c62828;">Zone de danger</div>
					<div class="section-sub">Actions irréversibles sur votre compte</div>
				</div>
			</div>

			<div class="card card--danger">
				<div class="card__head">
					<div class="card__icon"><i class="ti ti-user-off"></i></div>
					<div class="card__head-text">
						<h3>Désactiver le compte</h3>
						<p>Votre compte sera suspendu — vos données sont conservées</p>
					</div>
				</div>
				<div class="card__body">
					<div class="info-row">
						<div>
							<div style="font-weight:600;font-size:.9rem;display:flex;align-items:center;gap:6px;">
								<i class="ti ti-ban" style="font-size:15px;color:#c62828;"></i>
								Désactiver mon compte
							</div>
							<div style="font-size:.75rem;color:var(--text-muted);margin-top:3px;">
								Vous ne pourrez plus vous connecter jusqu'à réactivation par un administrateur
							</div>
						</div>
						<form action="{{ route('settings.deactivate') }}" method="POST"
							  onsubmit="return confirm('Êtes-vous sûr ? Votre compte sera suspendu.')">
							@csrf
							<button type="submit" class="btn btn--danger">
								<i class="ti ti-trash" style="font-size:14px;"></i>
								Désactiver
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>

	</main>
</div>

<script>
// ── Password strength indicator
const pwInput = document.getElementById('new-password');
if (pwInput) {
	pwInput.addEventListener('input', function () {
		const val = this.value;
		let score = 0;
		if (val.length >= 8) score++;
		if (/[A-Z]/.test(val)) score++;
		if (/[0-9]/.test(val)) score++;
		if (/[^A-Za-z0-9]/.test(val)) score++;

		const cls = score <= 1 ? 'weak' : score <= 2 ? 'medium' : 'strong';
		for (let i = 1; i <= 4; i++) {
			const bar = document.getElementById('pb' + i);
			bar.className = 'pw-bar' + (i <= score ? ' ' + cls : '');
		}
	});
}

// ── Google review stars
const stars = document.querySelectorAll('.star');
let selectedRating = 0;

stars.forEach(star => {
	star.addEventListener('mouseenter', () => {
		const val = parseInt(star.dataset.val);
		stars.forEach(s => s.classList.toggle('active', parseInt(s.dataset.val) <= val));
	});
	star.addEventListener('mouseleave', () => {
		stars.forEach(s => s.classList.toggle('active', parseInt(s.dataset.val) <= selectedRating));
	});
	star.addEventListener('click', () => {
		selectedRating = parseInt(star.dataset.val);
		stars.forEach(s => s.classList.toggle('active', parseInt(s.dataset.val) <= selectedRating));
	});
});

// ── Google review button
// Remplace l'URL ci-dessous par l'URL Google My Business de ta boutique
const GOOGLE_REVIEW_URL = 'https://g.page/r/VOTRE_ID_GOOGLE/review';

document.getElementById('btn-google-review').addEventListener('click', () => {
	window.open(GOOGLE_REVIEW_URL, '_blank');
});
</script>

</body>
</html>
