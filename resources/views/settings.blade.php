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
    padding: 0 2rem;
    height: 58px;
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
    font-size: 1.15rem;
    color: var(--primary-dark);
}

.topbar__brand em {
    font-style: italic;
    color: var(--gold);
}

.topbar__back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    color: var(--text-muted);
    text-decoration: none;
    font-weight: 500;
    padding: 6px 14px;
    border: 1px solid var(--border);
    border-radius: 999px;
    background: var(--white);
    transition: all var(--t);
}

.topbar__back:hover {
    color: var(--primary);
    border-color: var(--primary-light);
    background: var(--primary-pale);
}

/* ── PAGE ── */
.page {
    max-width: 860px;
    margin: 0 auto;
    padding: 2.5rem 1.5rem 5rem;
    display: flex;
    gap: 2rem;
    align-items: flex-start;
}

/* ── SIDEBAR ── */
.sidebar {
    flex: 0 0 190px;
    position: sticky;
    top: 76px;
}

.sidebar__heading {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: var(--text-muted);
    font-weight: 600;
    margin-bottom: 0.6rem;
    padding-left: 4px;
}

.nav-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 0.8rem 1rem;
    font-size: 0.82rem;
    font-weight: 500;
    color: var(--text-muted);
    cursor: pointer;
    border-left: 2px solid transparent;
    border-bottom: 1px solid var(--border);
    transition: all var(--t);
    user-select: none;
}

.nav-item:last-child { border-bottom: none; }
.nav-item:hover { background: var(--cream); color: var(--text); }

.nav-item.active {
    color: var(--primary);
    background: var(--primary-pale);
    border-left-color: var(--primary);
    font-weight: 600;
}

.nav-item__icon {
    font-size: 1rem;
    width: 18px;
    text-align: center;
    flex-shrink: 0;
}

/* ── CONTENT ── */
.content { flex: 1; min-width: 0; }

.section-panel { display: none; }
.section-panel.active { display: flex; flex-direction: column; gap: 1rem; animation: fadeIn 0.2s ease both; }

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── CARD ── */
.card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.card--danger { border-color: #e57373; }

.card__head {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
}

.card--danger .card__head { background: #fff8f8; border-bottom-color: #fdd; }

.card__icon {
    width: 30px;
    height: 30px;
    border-radius: var(--radius-sm);
    background: var(--cream-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
}

.card--danger .card__icon { background: #fdecea; }

.card__head-text h3 {
    font-family: var(--font-heading);
    font-size: 0.95rem;
    color: var(--primary-dark);
    font-weight: 600;
}

.card--danger .card__head-text h3 { color: #c62828; }

.card__head-text p {
    font-size: 0.72rem;
    color: var(--text-muted);
    margin-top: 1px;
}

.card__body { padding: 1.25rem; }

/* ── ALERT ── */
.alert {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0.65rem 1rem;
    border-radius: var(--radius-md);
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 1.1rem;
    animation: fadeIn 0.3s ease both;
}

.alert--success { background: var(--success-bg); color: var(--success-text); }
.alert--error   { background: var(--error-bg);   color: var(--error-text); }

/* ── AVATAR ROW ── */
.avatar-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding-bottom: 1.25rem;
    margin-bottom: 1.25rem;
    border-bottom: 1px solid var(--border);
}

.avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-heading);
    font-size: 1.6rem;
    color: var(--white);
    flex-shrink: 0;
    border: 3px solid var(--white);
    box-shadow: 0 0 0 2px var(--border);
}

.avatar-info h4 {
    font-family: var(--font-heading);
    font-size: 1rem;
    color: var(--primary-dark);
}

.avatar-info p {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 2px;
}

.avatar-info .role-chip {
    display: inline-flex;
    align-items: center;
    margin-top: 5px;
    padding: 2px 9px;
    border-radius: 999px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    background: var(--gold-light);
    color: var(--gold-dark);
    border: 1px solid #e8d5a0;
}

/* ── FORM ── */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-grid--full { grid-template-columns: 1fr; }

.form-group { display: flex; flex-direction: column; gap: 5px; }

.form-group label {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--text-muted);
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 0.55rem 0.85rem;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-md);
    background: var(--cream);
    color: var(--text);
    font-size: 0.85rem;
    font-family: var(--font-body);
    outline: none;
    transition: border-color var(--t), box-shadow var(--t), background var(--t);
    appearance: none;
    -webkit-appearance: none;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(192,57,90,.1);
    background: var(--white);
}

.form-group input.is-invalid { border-color: #e57373; background: #fff8f8; }
.field-error { font-size: 0.7rem; color: var(--error-text); margin-top: 2px; }

.input-hint { font-size: 0.7rem; color: var(--text-muted); margin-top: 3px; line-height: 1.4; }

/* ── BOUTONS ── */
.btn-row {
    display: flex;
    gap: 0.6rem;
    margin-top: 1.25rem;
    padding-top: 1.1rem;
    border-top: 1px solid var(--border);
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 0.55rem 1.3rem;
    border-radius: 999px;
    font-family: var(--font-body);
    font-size: 0.78rem;
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

/* ── TOGGLE ROW ── */
.toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.9rem 0;
    border-bottom: 1px solid var(--border);
    gap: 1rem;
}

.toggle-row:last-child { border-bottom: none; }

.toggle-row__info h4 { font-size: 0.85rem; font-weight: 600; color: var(--text); }
.toggle-row__info p  { font-size: 0.73rem; color: var(--text-muted); margin-top: 2px; }

.toggle { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
.toggle input { opacity: 0; width: 0; height: 0; position: absolute; }

.toggle__track {
    position: absolute;
    inset: 0;
    border-radius: 11px;
    background: #ddd;
    cursor: pointer;
    transition: background 0.25s ease;
}

.toggle__track::before {
    content: '';
    position: absolute;
    top: 3px; left: 3px;
    width: 16px; height: 16px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
    transition: transform 0.25s ease;
}

.toggle input:checked + .toggle__track { background: #22c55e; }
.toggle input:checked + .toggle__track::before { transform: translateX(18px); }

/* ── INFO ROW (lecture seule) ── */
.info-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border);
    font-size: 0.83rem;
}

.info-row:last-child { border-bottom: none; }
.info-row__label { color: var(--text-muted); font-weight: 500; }
.info-row__value { color: var(--text); font-weight: 600; }

/* ── SECTION TITLE ── */
.section-title {
    font-family: var(--font-heading);
    font-size: 1.4rem;
    color: var(--primary-dark);
    margin-bottom: 0.2rem;
}

.section-sub {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 1.25rem;
}

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .page { flex-direction: column; gap: 1rem; }
    .sidebar { flex: none; width: 100%; position: static; }
    .nav-card { display: flex; overflow-x: auto; border-radius: var(--radius-md); }
    .nav-item {
        border-bottom: none;
        border-left: none;
        border-bottom: 2px solid transparent;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .nav-item.active { border-bottom-color: var(--primary); border-left-color: transparent; }
    .form-grid { grid-template-columns: 1fr; }
}

@media (max-width: 480px) {
    .page { padding: 1.5rem 1rem 4rem; }
    .topbar { padding: 0 1rem; }
}
</style>
</head>
<body>

{{-- TOPBAR --}}
<header class="topbar">
    <span class="topbar__brand">Maison des <em>Meringues</em></span>
    @if($isAdmin)
        <a href="{{ route('gestion.index') }}" class="topbar__back">← Administration</a>
    @else
        <a href="{{ route('index') }}" class="topbar__back">← Accueil</a>
    @endif
</header>

<div class="page">

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <p class="sidebar__heading">Mon compte</p>
        <nav class="nav-card">
            <div class="nav-item active" data-tab="profil">
                <span class="nav-item__icon">👤</span> Profil
            </div>
            <div class="nav-item" data-tab="securite">
                <span class="nav-item__icon">🔒</span> Sécurité
            </div>
            @if($isAdmin)
            <div class="nav-item" data-tab="boutique">
                <span class="nav-item__icon">🏪</span> Boutique
            </div>
            <div class="nav-item" data-tab="commandes">
                <span class="nav-item__icon">📦</span> Commandes
            </div>
            @endif
            <div class="nav-item" data-tab="danger">
                <span class="nav-item__icon">⚠️</span> Zone danger
            </div>
        </nav>
    </aside>

    {{-- CONTENT --}}
    <main class="content">

        {{-- ══ PROFIL ══ --}}
        <div class="section-panel active" id="tab-profil">

            <div>
                <h2 class="section-title">Mon profil</h2>
                <p class="section-sub">Vos informations personnelles et coordonnées</p>
            </div>

            <div class="card">
                <div class="card__head">
                    <div class="card__icon">👤</div>
                    <div class="card__head-text">
                        <h3>Informations personnelles</h3>
                        <p>Nom, email et numéro de téléphone</p>
                    </div>
                </div>
                <div class="card__body">

                    @if(session('success_profile'))
                        <div class="alert alert--success">✅ {{ session('success_profile') }}</div>
                    @endif
                    @if($errors->has('name') || $errors->has('email') || $errors->has('phone'))
                        <div class="alert alert--error">⚠️ Veuillez corriger les erreurs ci-dessous.</div>
                    @endif

                    {{-- Avatar --}}
                    <div class="avatar-row">
                        <div class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div class="avatar-info">
                            <h4>{{ $user->name }}</h4>
                            <p>{{ $user->email }}</p>
                            <span class="role-chip">{{ $user->role }}</span>
                        </div>
                    </div>

                    <form action="{{ route('settings.profile') }}" method="POST">
                        @csrf

                        <div class="form-grid form-grid--full">
                            <div class="form-group">
                                <label>Nom complet</label>
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
                                <label>Adresse e-mail</label>
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
                                <label>Téléphone</label>
                                <input
                                    type="tel"
                                    name="phone"
                                    value="{{ old('phone', $user->phone ?? '') }}"
                                    class="{{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                    placeholder="06 12 34 56 78"
                                >
                                <span class="input-hint">Utilisé pour vous contacter concernant vos commandes</span>
                                @error('phone') <span class="field-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="btn-row">
                            <button type="submit" class="btn btn--primary">Enregistrer</button>
                            <button type="reset" class="btn btn--ghost">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Récap lecture seule --}}
            <div class="card">
                <div class="card__head">
                    <div class="card__icon">ℹ️</div>
                    <div class="card__head-text">
                        <h3>Informations du compte</h3>
                        <p>Données non modifiables</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="info-row">
                        <span class="info-row__label">Identifiant</span>
                        <span class="info-row__value">#{{ $user->id }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row__label">Rôle</span>
                        <span class="info-row__value">{{ ucfirst($user->role) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row__label">Membre depuis</span>
                        <span class="info-row__value">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row__label">Dernière connexion</span>
                        <span class="info-row__value">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ══ SÉCURITÉ ══ --}}
        <div class="section-panel" id="tab-securite">

            <div>
                <h2 class="section-title">Sécurité</h2>
                <p class="section-sub">Modifiez votre mot de passe de connexion</p>
            </div>

            <div class="card">
                <div class="card__head">
                    <div class="card__icon">🔒</div>
                    <div class="card__head-text">
                        <h3>Changer le mot de passe</h3>
                        <p>Choisissez un mot de passe fort d'au moins 8 caractères</p>
                    </div>
                </div>
                <div class="card__body">

                    @if(session('success_password'))
                        <div class="alert alert--success">✅ {{ session('success_password') }}</div>
                    @endif
                    @if($errors->has('current_password') || $errors->has('password'))
                        <div class="alert alert--error">⚠️ Veuillez corriger les erreurs ci-dessous.</div>
                    @endif

                    <form action="{{ route('settings.password') }}" method="POST">
                        @csrf

                        <div class="form-grid form-grid--full">
                            <div class="form-group">
                                <label>Mot de passe actuel</label>
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
                                <label>Nouveau mot de passe</label>
                                <input
                                    type="password"
                                    name="password"
                                    placeholder="••••••••"
                                    class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    required
                                >
                                @error('password') <span class="field-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>Confirmer le mot de passe</label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    placeholder="••••••••"
                                    required
                                >
                            </div>
                        </div>

                        <div class="btn-row">
                            <button type="submit" class="btn btn--primary">Changer le mot de passe</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {{-- ══ BOUTIQUE (admin) ══ --}}
        @if($isAdmin)
        <div class="section-panel" id="tab-boutique">

            <div>
                <h2 class="section-title">Paramètres boutique</h2>
                <p class="section-sub">Configuration générale de votre boutique en ligne</p>
            </div>

            <div class="card">
                <div class="card__head">
                    <div class="card__icon">🏪</div>
                    <div class="card__head-text">
                        <h3>Options de vente</h3>
                        <p>Modes de retrait et livraison disponibles</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="toggle-row">
                        <div class="toggle-row__info">
                            <h4>Click &amp; Collect</h4>
                            <p>Permettre le retrait en boutique</p>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" checked>
                            <span class="toggle__track"></span>
                        </label>
                    </div>
                    <div class="toggle-row">
                        <div class="toggle-row__info">
                            <h4>Expédition activée</h4>
                            <p>Autoriser la livraison à domicile</p>
                        </div>
                        <label class="toggle">
                            <input type="checkbox">
                            <span class="toggle__track"></span>
                        </label>
                    </div>
                    <div class="toggle-row">
                        <div class="toggle-row__info">
                            <h4>Confirmation automatique</h4>
                            <p>Les commandes sont confirmées sans validation manuelle</p>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" checked>
                            <span class="toggle__track"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card__head">
                    <div class="card__icon">⏱️</div>
                    <div class="card__head-text">
                        <h3>Règles de commande</h3>
                        <p>Délais et montants minimaux</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Délai de préparation (jours)</label>
                            <input type="number" value="2" min="1" max="14" placeholder="2">
                            <span class="input-hint">Nombre de jours avant que la commande soit prête</span>
                        </div>
                        <div class="form-group">
                            <label>Commande minimum (€)</label>
                            <input type="number" value="15" min="0" step="0.50" placeholder="15">
                            <span class="input-hint">Montant minimal pour valider une commande</span>
                        </div>
                    </div>
                    <div class="btn-row">
                        <button class="btn btn--primary">Enregistrer</button>
                    </div>
                </div>
            </div>

        </div>

        {{-- ══ COMMANDES (admin) ══ --}}
        <div class="section-panel" id="tab-commandes">

            <div>
                <h2 class="section-title">Notifications commandes</h2>
                <p class="section-sub">Choisissez comment être alerté des nouvelles commandes</p>
            </div>

            <div class="card">
                <div class="card__head">
                    <div class="card__icon">📧</div>
                    <div class="card__head-text">
                        <h3>Alertes e-mail</h3>
                        <p>E-mail envoyé à {{ $user->email }}</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="toggle-row">
                        <div class="toggle-row__info">
                            <h4>Nouvelle commande reçue</h4>
                            <p>Recevoir un e-mail dès qu'une commande est passée</p>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" checked>
                            <span class="toggle__track"></span>
                        </label>
                    </div>
                    <div class="toggle-row">
                        <div class="toggle-row__info">
                            <h4>Commande annulée</h4>
                            <p>Être notifié si un client annule sa commande</p>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" checked>
                            <span class="toggle__track"></span>
                        </label>
                    </div>
                    <div class="toggle-row">
                        <div class="toggle-row__info">
                            <h4>Rupture de stock</h4>
                            <p>Alerte quand un produit tombe à zéro</p>
                        </div>
                        <label class="toggle">
                            <input type="checkbox">
                            <span class="toggle__track"></span>
                        </label>
                    </div>
                    <div class="toggle-row">
                        <div class="toggle-row__info">
                            <h4>Récapitulatif quotidien</h4>
                            <p>Résumé des commandes du jour chaque soir à 20h</p>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" checked>
                            <span class="toggle__track"></span>
                        </label>
                    </div>
                </div>
            </div>

            @if($user->phone ?? false)
            <div class="card">
                <div class="card__head">
                    <div class="card__icon">📱</div>
                    <div class="card__head-text">
                        <h3>Alertes SMS</h3>
                        <p>Envoyé au {{ $user->phone }}</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="toggle-row">
                        <div class="toggle-row__info">
                            <h4>SMS — Nouvelle commande</h4>
                            <p>Recevoir un SMS à chaque nouvelle commande</p>
                        </div>
                        <label class="toggle">
                            <input type="checkbox">
                            <span class="toggle__track"></span>
                        </label>
                    </div>
                    <div class="toggle-row">
                        <div class="toggle-row__info">
                            <h4>SMS — Rupture de stock</h4>
                            <p>SMS immédiat quand un produit est épuisé</p>
                        </div>
                        <label class="toggle">
                            <input type="checkbox">
                            <span class="toggle__track"></span>
                        </label>
                    </div>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card__head">
                    <div class="card__icon">📱</div>
                    <div class="card__head-text">
                        <h3>Alertes SMS</h3>
                        <p>Ajoutez un numéro de téléphone pour activer les SMS</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="alert alert--error" style="margin-bottom:0;">
                        📵 Aucun numéro de téléphone enregistré — rendez-vous dans
                        <strong style="cursor:pointer; text-decoration:underline;" onclick="activateTab('profil')">Mon profil</strong>
                        pour en ajouter un et débloquer les alertes SMS.
                    </div>
                </div>
            </div>
            @endif

        </div>
        @endif

        {{-- ══ ZONE DANGER ══ --}}
        <div class="section-panel" id="tab-danger">

            <div>
                <h2 class="section-title">Zone de danger</h2>
                <p class="section-sub">Actions irréversibles sur votre compte</p>
            </div>

            <div class="card card--danger">
                <div class="card__head">
                    <div class="card__icon">⚠️</div>
                    <div class="card__head-text">
                        <h3>Désactiver le compte</h3>
                        <p>Votre compte sera suspendu — vos données sont conservées</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="toggle-row">
                        <div class="toggle-row__info">
                            <h4>Désactiver mon compte</h4>
                            <p>Vous ne pourrez plus vous connecter jusqu'à réactivation par un administrateur</p>
                        </div>
                        <form action="{{ route('settings.deactivate') }}" method="POST"
                              onsubmit="return confirm('Êtes-vous sûr ? Votre compte sera suspendu.')">
                            @csrf
                            <button type="submit" class="btn btn--danger">Désactiver</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </main>
</div>

<script>
const initialTab = '{{ session("active_tab", "profil") }}';

function activateTab(name) {
    document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
    document.querySelectorAll('.section-panel').forEach(p => p.classList.remove('active'));
    const item  = document.querySelector(`[data-tab="${name}"]`);
    const panel = document.getElementById('tab-' + name);
    if (item)  item.classList.add('active');
    if (panel) panel.classList.add('active');
}

document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', () => activateTab(item.dataset.tab));
});

activateTab(initialTab);
</script>

</body>
</html>
