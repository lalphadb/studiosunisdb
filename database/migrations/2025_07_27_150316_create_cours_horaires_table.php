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
        Schema::create('cours_horaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->enum('jour', ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('salle', 100)->nullable();
            $table->foreignId('instructeur_id')->nullable()->constrained('membres')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index pour les performances
            $table->index(['cours_id', 'jour']);
            $table->index(['instructeur_id']);
            
            // Contrainte pour éviter les chevauchements
            $table->unique(['cours_id', 'jour', 'heure_debut'], 'unique_cours_jour_heure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours_horaires');
    }
};
