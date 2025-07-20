<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration Membres Ultra-Professionnelle Laravel 11
     * Conforme aux standards de production enterprise
     */
    public function up(): void
    {
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            
            // Relations et clés étrangères
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Données personnelles obligatoires
            $table->string('prenom');
            $table->string('nom');
            $table->date('date_naissance');
            $table->enum('sexe', ['M', 'F', 'Autre']);
            
            // Coordonnées
            $table->string('telephone', 20)->nullable();
            $table->text('adresse')->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('code_postal', 10)->nullable();
            
            // Contact d'urgence obligatoire (Sécurité arts martiaux)
            $table->string('contact_urgence_nom');
            $table->string('contact_urgence_telephone', 20);
            $table->string('contact_urgence_relation', 50)->nullable();
            
            // Statut et progression martial
            $table->enum('statut', ['actif', 'inactif', 'suspendu', 'diplome'])->default('actif');
            $table->foreignId('ceinture_actuelle_id')->nullable()->constrained('ceintures')->onDelete('set null');
            $table->date('date_inscription');
            $table->timestamp('date_derniere_presence')->nullable();
            $table->date('date_derniere_progression')->nullable();
            
            // Santé et sécurité (Obligatoire assurance)
            $table->text('notes_medicales')->nullable();
            $table->json('allergies')->nullable();
            $table->text('conditions_medicales')->nullable();
            
            // Consentements Loi 25 Québec (RGPD compliance)
            $table->boolean('consentement_photos')->default(false);
            $table->boolean('consentement_communications')->default(true);
            $table->boolean('consentement_donnees')->default(true);
            $table->timestamp('date_consentements')->nullable();
            
            // Notes pédagogiques et administratives
            $table->text('notes_instructeur')->nullable();
            $table->text('notes_admin')->nullable();
            
            // Audit trail et soft delete
            $table->timestamps();
            $table->softDeletes();
            
            // Index optimisés pour performance
            $table->index(['statut', 'date_derniere_presence'], 'idx_statut_presence');
            $table->index('date_inscription', 'idx_inscription');
            $table->index(['nom', 'prenom'], 'idx_nom_prenom');
            $table->index('telephone', 'idx_telephone');
            $table->index(['ceinture_actuelle_id', 'statut'], 'idx_ceinture_statut');
            $table->index('created_at', 'idx_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membres');
    }
};
