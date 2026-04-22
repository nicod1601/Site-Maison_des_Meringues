<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('forme', function (Blueprint $table) {
			$table->id('id_forme');
			$table->string('nom_forme');
		});

		Schema::create('conditionnement', function (Blueprint $table) {
			$table->id('id_condi');
			$table->string('type');
		});

		Schema::create('parfum', function (Blueprint $table) {
			$table->id('id_parfum');
			$table->string('nom_parfum');
		});

		Schema::create('forme_condi', function (Blueprint $table) {
			$table->id('id_forme_condi');
			$table->unsignedBigInteger('id_forme');
			$table->unsignedBigInteger('id_condi');
			$table->decimal('prix', 8, 2);

			$table->foreign('id_forme')->references('id_forme')->on('forme')->onDelete('cascade');
			$table->foreign('id_condi')->references('id_condi')->on('conditionnement')->onDelete('cascade');
		});

		Schema::create('boutique', function (Blueprint $table) {
			$table->id('id_boutique');
			$table->string('nom_boutique');
			$table->unsignedInteger('stock_total')->default(0);
		});

		Schema::create('theme', function (Blueprint $table) {
			$table->id('id_theme');
			$table->string('nom_theme');
			$table->string('icone')->default('🎨');
			$table->string('couleur')->default('#C0395A');
		});

		Schema::create('rayon', function (Blueprint $table) {
			$table->id('id_rayon');
			$table->string('nom_rayon');
			$table->unsignedBigInteger('id_boutique');
			$table->unsignedInteger('stock_total_rayon')->default(0);

			$table->foreign('id_boutique')->references('id_boutique')->on('boutique')->onDelete('cascade');
		});

		Schema::create('rayon_theme', function (Blueprint $table) {
			$table->unsignedBigInteger('id_rayon');
			$table->unsignedBigInteger('id_theme');
			$table->primary(['id_rayon', 'id_theme']);

			$table->foreign('id_rayon')->references('id_rayon')->on('rayon')->onDelete('cascade');
			$table->foreign('id_theme')->references('id_theme')->on('theme')->onDelete('cascade');
		});

		Schema::create('produit', function (Blueprint $table) {
			$table->id('id_produit');
			$table->unsignedBigInteger('id_forme_condi');
			$table->unsignedBigInteger('id_parfum');
			$table->unsignedBigInteger('id_rayon')->nullable();

			$table->unsignedBigInteger('id_theme')->nullable()->after('id_rayon');
			$table->foreign('id_theme')
				->references('id_theme')->on('theme')
				->onDelete('set null');

			$table->string('description');
			$table->unsignedInteger('quantite')->default(0);
			$table->boolean('nouveaute')->default(false);
			$table->boolean('live')->default(false);
			$table->boolean('dispo_emporter')->default(false);
			$table->boolean('dispo_expedition')->default(false);

			$table->foreign('id_forme_condi')->references('id_forme_condi')->on('forme_condi')->onDelete('restrict');
			$table->foreign('id_parfum')->references('id_parfum')->on('parfum')->onDelete('restrict');
		});

        Schema::create('produit_rayon', function (Blueprint $table) {
            $table->unsignedBigInteger('id_produit');
            $table->unsignedBigInteger('id_rayon');
            $table->primary(['id_produit', 'id_rayon']);

            $table->foreign('id_produit')
                ->references('id_produit')->on('produit')
                ->onDelete('cascade');
            $table->foreign('id_rayon')
                ->references('id_rayon')->on('rayon')
                ->onDelete('cascade');
        });
	}

	public function down(): void
	{
		Schema::dropIfExists('produit');
         Schema::dropIfExists('rayon_theme');
		Schema::dropIfExists('rayon');
		Schema::dropIfExists('theme');
		Schema::dropIfExists('boutique');
		Schema::dropIfExists('forme_condi');
		Schema::dropIfExists('parfum');
		Schema::dropIfExists('conditionnement');
		Schema::dropIfExists('forme');
	}
};
