<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>La Maison des Meringues — Connexion</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
  @vite('resources/css/login.css')
  <style>
    * { cursor: url("{{ asset('fichier/image/sourie/cursor-50.png') }}") 0 0, auto !important; }
    /* Override bg-image avec le vrai asset Laravel */
    .panel-image { background-image: url("{{ asset('fichier/image/auberge.jpg') }}") !important; }
  </style>
</head>
<body>

  <!-- LEFT — Photo + Tagline -->
  <div class="panel-image">

    {{-- Deco circles optionnels, discrets --}}
    <div class="deco-circle" style="position:absolute;width:300px;height:300px;top:-80px;left:-80px;border-radius:50%;background:#fff;opacity:.06;pointer-events:none;"></div>
    <div class="deco-circle" style="position:absolute;width:160px;height:160px;top:38%;right:8%;border-radius:50%;background:#fff;opacity:.05;pointer-events:none;"></div>

    {{-- Tagline grande, occupe toute la moitié basse --}}
    <div class="panel-image-content">
      <p class="tagline">
        <span class="line">Meringues Artisanales</span>
        <span class="line"><em>Produits Normands</em></span>
      </p>
    </div>
  </div>

  <!-- RIGHT — Form -->
  <div class="panel-form">

    {{-- Logo au-dessus des onglets --}}
    <div class="form-logo">
      <div class="form-logo__icon">
        <i class="ti ti-candy"></i>
      </div>
      <div class="form-logo__text">
        <strong>La Maison des Meringues</strong>
        <span>Espace client</span>
      </div>
    </div>

    <div class="tabs" id="tabs">
      <div class="tab-slider"></div>
      <button type="button" class="tab-btn active" id="loginTab">Connexion</button>
      <button type="button" class="tab-btn" id="signupTab" onclick="window.location.href='{{ route('register') }}'">Inscription</button>
    </div>

    <!-- ── LOGIN ── -->
    <div class="form-panel active" id="loginPanel">
      <div class="form-header">
        <h1>Bon retour !</h1>
        <p>Connectez-vous à votre espace client.</p>
      </div>

      @if(session('status'))
        <div style="background:#d4edda;color:#155724;padding:10px 14px;border-radius:8px;margin-bottom:16px;font-size:.85rem;">
          {{ session('status') }}
        </div>
      @endif

      @if($errors->any())
        <div style="background:#fdecea;color:#c0392b;padding:10px 14px;border-radius:8px;margin-bottom:16px;font-size:.85rem;">
          <ul style="margin:0;padding-left:16px;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
          <label for="email">
            <i class="ti ti-mail"></i> Adresse e-mail
          </label>
          <div class="field-wrap">
            <span class="field-icon"><i class="ti ti-mail"></i></span>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="vous@exemple.com"
                   required autofocus autocomplete="username" />
          </div>
        </div>

        <div class="field">
          <label for="password">
            <i class="ti ti-lock"></i> Mot de passe
          </label>
          <div class="field-wrap">
            <span class="field-icon"><i class="ti ti-lock"></i></span>
            <input id="password" type="password" name="password"
                   placeholder="••••••••"
                   required autocomplete="current-password" />
          </div>
        </div>

        <div class="remember-row">
          <input type="checkbox" id="remember_me" name="remember">
          <label for="remember_me">Se souvenir de moi</label>
        </div>

        @if(Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="forgot">Mot de passe oublié ?</a>
        @endif

        <button type="submit" class="btn-submit">
          <i class="ti ti-login"></i>
          Se connecter
        </button>

      </form>
    </div>

  </div>

</body>
</html>
