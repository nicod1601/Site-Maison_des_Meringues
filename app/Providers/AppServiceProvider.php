<?php

namespace App\Providers;

use App\Mail\WelcomeMail;
use App\Models\Panier;
use App\Listeners\TransfererPanierApresLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // ── Gate admin ← CORRECTION : utilise 'role' au lieu de 'is_admin'
        Gate::define('access-admin', function ($user) {
            return $user->role === 'admin';
        });

        // ── Transfert panier après login
        Event::listen(Login::class, TransfererPanierApresLogin::class);

        // ── Mail de bienvenue à l'inscription
        Event::listen(function (Registered $event) {
            Mail::to($event->user->email)
                ->send(new WelcomeMail($event->user->name));
        });

        // ── Injecter le panier dans toutes les vues
        // ← CORRECTION : suppression du 'static' qui bloquait le rafraîchissement après login
        View::composer('*', function ($view) {
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

            $view->with('panier', $panier);
        });
    }
}
