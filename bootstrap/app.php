<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => App\Http\Middleware\IsAdmin::class,
        ]);

        // Callback serveur-à-serveur de Monetico : pas de jeton CSRF possible.
        // La sécurité repose sur la vérification du sceau (MAC) dans CommandeController@retour.
        $middleware->validateCsrfTokens(except: [
            'checkout/retour',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
