<x-auth-layout title="Mot de passe oublié" heading="Mot de passe oublié ?"
               subtitle="Indiquez votre adresse e-mail, nous vous enverrons un lien pour en choisir un nouveau." :back="true">

  <form method="POST" action="{{ route('password.email') }}" class="auth-form" novalidate>
    @csrf

    <x-auth-field name="email" type="email" label="Adresse e-mail" icon="ti-mail"
                  placeholder="vous@exemple.com" inputmode="email"
                  autocomplete="username" autocapitalize="none" spellcheck="false" required autofocus />

    <button type="submit" class="btn-submit">
      <i class="ti ti-send" aria-hidden="true"></i>
      <span>Envoyer le lien</span>
    </button>
  </form>

</x-auth-layout>
