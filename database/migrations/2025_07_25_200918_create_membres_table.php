<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            
            // Relations système
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('numero_membre', 20)->unique();
            
            // Informations personnelles
            $table->string('prenom', 100);
            $table->string('nom', 100);
            $table->date('date_naissance');
            $table->enum('sexe', ['M', 'F', 'Autre'])->default('Autre');
            $table->char('nationalite', 3)->default('CAN');
            
            // Coordonnées
            $table->string('telephone_principal', 20)->nullable();
            $table->string('telephone_secondaire', 20)->nullable();
            $table->string('email_personnel')->nullable();
            
            // Adresse
            $table->text('adresse_complete')->nullable();
            $table->string('rue')->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('province', 100)->default('Québec');
            $table->string('code_postal', 10)->nullable();
            $table->string('pays', 100)->default('Canada');
            
            // Contact urgence
            $table->string('urgence_nom', 150)->nullable();
            $table->string('urgence_lien', 100)->nullable();
            $table->string('urgence_telephone', 20)->nullable();
            $table->string('urgence_email')->nullable();
            
            // Informations karaté - RÉFÉRENCE APRÈS CRÉATION CEINTURES
            $table->unsignedBigInteger('ceinture_actuelle_id')->nullable();
            $table->date('date_obtention_ceinture')->nullable();
            $table->string('ecole_precedente')->nullable();
            $table->unsignedSmallInteger('annees_experience')->default(0);
            
            // Inscription et statut
            $table->date('date_inscription');
            $table->date('date_derniere_presence')->nullable();
            $table->date('date_derniere_connexion')->nullable();
            $table->enum('statut', [
                'actif', 'inactif', 'suspendu', 'exclus', 
                'en_pause', 'diplome', 'transfere'
            ])->default('actif');
            
            $table->text('raison_statut')->nullable();
            $table->date('date_changement_statut')->nullable();
            
            // Informations médicales
            $table->boolean('problemes_medicaux')->default(false);
            $table->text('details_medicaux')->nullable();
            $table->json('allergies')->nullable();
            $table->text('medicaments')->nullable();
            $table->string('medecin_nom')->nullable();
            $table->string('medecin_telephone')->nullable();
            
            // Assurances
            $table->string('numero_assurance_maladie')->nullable();
            $table->date('expiration_assurance')->nullable();
            $table->boolean('assurance_complementaire')->default(false);
            $table->string('compagnie_assurance')->nullable();
            
            // Consentements Loi 25
            $table->boolean('consentement_photos')->default(false);
            $table->boolean('consentement_videos')->default(false);
            $table->boolean('consentement_communications')->default(true);
            $table->boolean('consentement_donnees_medicales')->default(false);
            $table->boolean('consentement_partage_progres')->default(true);
            $table->datetime('date_consentements')->nullable();
            
            // Préférences
            $table->json('objectifs_personnels')->nullable();
            $table->json('interets_particuliers')->nullable();
            $table->enum('niveau_engagement', [
                'occasionnel', 'regulier', 'intensif', 'competition'
            ])->default('regulier');
            
            // Famille (mineurs)
            $table->string('tuteur_legal_nom')->nullable();
            $table->string('tuteur_legal_telephone')->nullable();
            $table->enum('autorisation_sortie', ['aucune', 'accompagne', 'autonome'])
                  ->default('aucune');
            
            // Progression
            $table->json('traits_caracteristiques')->nullable();
            $table->text('notes_comportementales')->nullable();
            $table->unsignedTinyInteger('niveau_motivation')->default(5);
            $table->json('recompenses_obtenues')->nullable();
            
            // Méta-données
            $table->json('preferences_systeme')->nullable();
            $table->json('donnees_analytics')->nullable();
            $table->timestamp('derniere_mise_a_jour_profil')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index optimisés
            $table->index(['statut', 'date_derniere_presence']);
            $table->index(['ceinture_actuelle_id', 'statut']);
            $table->index(['date_inscription', 'statut']);
            $table->index(['nom', 'prenom']);
            $table->index('numero_membre');
        });
        
        // Ajout foreign key APRÈS création table
        Schema::table('membres', function (Blueprint $table) {
            $table->foreign('ceinture_actuelle_id')->references('id')->on('ceintures')->nullOnDelete();
        });
        
        // Table historique
        Schema::create('membres_historique', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_modificateur_id')->constrained('users');
            
            $table->string('type_modification', 50);
            $table->json('anciennes_valeurs');
            $table->json('nouvelles_valeurs');
            $table->text('raison_modification')->nullable();
            
            $table->timestamp('date_modification');
            
            $table->index(['membre_id', 'date_modification']);
            $table->index('type_modification');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membres_historique');
        Schema::dropIfExists('membres');
    }
};
