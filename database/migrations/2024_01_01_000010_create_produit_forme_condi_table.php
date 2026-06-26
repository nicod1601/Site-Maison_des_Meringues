<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produit_forme_condi', function (Blueprint $table) {
            $table->increments('id_produit_forme_condi');
            $table->unsignedInteger('id_produit');
            $table->unsignedInteger('id_forme_condi');
            $table->integer('quantite')->default(0);

            $table->foreign('id_produit')->references('id_produit')->on('produit')->onDelete('cascade');
            $table->foreign('id_forme_condi')->references('id_forme_condi')->on('forme_condi')->onDelete('cascade');

            $table->unique(['id_produit', 'id_forme_condi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produit_forme_condi');
    }
};