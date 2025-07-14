<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Créer table cours d'abord
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            
            // Informations de base
            $table->string('nom');
            $table->text('description')->nullable();
            
            // Niveau RÉEL pour arts martiaux
            $table->enum('niveau', [
                'tous', 
                'debutant', 
                'avance', 
                'prive', 
                'a_la_carte', 
                'combat', 
                'autres'
            ])->default('tous');
            
            $table->string('type_cours')->default('regulier');
            
            // Contraintes
            $table->integer('age_minimum')->nullable();
            $table->integer('capacite_max')->default(20);
            
            // HORAIRES INTÉGRÉS
            $table->json('jours_semaine'); // [1,4] pour Lundi+Jeudi
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->integer('duree_minutes')->default(60);
            $table->string('salle')->nullable();
            
            // TARIFICATION RÉELLE
            $table->enum('mode_paiement', [
                'quotidien',
                'mensuel', 
                'trimestriel',
                'autre'
            ])->default('mensuel');
            
            $table->decimal('prix', 8, 2);
            $table->string('devise', 3)->default('CAD');
            
            // Sessions
            $table->enum('saison', [
                'automne',
                'hiver', 
                'printemps',
                'ete'
            ])->nullable();
            
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            
            // Options
            $table->boolean('actif')->default(true);
            $table->boolean('inscription_ouverte')->default(true);
            $table->string('couleur', 7)->default('#3B82F6');
            
            // Auto-référence (sans FK pour l'instant)
            $table->unsignedBigInteger('cours_parent_id')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index(['ecole_id', 'actif']);
            $table->index(['saison']);
            $table->index(['niveau']);
        });

        // 2. Ajouter FK auto-référentielle après création table
        Schema::table('cours', function (Blueprint $table) {
            $table->foreign('cours_parent_id')->references('id')->on('cours')->onDelete('set null');
        });

        // 3. Créer sessions_cours
        Schema::create('sessions_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('salle')->nullable();
            $table->foreignId('instructeur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('statut', [
                'programmee',
                'en_cours', 
                'terminee',
                'annulee',
                'reportee'
            ])->default('programmee');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['cours_id', 'date']);
            $table->index(['date', 'statut']);
        });

        // 4. Créer inscriptions_cours
        Schema::create('inscriptions_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->date('date_inscription');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->enum('statut', ['active', 'suspendue', 'terminee'])->default('active');
            $table->enum('type_paiement', ['mensuel', 'seance', 'carte'])->default('mensuel');
            $table->decimal('tarif_applique', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'cours_id']);
            $table->index(['cours_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions_cours');
        Schema::dropIfExists('sessions_cours');
        Schema::dropIfExists('cours');
    }
};
