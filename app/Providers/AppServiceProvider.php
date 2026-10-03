<?php

namespace App\Providers;

use App\Mail\WelcomeMail;
use App\Listeners\TransfererPanierApresLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // ── Transfert panier après login
        Event::listen(Login::class, TransfererPanierApresLogin::class);

        // ── Mail de bienvenue à l'inscription
        // Envoi direct (timeout SMTP de 10 s dans config/mail.php). Chaque étape est écrite dans
        // storage/logs/laravel.log, et un échec n'empêche jamais l'inscription.
        Event::listen(function (Registered $event) {
            $email = $event->user->email;

            Log::info("Inscription de {$email} : envoi du mail de bienvenue…");

            try {
                Mail::to($email)->send(new WelcomeMail($event->user->name));
                Log::info("Mail de bienvenue envoyé à {$email}");
            } catch (\Throwable $e) {
                Log::error("Mail de bienvenue en échec pour {$email} : " . $e->getMessage());
            }
        });
    }
}