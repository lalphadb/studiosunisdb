<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer la table si elle existe
        Schema::dropIfExists('membres');

        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('prenom');
            $table->string('nom');
            $table->date('date_naissance');
            $table->enum('sexe', ['M', 'F', 'Autre']);
            $table->string('telephone')->nullable();
            $table->text('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('code_postal', 10)->nullable();
            $table->string('contact_urgence_nom');
            $table->string('contact_urgence_telephone');
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            $table->foreignId('ceinture_actuelle_id')->nullable()->constrained('ceintures')->onDelete('set null');
            $table->date('date_inscription');
            $table->date('date_derniere_presence')->nullable();
            $table->text('notes_medicales')->nullable();
            $table->json('allergies')->nullable();
            $table->boolean('consentement_photos')->default(false);
            $table->boolean('consentement_communications')->default(true);
            $table->boolean('consentement_donnees')->default(false);
            $table->text('notes_instructeur')->nullable();
            $table->timestamps();
            
            // Index pour performance
            $table->index(['statut', 'date_derniere_presence']);
            $table->index('date_inscription');
            $table->index(['ceinture_actuelle_id', 'statut']);
            $table->index(['prenom', 'nom']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membres');
        
        // Recréer l'ancienne structure simple
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
