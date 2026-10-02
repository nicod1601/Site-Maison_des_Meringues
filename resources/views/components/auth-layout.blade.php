@props([
    'title'    => 'Espace client',
    'tab'      => null,   // 'login' | 'register' | null (pas d'onglets)
    'heading'  => null,
    'subtitle' => null,
    'back'     => false,  // affiche un lien « Retour à la connexion »
])
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta name="robots" content="noindex" />
  <meta name="theme-color" content="#b03060" />
  <title>La Maison des Meringues — {{ $title }}</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin />
  <link rel="preload" as="image" href="{{ asset('fichier/image/Auberge.webp') }}" type="image/webp" fetchpriority="high" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.48.0/dist/tabler-icons.min.css" />

  @vite(['resources/css/login.css', 'resources/js/auth.js'])

  <style>:root { --cursor: url("{{ asset('fichier/image/sourie/cursor-50.png') }}") 0 0, auto; }</style>
</head>
<body>
  <a href="#main" class="skip-link">Aller au contenu</a>

  {{-- GAUCHE — Photo + accroche --}}
  <aside class="panel-image" aria-hidden="true">
    <picture>
      <source srcset="{{ asset('fichier/image/Auberge.webp') }}" type="image/webp" />
      <img src="{{ asset('fichier/image/Auberge.jpg') }}" alt="" width="1024" height="768"
           fetchpriority="high" decoding="async" />
    </picture>

    <div class="panel-image-content">
      <p class="tagline">
        <span class="line">Meringues Artisanales</span>
        <span class="line"><em>Produits Normands</em></span>
      </p>
    </div>
  </aside>

  {{-- DROITE — Formulaire --}}
  <main class="panel-form" id="main">
    <div class="panel-form__inner">

      <a href="{{ url('/') }}" class="form-logo" aria-label="La Maison des Meringues — retour à l'accueil">
        <span class="form-logo__icon" aria-hidden="true"><i class="ti ti-candy"></i></span>
        <span class="form-logo__text">
          <strong>La Maison des Meringues</strong>
          <span>Espace client</span>
        </span>
      </a>

      @if($tab)
        <nav class="tabs {{ $tab === 'register' ? 'signup-active' : '' }}" aria-label="Connexion ou inscription">
          <span class="tab-slider" aria-hidden="true"></span>
          <a href="{{ route('login') }}"    class="tab-btn {{ $tab === 'login' ? 'active' : '' }}"    @if($tab === 'login') aria-current="page" @endif>Connexion</a>
          <a href="{{ route('register') }}" class="tab-btn {{ $tab === 'register' ? 'active' : '' }}" @if($tab === 'register') aria-current="page" @endif>Inscription</a>
        </nav>
      @endif

      <section class="form-panel">
        @if($heading)
          <header class="form-header">
            <h1>{{ $heading }}</h1>
            @if($subtitle)<p>{{ $subtitle }}</p>@endif
          </header>
        @endif

        @if(session('status') && ! in_array(session('status'), ['verification-link-sent']))
          <div class="alert alert--success" role="status">
            <i class="ti ti-circle-check" aria-hidden="true"></i>
            <span>{{ session('status') }}</span>
          </div>
        @endif

        {{ $slot }}

        @if($back)
          <a href="{{ route('login') }}" class="back-link">
            <i class="ti ti-arrow-left" aria-hidden="true"></i> Retour à la connexion
          </a>
        @endif
      </section>

    </div>
  </main>
</body>
</html>
