<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pending_checkouts', function (Blueprint $table) {
            // Photo du panier au moment du paiement : ce qui est payé = ce qui est commandé,
            // même si le client modifie son panier pendant qu'il est sur la page de la banque.
            $table->json('lignes')->nullable()->after('mode_livraison');
        });
    }

    public function down(): void
    {
        Schema::table('pending_checkouts', function (Blueprint $table) {
            $table->dropColumn('lignes');
        });
    }
};
