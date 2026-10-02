<x-auth-layout title="Inscription" tab="register" heading="Créer un compte" subtitle="Rejoignez La Maison des Meringues.">

  <form method="POST" action="{{ route('register') }}" class="auth-form" novalidate>
    @csrf

    <x-auth-field name="name" label="Nom complet" icon="ti-user"
                  placeholder="Marie Dupont" autocomplete="name" required autofocus />

    <x-auth-field name="email" type="email" label="Adresse e-mail" icon="ti-mail"
                  placeholder="vous@exemple.com" inputmode="email"
                  autocomplete="username" autocapitalize="none" spellcheck="false" required />

    <x-auth-field name="password" type="password" label="Mot de passe" icon="ti-lock"
                  placeholder="8 caractères minimum" autocomplete="new-password"
                  minlength="8" data-strength="password-strength" required>
      <div class="strength" id="password-strength" aria-live="polite" hidden>
        <div class="strength__bar"><span></span></div>
        <small class="strength__label"></small>
      </div>
    </x-auth-field>

    <x-auth-field name="password_confirmation" type="password" label="Confirmer le mot de passe"
                  icon="ti-lock-check" placeholder="Répétez votre mot de passe"
                  autocomplete="new-password" data-match="password" required />

    <button type="submit" class="btn-submit">
      <i class="ti ti-user-plus" aria-hidden="true"></i>
      <span>Créer mon compte</span>
    </button>

    <p class="terms">
      En créant un compte, vous acceptez nos
      <a href="#">Conditions d'utilisation</a> et notre
      <a href="#">Politique de confidentialité</a>.
    </p>
  </form>

</x-auth-layout>
