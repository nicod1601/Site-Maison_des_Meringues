<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table panier
        Schema::create('panier', function (Blueprint $table) {
            $table->increments('id_panier');
            $table->unsignedBigInteger('user_id')->nullable(); // null = visiteur anonyme
            $table->string('session_id')->nullable();          // pour les visiteurs
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Lignes du panier
        Schema::create('panier_ligne', function (Blueprint $table) {
            $table->increments('id_ligne');
            $table->unsignedInteger('id_panier');
            $table->unsignedInteger('id_produit');
            $table->unsignedInteger('id_forme_condi');
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 8, 2);
            $table->timestamps();

            $table->foreign('id_panier')->references('id_panier')->on('panier')->onDelete('cascade');
            $table->foreign('id_produit')->references('id_produit')->on('produit')->onDelete('cascade');
            $table->foreign('id_forme_condi')->references('id_forme_condi')->on('forme_condi')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panier_ligne');
        Schema::dropIfExists('panier');
    }
};
