<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		// ─── Forme ───────────────────────────────────────────────────────────
		Schema::create('forme', function (Blueprint $table) {
			$table->increments('id_forme');
			$table->string('nom_forme');
		});

		// ─── Conditionnement ─────────────────────────────────────────────────
		Schema::create('conditionnement', function (Blueprint $table) {
			$table->increments('id_condi');
			$table->string('type');
		});

		// ─── Forme_Condi (pivot avec données : prix) ──────────────────────────
		Schema::create('forme_condi', function (Blueprint $table) {
			$table->increments('id_forme_condi');
			$table->unsignedInteger('id_forme');
			$table->unsignedInteger('id_condi');
			$table->decimal('prix', 8, 2);

			$table->foreign('id_forme')->references('id_forme')->on('forme')->onDelete('cascade');
			$table->foreign('id_condi')->references('id_condi')->on('conditionnement')->onDelete('cascade');

			// Une combinaison forme + conditionnement est unique
			$table->unique(['id_forme', 'id_condi']);
		});

		// ─── Parfum ───────────────────────────────────────────────────────────
		Schema::create('parfum', function (Blueprint $table) {
			$table->increments('id_parfum');
			$table->string('nom_parfum');
		});

		// ─── Thème (indicateur de tri pour les produits) ──────────────────────
		Schema::create('theme', function (Blueprint $table) {
			$table->increments('id_theme');
			$table->string('nom_theme');
			$table->string('icone')->nullable();
			$table->string('couleur')->nullable();
		});

		// ─── Event (indicateur d'occasion : Noël, Printemps, Anniversaire…) ──
		Schema::create('event', function (Blueprint $table) {
			$table->increments('id_event');
			$table->string('nom_event');
			$table->string('icone')->nullable();
			$table->string('couleur')->nullable();
		});

		// ─── Boutique ─────────────────────────────────────────────────────────
		Schema::create('boutique', function (Blueprint $table) {
			$table->increments('id_boutique');
			$table->string('nom_boutique');
			$table->integer('stock_total')->default(0);
		});

		// ─── Rayon ────────────────────────────────────────────────────────────
		Schema::create('rayon', function (Blueprint $table) {
			$table->increments('id_rayon');
			$table->string('nom_rayon');
			$table->unsignedInteger('id_boutique');
			$table->integer('stock_total_rayon')->default(0);
			$table->boolean('live_rayon')->default(false);

			$table->foreign('id_boutique')->references('id_boutique')->on('boutique')->onDelete('cascade');
		});

		// ─── Produit ──────────────────────────────────────────────────────────
		Schema::create('produit', function (Blueprint $table) {
			$table->increments('id_produit');
			$table->string('nom_produit');
			$table->unsignedInteger('id_forme');
			$table->unsignedInteger('id_parfum');
			$table->unsignedInteger('id_theme')->nullable();
			$table->text('description')->nullable();
			$table->integer('quantite')->default(0);
			$table->boolean('nouveaute')->default(false);
			$table->boolean('live')->default(false);
			$table->boolean('dispo_emporter')->default(true);
			$table->boolean('dispo_expedition')->default(true);
			$table->boolean(('special'))->default(false);
			$table->timestamp('nouveaute_since')->nullable()->after('nouveaute');

			$table->foreign('id_forme')->references('id_forme')->on('forme')->onDelete('restrict');
			$table->foreign('id_parfum')->references('id_parfum')->on('parfum')->onDelete('restrict');
			$table->foreign('id_theme')->references('id_theme')->on('theme')->onDelete('set null');
		});

		// ─── Pivot : produit ↔ rayon ──────────────────────────────────────────
		Schema::create('produit_rayon', function (Blueprint $table) {
			$table->unsignedInteger('id_produit');
			$table->unsignedInteger('id_rayon');

			$table->primary(['id_produit', 'id_rayon']);
			$table->foreign('id_produit')->references('id_produit')->on('produit')->onDelete('cascade');
			$table->foreign('id_rayon')->references('id_rayon')->on('rayon')->onDelete('cascade');
		});

		// ─── Pivot : rayon ↔ event ────────────────────────────────────────────
		Schema::create('rayon_event', function (Blueprint $table) {
			$table->unsignedInteger('id_rayon');
			$table->unsignedInteger('id_event');

			$table->primary(['id_rayon', 'id_event']);
			$table->foreign('id_rayon')->references('id_rayon')->on('rayon')->onDelete('cascade');
			$table->foreign('id_event')->references('id_event')->on('event')->onDelete('cascade');
		});

		// ─── Pivot : produit ↔ event ──────────────────────────────────────────
		Schema::create('produit_event', function (Blueprint $table) {
			$table->unsignedInteger('id_produit');
			$table->unsignedInteger('id_event');

			$table->primary(['id_produit', 'id_event']);
			$table->foreign('id_produit')->references('id_produit')->on('produit')->onDelete('cascade');
			$table->foreign('id_event')->references('id_event')->on('event')->onDelete('cascade');
		});

		//Images
		Schema::create('image', function (Blueprint $table) {
			$table->increments('id_image');
			$table->unsignedInteger('id_produit');
			$table->unsignedInteger('id_forme_condi');
			$table->string('url');

			$table->foreign('id_produit')->references('id_produit')->on('produit')->onDelete('cascade');
			$table->foreign('id_forme_condi')->references('id_forme_condi')->on('forme_condi')->onDelete('cascade');
		});
	}

	public function down(): void
	{
		// Suppression dans l'ordre inverse des dépendances
		Schema::dropIfExists('image');
		Schema::dropIfExists('produit_event');
		Schema::dropIfExists('rayon_event');
		Schema::dropIfExists('produit_rayon');
		Schema::dropIfExists('produit');
		Schema::dropIfExists('rayon');
		Schema::dropIfExists('boutique');
		Schema::dropIfExists('event');
		Schema::dropIfExists('theme');
		Schema::dropIfExists('parfum');
		Schema::dropIfExists('forme_condi');
		Schema::dropIfExists('conditionnement');
		Schema::dropIfExists('forme');
	}
};
