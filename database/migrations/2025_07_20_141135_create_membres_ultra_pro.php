<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Données personnelles
            $table->string('prenom');
            $table->string('nom');
            $table->date('date_naissance');
            $table->enum('sexe', ['M', 'F', 'Autre']);
            $table->string('telephone', 20)->nullable();
            
            // Adresse
            $table->text('adresse')->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('code_postal', 10)->nullable();
            
            // Contact d'urgence
            $table->string('contact_urgence_nom');
            $table->string('contact_urgence_telephone', 20);
            $table->string('contact_urgence_relation', 50)->nullable();
            
            // Statut et progression
            $table->enum('statut', ['actif', 'inactif', 'suspendu', 'diplome'])->default('actif');
            $table->foreignId('ceinture_actuelle_id')->nullable()->constrained('ceintures')->onDelete('set null');
            $table->date('date_inscription');
            $table->timestamp('date_derniere_presence')->nullable();
            $table->date('date_derniere_progression')->nullable();
            
            // Santé
            $table->text('notes_medicales')->nullable();
            $table->json('allergies')->nullable();
            $table->text('conditions_medicales')->nullable();
            
            // Consentements Loi 25
            $table->boolean('consentement_photos')->default(false);
            $table->boolean('consentement_communications')->default(true);
            $table->boolean('consentement_donnees')->default(true);
            $table->timestamp('date_consentements')->nullable();
            
            // Notes
            $table->text('notes_instructeur')->nullable();
            $table->text('notes_admin')->nullable();
            
            // Audit
            $table->timestamps();
            $table->softDeletes();
            
            // Index performance
            $table->index(['statut', 'date_derniere_presence']);
            $table->index('date_inscription');
            $table->index(['nom', 'prenom']);
            $table->index('telephone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membres');
    }
};
