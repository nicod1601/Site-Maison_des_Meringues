<?php

use Illuminate\Foundation\Inspiring;
use App\Models\PendingCheckout;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Paiements démarrés mais jamais finalisés : purge après 7 jours
// (nécessite le cron Laravel : * * * * * php artisan schedule:run)
Schedule::call(fn () => PendingCheckout::where('created_at', '<', now()->subDays(7))->delete())
    ->daily()
    ->name('purge-pending-checkouts');
