<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('commande', function (Blueprint $table) {
			$table->increments('id_commande');
			$table->unsignedBigInteger('user_id')->nullable();
			$table->string('reference')->unique();
			$table->enum('mode_livraison', ['livraison', 'expedition']);
			$table->enum('statut', ['en_attente','payee','expediee','terminee','emportee','annulee'])->default('en_attente');
			$table->decimal('montant', 8, 2);
			$table->string('monetico_reference')->nullable();
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
		});

		Schema::create('commande_ligne', function (Blueprint $table) {
			$table->id('id_ligne');
			$table->foreignId('id_commande')->constrained('commande', 'id_commande')->cascadeOnDelete();
			$table->string('designation');
			$table->string('sous_designation')->nullable();
			$table->unsignedInteger('quantite');
			$table->decimal('prix_unitaire', 8, 2);
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('commande_ligne');
		Schema::dropIfExists('commande');
	}
};
