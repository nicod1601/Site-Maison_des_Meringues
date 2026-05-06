<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Paramètres — Maison des Meringues</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --primary:       #C0395A;
    --primary-dark:  #8B2340;
    --primary-light: #e8748f;
    --gold:          #C9963A;
    --gold-light:    #F5EDCA;
    --gold-dark:     #8B6820;
    --cream:         #FAF6EE;
    --cream-dark:    #F0E8D5;
    --white:         #FFFFFF;
    --border:        #E8DEC8;
    --text:          #2E1A10;
    --text-muted:    #8C7B6A;
    --radius-sm:     6px;
    --radius-md:     10px;
    --radius-lg:     16px;
    --radius-xl:     22px;
    --shadow-sm:     0 1px 4px rgba(46,26,16,.07);
    --shadow-md:     0 4px 16px rgba(46,26,16,.10);
    --font-heading:  'Playfair Display', serif;
    --font-body:     'DM Sans', sans-serif;
    --transition:    0.2s ease;
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: var(--font-body);
    background: var(--cream);
    color: var(--text);
    min-height: 100vh;
  }

  /* ── TOPBAR ── */
  .topbar {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 0 2rem;
    height: 60px;
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
    letter-spacing: 0.01em;
  }

  .topbar__brand span { color: var(--gold); }

  .topbar__back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: var(--text-muted);
    text-decoration: none;
    font-weight: 500;
    transition: color var(--transition);
  }

  .topbar__back:hover { color: var(--primary); }

  /* ── LAYOUT ── */
  .page {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2.5rem 1.5rem 4rem;
    display: flex;
    gap: 2rem;
    align-items: flex-start;
  }

  /* ── PAGE HEADER ── */
  .page-header { margin-bottom: 2rem; }

  .page-header__eyebrow {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: var(--primary);
    font-weight: 600;
    margin-bottom: 0.4rem;
  }

  .page-header__title {
    font-family: var(--font-heading);
    font-size: 2rem;
    color: var(--primary-dark);
    line-height: 1.15;
  }

  /* ── SIDEBAR NAV ── */
  .sidebar {
    flex: 0 0 200px;
    position: sticky;
    top: 80px;
  }

  .nav-menu {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
  }

  .nav-menu__item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0.85rem 1.2rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--text-muted);
    cursor: pointer;
    border-left: 3px solid transparent;
    transition: all var(--transition);
    border-bottom: 1px solid var(--border);
    user-select: none;
  }

  .nav-menu__item:last-child { border-bottom: none; }
  .nav-menu__item:hover { background: var(--cream); color: var(--primary); }

  .nav-menu__item.active {
    color: var(--primary);
    background: rgba(192,57,90,.05);
    border-left-color: var(--primary);
    font-weight: 600;
  }

  .nav-menu__icon { font-size: 1.1rem; flex-shrink: 0; width: 20px; text-align: center; }

  /* ── MAIN CONTENT ── */
  .content { flex: 1; min-width: 0; }

  /* ── SECTION PANEL ── */
  .section-panel { display: none; animation: fadeIn 0.25s ease both; }
  .section-panel.active { display: block; }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ── CARD ── */
  .card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    margin-bottom: 1.25rem;
    overflow: hidden;
  }

  .card__header {
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .card__header-icon {
    width: 32px; height: 32px;
    border-radius: 8px;
    background: var(--cream-dark);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
  }

  .card__header h3 {
    font-family: var(--font-heading);
    font-size: 1rem;
    color: var(--primary-dark);
    font-weight: 600;
  }

  .card__header p { font-size: 0.75rem; color: var(--text-muted); margin-top: 1px; }

  .card__body { padding: 1.5rem; }

  /* ── ALERTS ── */
  .alert {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0.75rem 1rem;
    border-radius: var(--radius-md);
    font-size: 0.82rem;
    font-weight: 500;
    margin-bottom: 1.25rem;
    animation: fadeIn 0.3s ease both;
  }

  .alert--success { background: #d4edda; color: #2d6a4f; border: 1px solid #a8d5b5; }
  .alert--error   { background: #fdecea; color: #c62828; border: 1px solid #e57373; }

  /* ── FORM ELEMENTS ── */
  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
  }

  .form-row--full { grid-template-columns: 1fr; }

  .form-group { display: flex; flex-direction: column; gap: 5px; }

  .form-group label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
  }

  .form-group input,
  .form-group select,
  .form-group textarea {
    padding: 0.6rem 0.9rem;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-md);
    background: var(--cream);
    color: var(--text);
    font-size: 0.87rem;
    font-family: var(--font-body);
    outline: none;
    transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
    appearance: none;
  }

  .form-group input:focus,
  .form-group select:focus,
  .form-group textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(192,57,90,.1);
    background: var(--white);
  }

  .form-group input.is-invalid { border-color: #e57373; }

  .field-error { font-size: 0.72rem; color: #c62828; margin-top: 2px; }

  .form-group textarea { resize: vertical; min-height: 80px; }

  /* ── AVATAR ── */
  .avatar-row {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border);
  }

  .avatar {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-light), var(--primary));
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-heading);
    font-size: 1.8rem;
    color: var(--white);
    flex-shrink: 0;
    border: 3px solid var(--white);
    box-shadow: 0 0 0 2px var(--border);
  }

  .avatar-info h4 { font-family: var(--font-heading); font-size: 1rem; color: var(--primary-dark); }
  .avatar-info p  { font-size: 0.78rem; color: var(--text-muted); margin-top: 2px; }

  .btn-change-avatar {
    margin-top: 8px;
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--primary);
    background: none;
    border: 1px solid var(--primary-light);
    border-radius: var(--radius-sm);
    padding: 4px 10px;
    cursor: pointer;
    transition: background var(--transition);
  }

  .btn-change-avatar:hover { background: rgba(192,57,90,.07); }

  /* ── TOGGLE ROW ── */
  .toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.85rem 0;
    border-bottom: 1px solid var(--border);
  }

  .toggle-row:last-child { border-bottom: none; }

  .toggle-row__info h4 { font-size: 0.87rem; font-weight: 600; color: var(--text); }
  .toggle-row__info p  { font-size: 0.75rem; color: var(--text-muted); margin-top: 2px; }

  .toggle { position: relative; width: 42px; height: 24px; flex-shrink: 0; }
  .toggle input { opacity: 0; width: 0; height: 0; position: absolute; }

  .toggle__track {
    position: absolute; inset: 0;
    border-radius: 12px;
    background: #ddd;
    cursor: pointer;
    transition: background 0.25s ease;
  }

  .toggle__track::before {
    content: '';
    position: absolute;
    top: 3px; left: 3px;
    width: 18px; height: 18px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
    transition: transform 0.25s ease;
  }

  .toggle input:checked + .toggle__track { background: #22c55e; }
  .toggle input:checked + .toggle__track::before { transform: translateX(18px); }

  /* ── COLOR SWATCHES ── */
  .swatch-grid { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 0.5rem; }

  .swatch {
    width: 34px; height: 34px;
    border-radius: 50%;
    cursor: pointer;
    border: 3px solid transparent;
    transition: transform var(--transition), border-color var(--transition);
    position: relative;
  }

  .swatch:hover { transform: scale(1.12); }

  .swatch.selected {
    border-color: var(--text);
    box-shadow: 0 0 0 2px var(--white) inset;
  }

  .swatch::after {
    content: '✓';
    position: absolute; inset: 0;
    display: none;
    align-items: center; justify-content: center;
    font-size: 0.8rem; color: #fff; font-weight: 700;
  }

  .swatch.selected::after { display: flex; }

  /* ── SELECT STYLÉ ── */
  .select-wrap { position: relative; }

  .select-wrap::after {
    content: '▾';
    position: absolute; right: 12px; top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: var(--text-muted); font-size: 0.8rem;
  }

  /* ── BADGE ── */
  .badge {
    display: inline-flex; align-items: center;
    padding: 3px 9px;
    border-radius: 999px;
    font-size: 0.68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.07em;
  }

  .badge--green { background: #d4edda; color: #2d6a4f; }
  .badge--gold  { background: var(--gold-light); color: var(--gold-dark); }
  .badge--red   { background: #fdecea; color: #c62828; }

  /* ── COMMANDE ITEM ── */
  .order-item {
    display: flex; align-items: center; gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--border);
  }

  .order-item:last-child { border-bottom: none; }

  .order-item__num {
    font-family: var(--font-heading);
    font-size: 1rem; color: var(--primary); font-weight: 700;
    min-width: 70px;
  }

  .order-item__detail { flex: 1; }
  .order-item__detail h4 { font-size: 0.87rem; font-weight: 600; color: var(--text); }
  .order-item__detail p  { font-size: 0.75rem; color: var(--text-muted); margin-top: 2px; }

  .order-item__price { font-weight: 700; color: var(--gold-dark); font-size: 0.9rem; white-space: nowrap; }

  /* ── BOUTONS ── */
  .btn-row {
    display: flex; gap: 0.75rem;
    margin-top: 1.5rem; padding-top: 1.25rem;
    border-top: 1px solid var(--border);
  }

  .btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    padding: 0.6rem 1.4rem;
    border-radius: 999px;
    font-family: var(--font-body);
    font-size: 0.8rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: 0.09em;
    cursor: pointer;
    border: 1.5px solid transparent;
    transition: all var(--transition);
  }

  .btn--primary { background: var(--primary); color: #fff; border-color: var(--primary); }
  .btn--primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }

  .btn--ghost { background: transparent; color: var(--text-muted); border-color: var(--border); }
  .btn--ghost:hover { background: var(--cream); color: var(--text); }

  .btn--danger { background: transparent; color: #c62828; border-color: #e57373; }
  .btn--danger:hover { background: #fdecea; }

  /* ── DANGER ZONE ── */
  .danger-zone { border-color: #e57373; }
  .danger-zone .card__header { background: #fff5f5; border-bottom-color: #e57373; }
  .danger-zone .card__header h3 { color: #c62828; }

  /* ── RESPONSIVE ── */
  @media (max-width: 768px) {
    .page { flex-direction: column; gap: 1rem; }
    .sidebar { flex: none; width: 100%; position: static; }
    .nav-menu { display: flex; overflow-x: auto; border-radius: var(--radius-md); }
    .nav-menu__item { border-bottom: none; border-left: none; border-bottom: 3px solid transparent; white-space: nowrap; }
    .nav-menu__item.active { border-bottom-color: var(--primary); border-left-color: transparent; }
    .form-row { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>

<!-- TOPBAR -->
<header class="topbar">
  <span class="topbar__brand">Maison des <span>Meringues</span></span>
  <a href="{{ route('gestion') }}" class="topbar__back">← Retour à l'administration</a>
</header>

<div class="page">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="page-header">
      <p class="page-header__eyebrow">Administration</p>
      <h1 class="page-header__title">Paramètres</h1>
    </div>

    <nav class="nav-menu">
      @auth
        @if (Auth::user()->role === 'admin')
          <div class="nav-menu__item {{ session('active_tab', 'compte') === 'compte' ? 'active' : '' }}" data-tab="compte">
            <span class="nav-menu__icon">👤</span> Compte
          </div>
          <div class="nav-menu__item {{ session('active_tab') === 'notif' ? 'active' : '' }}" data-tab="notif">
            <span class="nav-menu__icon">🔔</span> Notifications
          </div>
          <div class="nav-menu__item {{ session('active_tab') === 'commande' ? 'active' : '' }}" data-tab="commande">
            <span class="nav-menu__icon">📦</span> Commandes
          </div>
        @else
          <div class="nav-menu__item active" data-tab="compte">
            <span class="nav-menu__icon">👤</span> Compte
          </div>
        @endif
      @endauth
    </nav>
  </aside>

  <!-- CONTENT -->
  <main class="content">

    <!-- ═══ COMPTE ═══ -->
    <div class="section-panel active" id="tab-compte">

      {{-- ── Profil ── --}}
      <div class="card">
        <div class="card__header">
          <div class="card__header-icon">👤</div>
          <div>
            <h3>Informations personnelles</h3>
            <p>Gérez votre profil et vos accès</p>
          </div>
        </div>
        <div class="card__body">

          @if(session('success_profile'))
            <div class="alert alert--success">✅ {{ session('success_profile') }}</div>
          @endif

          <div class="avatar-row">
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="avatar-info">
              <h4>{{ Auth::user()->name }}</h4>
              <p>{{ Auth::user()->role }}</p>
              <button type="button" class="btn-change-avatar">Changer l'avatar</button>
            </div>
          </div>

          <form action="{{ route('settings.profile') }}" method="POST">
            @csrf

            <div class="form-row form-row--full">
              <div class="form-group">
                <label>Nom complet</label>
                <input type="text" name="name"
                       value="{{ old('name', Auth::user()->name) }}"
                       class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                       required>
                @error('name') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"
                       value="{{ old('email', Auth::user()->email) }}"
                       class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                       required>
                @error('email') <span class="field-error">{{ $message }}</span> @enderror
              </div>
              <div class="form-group">
                <label>Téléphone</label>
                <input type="tel" name="phone"
                       value="{{ old('phone', Auth::user()->phone) }}"
                       placeholder="ex: 06 12 34 56 78">
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

      {{-- ── Mot de passe ── --}}
      <div class="card">
        <div class="card__header">
          <div class="card__header-icon">🔒</div>
          <div>
            <h3>Mot de passe</h3>
            <p>Modifiez votre mot de passe de connexion</p>
          </div>
        </div>
        <div class="card__body">

          @if(session('success_password'))
            <div class="alert alert--success">✅ {{ session('success_password') }}</div>
          @endif

          <form action="{{ route('settings.password') }}" method="POST">
            @csrf

            <div class="form-row form-row--full">
              <div class="form-group">
                <label>Mot de passe actuel</label>
                <input type="password" name="current_password" placeholder="••••••••"
                       class="{{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                       required>
                @error('current_password') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Nouveau mot de passe</label>
                <input type="password" name="password" placeholder="••••••••"
                       class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                       required>
                @error('password') <span class="field-error">{{ $message }}</span> @enderror
              </div>
              <div class="form-group">
                <label>Confirmer</label>
                <input type="password" name="password_confirmation" placeholder="••••••••" required>
              </div>
            </div>

            <div class="btn-row">
              <button type="submit" class="btn btn--primary">Changer le mot de passe</button>
            </div>
          </form>
        </div>
      </div>

      {{-- ── Zone danger ── --}}
      <div class="card danger-zone">
        <div class="card__header">
          <div class="card__header-icon">⚠️</div>
          <div>
            <h3>Zone de danger</h3>
            <p>Actions irréversibles</p>
          </div>
        </div>
        <div class="card__body">
          <div class="toggle-row">
            <div class="toggle-row__info">
              <h4>Désactiver le compte</h4>
              <p>Votre compte sera suspendu mais les données conservées</p>
            </div>
            <form action="{{ route('settings.deactivate') }}" method="POST"
                  onsubmit="return confirm('Êtes-vous sûr ? Cette action suspendra votre compte.')">
              @csrf
              <button type="submit" class="btn btn--danger">Désactiver</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══ NOTIFICATIONS ═══ -->
    @if(Auth::user()->role === 'admin')
    <div class="section-panel" id="tab-notif">

      <div class="card">
        <div class="card__header">
          <div class="card__header-icon">📧</div>
          <div>
            <h3>Notifications par e-mail</h3>
            <p>Choisissez ce que vous recevez</p>
          </div>
        </div>
        <div class="card__body">
          <div class="toggle-row">
            <div class="toggle-row__info">
              <h4>Nouvelle commande</h4>
              <p>Recevoir un e-mail à chaque nouvelle commande</p>
            </div>
            <label class="toggle">
              <input type="checkbox" checked>
              <span class="toggle__track"></span>
            </label>
          </div>
          <div class="toggle-row">
            <div class="toggle-row__info">
              <h4>Commande expédiée</h4>
              <p>Confirmation d'expédition au client</p>
            </div>
            <label class="toggle">
              <input type="checkbox" checked>
              <span class="toggle__track"></span>
            </label>
          </div>
          <div class="toggle-row">
            <div class="toggle-row__info">
              <h4>Rupture de stock</h4>
              <p>Alerte quand un produit est épuisé</p>
            </div>
            <label class="toggle">
              <input type="checkbox">
              <span class="toggle__track"></span>
            </label>
          </div>
          <div class="toggle-row">
            <div class="toggle-row__info">
              <h4>Rapport hebdomadaire</h4>
              <p>Résumé des ventes chaque lundi</p>
            </div>
            <label class="toggle">
              <input type="checkbox" checked>
              <span class="toggle__track"></span>
            </label>
          </div>
          <div class="toggle-row">
            <div class="toggle-row__info">
              <h4>Avis client</h4>
              <p>Notifier à chaque nouvel avis déposé</p>
            </div>
            <label class="toggle">
              <input type="checkbox">
              <span class="toggle__track"></span>
            </label>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card__header">
          <div class="card__header-icon">🔔</div>
          <div>
            <h3>Notifications push</h3>
            <p>Alertes dans le navigateur</p>
          </div>
        </div>
        <div class="card__body">
          <div class="toggle-row">
            <div class="toggle-row__info">
              <h4>Activer les notifications push</h4>
              <p>Autorisez le navigateur à envoyer des alertes</p>
            </div>
            <label class="toggle">
              <input type="checkbox">
              <span class="toggle__track"></span>
            </label>
          </div>
          <div class="toggle-row">
            <div class="toggle-row__info">
              <h4>Sons activés</h4>
              <p>Son de notification lors d'une alerte</p>
            </div>
            <label class="toggle">
              <input type="checkbox" checked>
              <span class="toggle__track"></span>
            </label>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card__header">
          <div class="card__header-icon">⏰</div>
          <div>
            <h3>Fréquence des alertes</h3>
            <p>Regroupement des notifications</p>
          </div>
        </div>
        <div class="card__body">
          <div class="form-row form-row--full">
            <div class="form-group">
              <label>Regrouper les notifications</label>
              <div class="select-wrap">
                <select>
                  <option>Immédiatement</option>
                  <option selected>Toutes les heures</option>
                  <option>Une fois par jour</option>
                  <option>Jamais</option>
                </select>
              </div>
            </div>
          </div>
          <div class="btn-row">
            <button class="btn btn--primary">Enregistrer les préférences</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══ COMMANDES ═══ -->
    <div class="section-panel" id="tab-commande">

      <div class="card">
        <div class="card__header">
          <div class="card__header-icon">⚙️</div>
          <div>
            <h3>Paramètres de commande</h3>
            <p>Comportement par défaut des commandes</p>
          </div>
        </div>
        <div class="card__body">
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
          <div class="toggle-row">
            <div class="toggle-row__info">
              <h4>Click & Collect activé</h4>
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
              <p>Activer les commandes avec livraison à domicile</p>
            </div>
            <label class="toggle">
              <input type="checkbox">
              <span class="toggle__track"></span>
            </label>
          </div>
          <div class="form-row" style="margin-top:1.25rem">
            <div class="form-group">
              <label>Délai de préparation (jours)</label>
              <input type="number" value="2" min="1" max="14">
            </div>
            <div class="form-group">
              <label>Commande minimum (€)</label>
              <input type="number" value="15" min="0">
            </div>
          </div>
          <div class="btn-row">
            <button class="btn btn--primary">Enregistrer</button>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card__header">
          <div class="card__header-icon">📦</div>
          <div>
            <h3>Dernières commandes</h3>
            <p>Aperçu des commandes récentes</p>
          </div>
        </div>
        <div class="card__body">
          <div class="order-item">
            <div class="order-item__num">#1042</div>
            <div class="order-item__detail">
              <h4>Sophie Martin</h4>
              <p>3 produits · Aujourd'hui 10h34</p>
            </div>
            <span class="badge badge--gold">En préparation</span>
            <div class="order-item__price">28,50 €</div>
          </div>
          <div class="order-item">
            <div class="order-item__num">#1041</div>
            <div class="order-item__detail">
              <h4>Thomas Leclerc</h4>
              <p>1 produit · Hier 16h12</p>
            </div>
            <span class="badge badge--green">Expédiée</span>
            <div class="order-item__price">12,00 €</div>
          </div>
          <div class="order-item">
            <div class="order-item__num">#1040</div>
            <div class="order-item__detail">
              <h4>Claire Fontaine</h4>
              <p>5 produits · Hier 09h50</p>
            </div>
            <span class="badge badge--green">Livrée</span>
            <div class="order-item__price">54,00 €</div>
          </div>
          <div class="order-item">
            <div class="order-item__num">#1039</div>
            <div class="order-item__detail">
              <h4>Paul Bernard</h4>
              <p>2 produits · 02/05 14h20</p>
            </div>
            <span class="badge badge--red">Annulée</span>
            <div class="order-item__price">19,90 €</div>
          </div>
        </div>
      </div>

    </div>
    @endif

  </main>
</div>

<script>
  // ── Onglet actif depuis session PHP si erreur de validation ──
  const initialTab = '{{ session("active_tab", "compte") }}';

  // ── Navigation onglets ──
  function activateTab(tabName) {
    document.querySelectorAll('.nav-menu__item').forEach(i => i.classList.remove('active'));
    document.querySelectorAll('.section-panel').forEach(p => p.classList.remove('active'));

    const item = document.querySelector(`[data-tab="${tabName}"]`);
    const panel = document.getElementById('tab-' + tabName);
    if (item)  item.classList.add('active');
    if (panel) panel.classList.add('active');
  }

  document.querySelectorAll('.nav-menu__item').forEach(item => {
    item.addEventListener('click', () => activateTab(item.dataset.tab));
  });

  // Active l'onglet initial (retour après erreur)
  activateTab(initialTab);

  // ── Swatches couleur ──
  document.querySelectorAll('.swatch').forEach(swatch => {
    swatch.addEventListener('click', () => {
      document.querySelectorAll('.swatch').forEach(s => s.classList.remove('selected'));
      swatch.classList.add('selected');
    });
  });
</script>

</body>
</html>
