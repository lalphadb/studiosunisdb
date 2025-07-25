<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ceintures', function (Blueprint $table) {
            $table->id();
            
            // Informations ceinture
            $table->string('nom', 50);
            $table->string('nom_en', 50)->nullable();
            $table->char('couleur_hex', 7)->default('#000000');
            $table->char('couleur_secondaire_hex', 7)->nullable();
            
            // Système hiérarchique
            $table->unsignedTinyInteger('ordre')->unique();
            $table->unsignedTinyInteger('niveau_difficulte')->default(1);
            $table->boolean('est_dan')->default(false);
            $table->unsignedTinyInteger('dan_niveau')->nullable();
            
            // Pré-requis
            $table->json('techniques_requises')->nullable();
            $table->json('connaissances_theoriques')->nullable();
            $table->unsignedSmallInteger('duree_minimum_mois')->default(6);
            $table->unsignedSmallInteger('presences_minimum')->default(48);
            $table->unsignedTinyInteger('age_minimum')->default(6);
            
            // Critères évaluation
            $table->unsignedTinyInteger('seuil_technique')->default(70);
            $table->unsignedTinyInteger('seuil_physique')->default(60);
            $table->unsignedTinyInteger('seuil_mental')->default(50);
            $table->unsignedTinyInteger('seuil_global')->default(65);
            
            // Informations admin
            $table->decimal('cout_examen', 8, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->text('objectifs_pedagogiques')->nullable();
            $table->string('certificat_template')->nullable();
            
            // Méta-données
            $table->boolean('active')->default(true);
            $table->boolean('visible_membres')->default(true);
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index(['ordre', 'active']);
            $table->index(['est_dan', 'dan_niveau']);
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ceintures');
    }
};
