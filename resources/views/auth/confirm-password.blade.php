<x-auth-layout title="Confirmation" heading="Zone sécurisée"
               subtitle="Merci de confirmer votre mot de passe avant de continuer.">

  <form method="POST" action="{{ route('password.confirm') }}" class="auth-form" novalidate>
    @csrf

    <x-auth-field name="password" type="password" label="Mot de passe" icon="ti-lock"
                  placeholder="••••••••" autocomplete="current-password" required autofocus />

    <button type="submit" class="btn-submit">
      <i class="ti ti-shield-lock" aria-hidden="true"></i>
      <span>Confirmer</span>
    </button>
  </form>

</x-auth-layout>
