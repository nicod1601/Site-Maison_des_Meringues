<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>La Maison des Meringues — Inscription</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
  @vite('resources/css/login.css')
  <style>
	* { cursor: url("{{ asset('fichier/image/sourie/cursor-50.png') }}") 0 0, auto !important; }
	.panel-image { background-image: url("{{ asset('fichier/image/auberge.jpg') }}") !important; }
  </style>
</head>
<body>

  <!-- LEFT — Photo + Tagline -->
  <div class="panel-image">
	<div class="deco-circle" style="position:absolute;width:300px;height:300px;top:-80px;left:-80px;border-radius:50%;background:#fff;opacity:.06;pointer-events:none;"></div>
	<div class="deco-circle" style="position:absolute;width:160px;height:160px;top:38%;right:8%;border-radius:50%;background:#fff;opacity:.05;pointer-events:none;"></div>

	<div class="panel-image-content">
	  <p class="tagline">
		<span class="line">Meringues Artisanales</span>
		<span class="line"><em>Produits Normands</em></span>
	  </p>
	</div>
  </div>

  <!-- RIGHT — Form -->
  <div class="panel-form">

	<div class="form-logo">
	  <div class="form-logo__icon">
		<i class="ti ti-candy"></i>
	  </div>
	  <div class="form-logo__text">
		<strong>La Maison des Meringues</strong>
		<span>Espace client</span>
	  </div>
	</div>

	<div class="tabs signup-active" id="tabs">
	  <div class="tab-slider"></div>
	  <button type="button" class="tab-btn active" id="signupTab">Inscription</button>
	  <button type="button" class="tab-btn" id="loginTab" onclick="window.location.href='{{ route('login') }}'">Connexion</button>
	</div>

	<!-- ── SIGNUP ── -->
	<div class="form-panel active" id="signupPanel">
	  <div class="form-header">
		<h1>Créer un compte</h1>
		<p>Rejoignez La Maison des Meringues.</p>
	  </div>

	  @if($errors->any())
		<div style="background:#fdecea;color:#c0392b;padding:10px 14px;border-radius:8px;margin-bottom:16px;font-size:.85rem;">
		  <ul style="margin:0;padding-left:16px;">
			@foreach($errors->all() as $error)
			  <li>{{ $error }}</li>
			@endforeach
		  </ul>
		</div>
	  @endif

	  <form method="POST" action="{{ route('register') }}">
		@csrf

		<div class="field">
		  <label for="name">
			<i class="ti ti-user"></i> Nom complet
		  </label>
		  <div class="field-wrap">
			<span class="field-icon"><i class="ti ti-user"></i></span>
			<input id="name" type="text" name="name"
				   value="{{ old('name') }}"
				   placeholder="Marie Dupont"
				   required autofocus autocomplete="name" />
		  </div>
		</div>

		<div class="field">
		  <label for="email">
			<i class="ti ti-mail"></i> Adresse e-mail
		  </label>
		  <div class="field-wrap">
			<span class="field-icon"><i class="ti ti-mail"></i></span>
			<input id="email" type="email" name="email"
				   value="{{ old('email') }}"
				   placeholder="vous@exemple.com"
				   required autocomplete="username" />
		  </div>
		</div>

		<div class="field">
		  <label for="password">
			<i class="ti ti-lock"></i> Mot de passe
		  </label>
		  <div class="field-wrap">
			<span class="field-icon"><i class="ti ti-lock"></i></span>
			<input id="password" type="password" name="password"
				   placeholder="8 caractères minimum"
				   required autocomplete="new-password" />
		  </div>
		</div>

		<div class="field">
		  <label for="password_confirmation">
			<i class="ti ti-lock-check"></i> Confirmer le mot de passe
		  </label>
		  <div class="field-wrap">
			<span class="field-icon"><i class="ti ti-lock-check"></i></span>
			<input id="password_confirmation" type="password" name="password_confirmation"
				   placeholder="Répétez votre mot de passe"
				   required autocomplete="new-password" />
		  </div>
		</div>

		<button type="submit" class="btn-submit">
		  <i class="ti ti-user-plus"></i>
		  Créer mon compte
		</button>

		<p class="terms">
		  En créant un compte, vous acceptez nos
		  <a href="#">Conditions d'utilisation</a> et notre
		  <a href="#">Politique de confidentialité</a>.
		</p>

	  </form>
	</div>

  </div>

</body>
</html>
