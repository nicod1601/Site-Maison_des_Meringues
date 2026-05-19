<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenue — La Maison des Meringues</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Cormorant+Garamond:ital@1&family=Lato:wght@400;600&display=swap" rel="stylesheet">
  <style>
	* { margin: 0; padding: 0; box-sizing: border-box; }

	body {
	  background-color: #ECD9B0;
	  font-family: 'Lato', Arial, sans-serif;
	  min-height: 100vh;
	}

	.email-wrapper {
	  width: 100%;
	  min-height: 100vh;
	  background-color: #ECD9B0;
	  padding: 40px 20px;
	}

	.email-card {
	  max-width: 620px;
	  margin: 0 auto;
	  background-color: #7A1C3B;
	  border-radius: 24px;
	  overflow: hidden;
	  border: 2px solid #C9A84C;
	}

	/* ── HEADER ── */
	.card-header {
	  background-color: #7A1C3B;
	  padding: 48px 48px 0;
	  text-align: center;
	  position: relative;
	}

	.card-header::before {
	  content: '';
	  position: absolute;
	  inset: 0;
	  opacity: 0.05;
	  background-image:
		repeating-linear-gradient(45deg, #C9A84C 0px, #C9A84C 1px, transparent 1px, transparent 14px),
		repeating-linear-gradient(-45deg, #C9A84C 0px, #C9A84C 1px, transparent 1px, transparent 14px);
	}

	.header-inner { position: relative; z-index: 1; }

	.logo-circle {
	  width: 72px;
	  height: 72px;
	  border-radius: 50%;
	  background: #5A1228;
	  border: 2px solid #C9A84C;
	  margin: 0 auto 20px;
	  display: flex;
	  align-items: center;
	  justify-content: center;
	}

	.brand-eyebrow {
	  font-family: 'Cormorant Garamond', Georgia, serif;
	  font-style: italic;
	  font-size: 13px;
	  color: #C9A84C;
	  letter-spacing: 0.2em;
	  margin-bottom: 10px;
	}

	.brand-name {
	  font-family: 'Playfair Display', Georgia, serif;
	  font-size: 32px;
	  font-weight: 700;
	  color: #E8CC89;
	  letter-spacing: 0.04em;
	  line-height: 1.15;
	}

	.brand-sub {
	  font-family: 'Cormorant Garamond', Georgia, serif;
	  font-style: italic;
	  font-size: 14px;
	  color: #E8839A;
	  margin-top: 8px;
	}

	.gold-line {
	  height: 1px;
	  background: linear-gradient(90deg, transparent, #C9A84C, transparent);
	  margin: 28px 60px 0;
	  opacity: 0.6;
	}

	/* ── BODY ── */
	.card-body {
	  background-color: #F5EDD6;
	  padding: 44px 48px;
	  text-align: center;
	}

	.welcome-eyebrow {
	  font-size: 11px;
	  text-transform: uppercase;
	  letter-spacing: 0.2em;
	  color: #C9A84C;
	  font-weight: 600;
	  margin-bottom: 16px;
	}

	.welcome-title {
	  font-family: 'Playfair Display', Georgia, serif;
	  font-size: 28px;
	  font-weight: 700;
	  color: #7A1C3B;
	  line-height: 1.3;
	  margin-bottom: 20px;
	}

	.welcome-title em {
	  font-style: italic;
	  color: #C0395A;
	  font-family: 'Cormorant Garamond', Georgia, serif;
	  font-size: 1.1em;
	}

	.welcome-text {
	  font-size: 15px;
	  color: #6B4C3B;
	  line-height: 1.8;
	  max-width: 460px;
	  margin: 0 auto 32px;
	}

	.ornament {
	  font-family: 'Cormorant Garamond', serif;
	  color: #C9A84C;
	  font-size: 18px;
	  letter-spacing: 0.5em;
	  margin: 28px 0;
	  opacity: 0.7;
	}

	/* ── HIGHLIGHTS ── */
	.highlights {
	  display: table;
	  width: 100%;
	  margin: 0 0 36px;
	  border-collapse: separate;
	  border-spacing: 12px 0;
	}

	.highlight-cell {
	  display: table-cell;
	  width: 33.33%;
	  background: #FAF6EE;
	  border: 1px solid #D9C49A;
	  border-radius: 12px;
	  padding: 20px 12px;
	  text-align: center;
	  vertical-align: top;
	}

	.highlight-icon {
	  font-size: 24px;
	  margin-bottom: 8px;
	  display: block;
	}

	.highlight-label {
	  font-family: 'Playfair Display', Georgia, serif;
	  font-size: 13px;
	  font-weight: 700;
	  color: #7A1C3B;
	  margin-bottom: 4px;
	}

	.highlight-desc {
	  font-size: 11px;
	  color: #6B4C3B;
	  line-height: 1.5;
	}

	/* ── CTA ── */
	.cta-btn {
	  display: inline-block;
	  background-color: #C0395A;
	  color: #FAF6EE !important;
	  text-decoration: none;
	  font-family: 'Lato', Arial, sans-serif;
	  font-size: 13px;
	  font-weight: 700;
	  text-transform: uppercase;
	  letter-spacing: 0.14em;
	  padding: 14px 40px;
	  border-radius: 100px;
	  margin-bottom: 36px;
	}

	/* ── FOOTER CARD ── */
	.card-footer {
	  background-color: #7A1C3B;
	  padding: 28px 48px;
	  text-align: center;
	  position: relative;
	}

	.card-footer::before {
	  content: '';
	  position: absolute;
	  inset: 0;
	  opacity: 0.05;
	  background-image:
		repeating-linear-gradient(45deg, #C9A84C 0px, #C9A84C 1px, transparent 1px, transparent 14px),
		repeating-linear-gradient(-45deg, #C9A84C 0px, #C9A84C 1px, transparent 1px, transparent 14px);
	}

	.footer-inner { position: relative; z-index: 1; }

	.footer-contact {
	  font-size: 11px;
	  color: #ECD9B0;
	  opacity: 0.8;
	  line-height: 2;
	}

	.footer-contact a {
	  color: #C9A84C;
	  text-decoration: none;
	}

	.footer-legal {
	  font-size: 10px;
	  color: #ECD9B0;
	  opacity: 0.4;
	  margin-top: 16px;
	}
  </style>
</head>
<body>
<div class="email-wrapper">
  <table class="email-card" width="100%" cellpadding="0" cellspacing="0" border="0">

	{{-- ── HEADER ── --}}
	<tr>
	  <td class="card-header">
		<div class="header-inner">

		  <div class="logo-circle">
			<svg width="38" height="38" viewBox="0 0 38 38" fill="none">
			  <ellipse cx="19" cy="26" rx="11" ry="7" fill="#C9A84C" opacity="0.3"/>
			  <ellipse cx="19" cy="23" rx="8" ry="10" fill="#E8CC89" opacity="0.4"/>
			  <ellipse cx="19" cy="20" rx="6"  ry="8"  fill="#FAF6EE"/>
			  <ellipse cx="19" cy="17" rx="4"  ry="6"  fill="#E8CC89"/>
			  <ellipse cx="19" cy="13" rx="2.5" ry="4" fill="#C9A84C"/>
			  <circle  cx="19" cy="10" r="1.8"          fill="#C0395A"/>
			</svg>
		  </div>

		  <div class="brand-eyebrow">Artisanat sucré depuis 1987</div>
		  <div class="brand-name">La Maison<br>des Meringues</div>
		  <div class="brand-sub">Paris · Boutique & Atelier</div>
		  <div class="gold-line"></div>
		</div>
	  </td>
	</tr>

	{{-- ── BODY ── --}}
	<tr>
	  <td class="card-body">

		<div class="welcome-eyebrow">✦ &nbsp; Bienvenue dans notre famille &nbsp; ✦</div>

		<div class="welcome-title">
		  Bonjour <em>{{ $prenom ?? 'cher(e) gourmand(e)' }}</em>,<br>
		  nous sommes ravis de vous accueillir !
		</div>

		<p class="welcome-text">
		  Votre compte vient d'être créé sur <strong>La Maison des Meringues</strong>.
		  Découvrez nos créations artisanales, passez commande en ligne et venez nous
		  rendre visite dans notre atelier parisien.
		</p>

		<div class="ornament">— ✦ —</div>

		{{-- Highlights --}}
		<table class="highlights" cellpadding="0" cellspacing="0" border="0">
		  <tr>
			<td class="highlight-cell">
			  <span class="highlight-icon">🎂</span>
			  <div class="highlight-label">Créations</div>
			  <div class="highlight-desc">Plus de 30 saveurs artisanales faites à la main</div>
			</td>
			<td class="highlight-cell">
			  <span class="highlight-icon">🚚</span>
			  <div class="highlight-label">Livraison</div>
			  <div class="highlight-desc">Expédition soignée partout en France</div>
			</td>
			<td class="highlight-cell">
			  <span class="highlight-icon">🎁</span>
			  <div class="highlight-label">Coffrets</div>
			  <div class="highlight-desc">Boîtes cadeaux personnalisées pour toutes occasions</div>
			</td>
		  </tr>
		</table>

		<a href="{{ $shop_url ?? '#' }}" class="cta-btn">
		  Découvrir la boutique
		</a>

	  </td>
	</tr>

	{{-- ── FOOTER ── --}}
	<tr>
	  <td class="card-footer">
		<div class="footer-inner">
		  <p class="footer-contact">
			📍 12 rue des Rosiers, Paris 4ème &nbsp;·&nbsp;
			🕐 Mar–Sam · 9h–19h30<br>
			<a href="mailto:contact@maison-meringues.fr">contact@maison-meringues.fr</a>
			&nbsp;·&nbsp;
			<a href="#">www.maisondesmeringues.fr</a>
		  </p>
		  <p class="footer-legal">
			Vous recevez cet email car vous venez de créer un compte sur notre boutique.<br>
			© {{ date('Y') }} La Maison des Meringues — Tous droits réservés.
		  </p>
		</div>
	  </td>
	</tr>

  </table>
</div>
</body>
</html>
