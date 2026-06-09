<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
            Schema::create('depenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('recu_id')->constrained('recus')->cascadeOnDelete();
                $table->string('libelle');
                $table->unsignedInteger('quantite');
                $table->decimal('prix_unitaire', 10, 2);
                $table->enum('categorie', ['alimentaire', 'boissons', 'hygiene', 'entretien', 'autre']);
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depenses');
    }
};
