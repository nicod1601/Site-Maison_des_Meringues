<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>La Maison des Meringues — Connexion</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  @vite('resources/css/login.css')
</head>
<body>

  <!-- LEFT — Image / Brand -->
  <div class="panel-image">
	<div class="deco-circle"></div>
	<div class="deco-circle"></div>
	<div class="deco-circle"></div>

	<div class="brand-logo">
	  <div class="logo-icon">🍬</div>
	  <div class="logo-text">
		<strong>La Maison</strong>
		<span>des Meringues</span>
	  </div>
	</div>

	<div class="meringue-art">
	  <div class="swirl"></div>
	  <div class="swirl"></div>
	  <div class="swirl"></div>
	  <div class="swirl"></div>
	</div>

	<div class="panel-image-content">
	  <p class="tagline">L'art de la<br><em>meringue française</em><br>à portée de clic.</p>
	  <p class="sub">Commandez vos douceurs avec élégance.</p>
	</div>
  </div>

  <!-- RIGHT — Form -->
  <div class="panel-form">

	<div class="tabs" id="tabs">
	  <div class="tab-slider"></div>
	  <button type="button" class="tab-btn active" id="loginTab">Connexion</button>
	  <button type="button" class="tab-btn" id="signupTab" onclick="window.location.href='{{ route('register') }}'">Inscription</button>
	</div>

	<!-- ── LOGIN ─────────────────────────────────────────── -->
	<div class="form-panel active" id="loginPanel">
	  <div class="form-header">
		<h1>Bon retour !</h1>
		<p>Connectez-vous à votre espace client.</p>
	  </div>

	  {{-- Message de statut (ex: après reset mot de passe) --}}
	  @if(session('status'))
		<div style="background:#d4edda;color:#155724;padding:10px 14px;border-radius:8px;margin-bottom:16px;font-size:.85rem;">
		  {{ session('status') }}
		</div>
	  @endif

	  {{-- Erreurs --}}
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
		  <label for="email">Adresse e-mail</label>
		  <div class="field-wrap">
			<span class="field-icon">✉</span>
			<input id="email" type="email" name="email"
				   value="{{ old('email') }}"
				   placeholder="vous@exemple.com"
				   required autofocus autocomplete="username" />
		  </div>
		</div>

		<div class="field">
		  <label for="password">Mot de passe</label>
		  <div class="field-wrap">
			<span class="field-icon">🔒</span>
			<input id="password" type="password" name="password"
				   placeholder="••••••••"
				   required autocomplete="current-password" />
		  </div>
		</div>

		{{-- Se souvenir de moi --}}
		<div style="display:flex;align-items:center;gap:8px;margin-bottom:var(--space-sm);">
		  <input type="checkbox" id="remember_me" name="remember"
				 style="width:16px;height:16px;accent-color:var(--color-primary);cursor:pointer;">
		  <label for="remember_me" style="font-size:.85rem;color:var(--color-text-muted);cursor:pointer;">
			Se souvenir de moi
		  </label>
		</div>

		@if(Route::has('password.request'))
		  <a href="{{ route('password.request') }}" class="forgot">Mot de passe oublié ?</a>
		@endif

		<button type="submit" class="btn-submit">Se connecter →</button>

		<p class="terms">
		  Pas encore de compte ?
		  <a href="{{ route('register') }}">Créer un compte</a>
		</p>

	  </form>
	</div>

  </div>

</body>
</html>
