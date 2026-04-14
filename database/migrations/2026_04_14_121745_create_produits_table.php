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
			$table->unsignedBigInteger('id_forme');
			$table->unsignedBigInteger('id_condi');
			$table->decimal('prix', 8, 2);

			$table->primary(['id_forme', 'id_condi']);

			$table->foreign('id_forme')
				->references('id_forme')->on('forme')
				->onDelete('cascade');

			$table->foreign('id_condi')
				->references('id_condi')->on('conditionnement')
				->onDelete('cascade');
		});

		Schema::create('produit', function (Blueprint $table) {
			$table->id('id_produit');
			$table->unsignedBigInteger('id_forme');
			$table->unsignedBigInteger('id_parfum');

			$table->string('description');
			$table->unsignedInteger('quantite')->default(0);
			$table->boolean('nouveaute')->default(false);
			$table->boolean('live')->default(false);

			$table->foreign('id_forme')
				->references('id_forme')->on('forme')
				->onDelete('restrict');

			$table->foreign('id_parfum')
				->references('id_parfum')->on('parfum')
				->onDelete('restrict');
		});

		Schema::create('boutique', function (Blueprint $table) {

			$table->unsignedBigInteger('id_produit');

			$table->boolean('dispo_emporter')->default(true);
			$table->boolean('dispo_expedition')->default(false);
			$table->unsignedInteger('stock_total')->default(0);

			$table->primary('id_produit');

			$table->foreign('id_produit')
				->references('id_produit')->on('produit')
				->onDelete('cascade');
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('boutique');
		Schema::dropIfExists('produit');
		Schema::dropIfExists('forme_condi');
		Schema::dropIfExists('parfum');
		Schema::dropIfExists('conditionnement');
		Schema::dropIfExists('forme');
	}
};
