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
      <button type="button" class="tab-btn active" id="signupTab">Inscription</button>
	  <button type="button" class="tab-btn" id="loginTab" onclick="window.location.href='{{ route('login') }}'">Connexion</button>
	</div>

	<!-- ── SIGNUP ─────────────────────────────────────────── -->
	<div class="form-panel active" id="signupPanel">
	  <div class="form-header">
		<h1>Créer un compte</h1>
		<p>Rejoignez La Maison des Meringues.</p>
	  </div>

	  <form method="POST" action="{{ route('register') }}">
		@csrf

		{{-- Erreurs globales --}}
		@if($errors->any())
		  <div style="background:#fdecea;color:#c0392b;padding:10px 14px;border-radius:8px;margin-bottom:16px;font-size:.85rem;">
			<ul style="margin:0;padding-left:16px;">
			  @foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			  @endforeach
			</ul>
		  </div>
		@endif

		<div class="field">
		  <label for="name">Nom complet</label>
		  <div class="field-wrap">
			<span class="field-icon">👤</span>
			<input id="name" type="text" name="name"
				   value="{{ old('name') }}"
				   placeholder="Marie Dupont" required autofocus autocomplete="name" />
		  </div>
		</div>

		<div class="field">
		  <label for="email">Adresse e-mail</label>
		  <div class="field-wrap">
			<span class="field-icon">✉</span>
			<input id="email" type="email" name="email"
				   value="{{ old('email') }}"
				   placeholder="vous@exemple.com" required autocomplete="username" />
		  </div>
		</div>

		<div class="field">
		  <label for="password">Mot de passe</label>
		  <div class="field-wrap">
			<span class="field-icon">🔒</span>
			<input id="password" type="password" name="password"
				   placeholder="8 caractères minimum" required autocomplete="new-password" />
		  </div>
		</div>

		<div class="field">
		  <label for="password_confirmation">Confirmer le mot de passe</label>
		  <div class="field-wrap">
			<span class="field-icon">🔒</span>
			<input id="password_confirmation" type="password" name="password_confirmation"
				   placeholder="Répétez votre mot de passe" required autocomplete="new-password" />
		  </div>
		</div>

		<button type="submit" class="btn-submit">Créer mon compte →</button>

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
