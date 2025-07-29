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
        Schema::create('liens_familiaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_principal_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('membre_lie_id')->constrained('membres')->onDelete('cascade');
            $table->enum('type_relation', [
                'parent', 'enfant', 'conjoint', 'frere_soeur',
                'grand_parent', 'petit_enfant', 'oncle_tante',
                'neveu_niece', 'cousin', 'autre'
            ]);
            $table->string('famille_id', 50)->nullable(); // Identifiant de famille pour grouper
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index pour les performances
            $table->index(['membre_principal_id', 'type_relation']);
            $table->index(['famille_id']);

            // Éviter les doublons
            $table->unique(['membre_principal_id', 'membre_lie_id', 'type_relation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liens_familiaux');
    }
};
