<x-auth-layout title="Connexion" tab="login" heading="Bon retour !" subtitle="Connectez-vous à votre espace client.">

  <form method="POST" action="{{ route('login') }}" class="auth-form" novalidate>
    @csrf

    <x-auth-field name="email" type="email" label="Adresse e-mail" icon="ti-mail"
                  placeholder="vous@exemple.com" inputmode="email"
                  autocomplete="username" autocapitalize="none" spellcheck="false"
                  required autofocus />

    <x-auth-field name="password" type="password" label="Mot de passe" icon="ti-lock"
                  placeholder="••••••••" autocomplete="current-password" required />

    <div class="form-row">
      <label class="check" for="remember_me">
        <input type="checkbox" id="remember_me" name="remember" @checked(old('remember'))>
        <span>Se souvenir de moi</span>
      </label>

      @if(Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="forgot">Mot de passe oublié ?</a>
      @endif
    </div>

    <button type="submit" class="btn-submit">
      <i class="ti ti-login" aria-hidden="true"></i>
      <span>Se connecter</span>
    </button>
  </form>

</x-auth-layout>
