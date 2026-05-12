<?php

namespace App\Providers;

use App\Models\Panier;
use App\Listeners\TransfererPanierApresLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	public function register(): void {}

	public function boot(): void
	{
		// Gate admin
		Gate::define('access-admin', function ($user) {
			return $user->is_admin === true;
		});

		Event::listen(Login::class, TransfererPanierApresLogin::class);

		View::composer('*', function ($view) {
			static $panier = null;

			if ($panier === null) {
				if (auth()->check()) {
					$panier = Panier::with('lignes.produit', 'lignes.formeCondi')
						->where('user_id', auth()->id())
						->first()
						?? Panier::create(['user_id' => auth()->id()]);
				} else {
					$sessionId = session()->getId();
					$panier = Panier::with('lignes.produit', 'lignes.formeCondi')
						->where('session_id', $sessionId)
						->first()
						?? Panier::create(['session_id' => $sessionId]);
				}
			}

			$view->with('panier', $panier);
		});
	}
}
