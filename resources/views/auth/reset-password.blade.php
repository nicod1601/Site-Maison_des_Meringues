<x-auth-layout title="Nouveau mot de passe" heading="Nouveau mot de passe"
               subtitle="Choisissez un mot de passe sécurisé pour votre compte." :back="true">

  <form method="POST" action="{{ route('password.store') }}" class="auth-form" novalidate>
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <x-auth-field name="email" type="email" label="Adresse e-mail" icon="ti-mail"
                  :value="$request->email" inputmode="email"
                  autocomplete="username" autocapitalize="none" spellcheck="false" required />

    <x-auth-field name="password" type="password" label="Nouveau mot de passe" icon="ti-lock"
                  placeholder="8 caractères minimum" autocomplete="new-password"
                  minlength="8" data-strength="password-strength" required autofocus>
      <div class="strength" id="password-strength" aria-live="polite" hidden>
        <div class="strength__bar"><span></span></div>
        <small class="strength__label"></small>
      </div>
    </x-auth-field>

    <x-auth-field name="password_confirmation" type="password" label="Confirmer le mot de passe"
                  icon="ti-lock-check" placeholder="Répétez votre mot de passe"
                  autocomplete="new-password" data-match="password" required />

    <button type="submit" class="btn-submit">
      <i class="ti ti-key" aria-hidden="true"></i>
      <span>Réinitialiser le mot de passe</span>
    </button>
  </form>

</x-auth-layout>
