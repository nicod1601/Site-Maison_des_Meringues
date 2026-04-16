<footer class="footer">
	<div class="container">
		<div class="footer__grid">

			<!-- Marque -->
			<div class="footer__brand">
				<h3>Maison des <span style="color: var(--color-primary);">Meringues</span></h3>
				<p>Des meringues artisanales préparées avec passion, pour taquiner vos papilles à chaque bouchée.</p>
				<div class="social-links" style="margin-top: var(--space-lg);">
					<a href="#" aria-label="Instagram">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
							<circle cx="12" cy="12" r="4"/>
							<circle cx="17.5" cy="6.5" r="0.5" fill="currentColor"/>
						</svg>
					</a>
					<a href="#" aria-label="Facebook">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
						</svg>
					</a>
				</div>
			</div>

			<!-- Navigation -->
			<div>
				<p class="footer__heading">Navigation</p>
				<ul class="footer__links">
					<li><a href="/">Accueil</a></li>
					<li><a href="/news">Catalogue</a></li>
					<li><a href="/shop">Boutique</a></li>
				</ul>
			</div>

			<!-- Nos produits -->
			<div>
				<p class="footer__heading">Nos produits</p>
				<ul class="footer__links">
					<li><a href="#">Meringues Mini</a></li>
					<li><a href="#">Meringues Nid</a></li>
				</ul>
			</div>

			<!-- Contact & infos -->
			<div>
				<p class="footer__heading">Contact</p>
				<ul class="footer__links">
					<li>
						<a href="mailto:contact@maisondesmeringues.fr" style="display:flex; align-items:flex-start; gap: var(--space-sm);">
							<svg style="margin-top:2px; flex-shrink:0;" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
								<polyline points="22,6 12,13 2,6"/>
							</svg>
							contact@maisondesmeringues.fr
						</a>
					</li>
					<li>
						<a href="tel:+33600000000" style="display:flex; align-items:flex-start; gap: var(--space-sm);">
							<svg style="margin-top:2px; flex-shrink:0;" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.35 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 5.53 5.53l1.62-1.62a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
							</svg>
							+33 6 00 00 00 00
						</a>
					</li>
					<li>
						<span style="display:flex; align-items:flex-start; gap: var(--space-sm); color: var(--color-cream-dark); opacity:0.75; font-size: var(--text-sm);">
							<svg style="margin-top:2px; flex-shrink:0;" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
								<circle cx="12" cy="10" r="3"/>
							</svg>
							Fécamp, Normandie, France
						</span>
					</li>
				</ul>

				<div style="margin-top: var(--space-xl);">
					<p class="footer__heading">Horaires</p>
					<p style="font-size: var(--text-sm); opacity: 0.75; line-height: 1.8;">
						Lun – Sam : 9h – 18h<br>
						Dimanche : Fermé
					</p>
				</div>
			</div>

		</div>

		<!-- Barre du bas -->
		<div class="footer__bottom">
			<span>© {{ date('Y') }} La Maison des Meringues — Tous droits réservés</span>
			<div style="display:flex; gap: var(--space-xl);">
				<a href="#" style="color: inherit;">Mentions légales</a>
				<a href="#" style="color: inherit;">Politique de confidentialité</a>
				<a href="#" style="color: inherit;">CGV</a>
			</div>
		</div>

	</div>
</footer>
