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
	  <p class="sub">Gérez votre boutique avec élégance.</p>
	</div>
  </div>

  <!-- RIGHT — Form -->
  <div class="panel-form">

	<div class="tabs" id="tabs">
	  <div class="tab-slider"></div>
	  <button class="tab-btn active" id="loginTab" onclick="switchTab('login')">Connexion</button>
	  <button class="tab-btn"        id="signupTab" onclick="switchTab('signup')">Inscription</button>
	</div>

	<!-- LOGIN -->
	<div class="form-panel active" id="loginPanel">
	  <div class="form-header">
		<h1>Bon retour !</h1>
		<p>Connectez-vous à votre espace boutique.</p>
	  </div>

	  <div class="field">
		<label>Adresse e-mail</label>
		<div class="field-wrap">
		  <span class="field-icon">✉</span>
		  <input type="email" placeholder="vous@exemple.com" />
		</div>
	  </div>

	  <div class="field">
		<label>Mot de passe</label>
		<div class="field-wrap">
		  <span class="field-icon">🔒</span>
		  <input type="password" placeholder="••••••••" />
		</div>
	  </div>

	  <a href="#" class="forgot">Mot de passe oublié ?</a>

	  <button class="btn-submit">Se connecter →</button>

	  <div class="divider">ou continuer avec</div>

	  <button class="btn-google">
		<svg width="18" height="18" viewBox="0 0 18 18" fill="none">
		  <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.616z" fill="#4285F4"/>
		  <path d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 009 18z" fill="#34A853"/>
		  <path d="M3.964 10.707A5.41 5.41 0 013.682 9c0-.593.102-1.17.282-1.707V4.961H.957A8.996 8.996 0 000 9c0 1.452.348 2.827.957 4.039l3.007-2.332z" fill="#FBBC05"/>
		  <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 00.957 4.961L3.964 7.293C4.672 5.166 6.656 3.58 9 3.58z" fill="#EA4335"/>
		</svg>
		Continuer avec Google
	  </button>
	</div>

	<!-- SIGNUP -->
	<div class="form-panel" id="signupPanel">
	  <div class="form-header">
		<h1>Créer un compte</h1>
		<p>Rejoignez La Maison des Meringues.</p>
	  </div>

	  <div class="fields-row">
		<div class="field">
		  <label>Prénom</label>
		  <div class="field-wrap">
			<span class="field-icon">👤</span>
			<input type="text" placeholder="Marie" />
		  </div>
		</div>
		<div class="field">
		  <label>Nom</label>
		  <div class="field-wrap">
			<span class="field-icon">👤</span>
			<input type="text" placeholder="Dupont" />
		  </div>
		</div>
	  </div>

	  <div class="field">
		<label>Adresse e-mail</label>
		<div class="field-wrap">
		  <span class="field-icon">✉</span>
		  <input type="email" placeholder="vous@exemple.com" />
		</div>
	  </div>

	  <div class="field">
		<label>Mot de passe</label>
		<div class="field-wrap">
		  <span class="field-icon">🔒</span>
		  <input type="password" placeholder="8 caractères minimum" />
		</div>
	  </div>

	  <div class="field">
		<label>Confirmer le mot de passe</label>
		<div class="field-wrap">
		  <span class="field-icon">🔒</span>
		  <input type="password" placeholder="Répétez votre mot de passe" />
		</div>
	  </div>

	  <button class="btn-submit">Créer mon compte →</button>

	  <div class="divider">ou</div>

	  <button class="btn-google">
		<svg width="18" height="18" viewBox="0 0 18 18" fill="none">
		  <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.616z" fill="#4285F4"/>
		  <path d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 009 18z" fill="#34A853"/>
		  <path d="M3.964 10.707A5.41 5.41 0 013.682 9c0-.593.102-1.17.282-1.707V4.961H.957A8.996 8.996 0 000 9c0 1.452.348 2.827.957 4.039l3.007-2.332z" fill="#FBBC05"/>
		  <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 00.957 4.961L3.964 7.293C4.672 5.166 6.656 3.58 9 3.58z" fill="#EA4335"/>
		</svg>
		S'inscrire avec Google
	  </button>

	  <p class="terms">
		En créant un compte, vous acceptez nos
		<a href="#">Conditions d'utilisation</a> et notre
		<a href="#">Politique de confidentialité</a>.
	  </p>
	</div>

  </div>

  <script>
	function switchTab(tab) {
	  const tabs      = document.getElementById('tabs');
	  const loginTab  = document.getElementById('loginTab');
	  const signupTab = document.getElementById('signupTab');
	  const loginP    = document.getElementById('loginPanel');
	  const signupP   = document.getElementById('signupPanel');

	  if (tab === 'login') {
		tabs.classList.remove('signup-active');
		loginTab.classList.add('active');
		signupTab.classList.remove('active');
		loginP.classList.add('active');
		signupP.classList.remove('active');
	  } else {
		tabs.classList.add('signup-active');
		signupTab.classList.add('active');
		loginTab.classList.remove('active');
		signupP.classList.add('active');
		loginP.classList.remove('active');
	  }
	}
  </script>
</body>
</html>
