<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->foreignId('instructeur_id')->constrained('users');
            $table->enum('niveau', ['debutant', 'intermediaire', 'avance', 'competition']);
            $table->integer('age_min')->default(5);
            $table->integer('age_max')->default(99);
            $table->integer('places_max')->default(20);
            $table->enum('jour_semaine', ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->decimal('tarif_mensuel', 8, 2);
            $table->string('salle')->nullable();
            $table->string('couleur_calendrier')->default('#3B82F6');
            $table->boolean('actif')->default(true);
            $table->boolean('inscription_ouverte')->default(true);
            $table->json('prerequis')->nullable();
            $table->timestamps();
            
            // Index pour les requêtes de planning
            $table->index(['jour_semaine', 'heure_debut']);
            $table->index(['instructeur_id', 'actif']);
            $table->index('actif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
