<x-auth-layout title="Vérification de l'e-mail" heading="Vérifiez votre e-mail"
               subtitle="Merci pour votre inscription ! Cliquez sur le lien que nous venons de vous envoyer par e-mail. Vous ne l'avez pas reçu ? Nous pouvons vous en renvoyer un.">

  @if(session('status') === 'verification-link-sent')
    <div class="alert alert--success" role="status">
      <i class="ti ti-circle-check" aria-hidden="true"></i>
      <span>Un nouveau lien de vérification a été envoyé à l'adresse indiquée lors de l'inscription.</span>
    </div>
  @endif

  <form method="POST" action="{{ route('verification.send') }}" class="auth-form">
    @csrf
    <button type="submit" class="btn-submit">
      <i class="ti ti-mail-check" aria-hidden="true"></i>
      <span>Renvoyer l'e-mail de vérification</span>
    </button>
  </form>

  <form method="POST" action="{{ route('logout') }}" class="logout-form">
    @csrf
    <button type="submit" class="link-btn">
      <i class="ti ti-logout" aria-hidden="true"></i> Se déconnecter
    </button>
  </form>

</x-auth-layout>
