#!/bin/bash

echo "👥 CRÉATION MIGRATION MEMBRES ULTRA-PRO"
echo "======================================"

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Création migration membres
php artisan make:migration create_membres_table

MIGRATION_FILE=$(find database/migrations -name "*_create_membres_table.php" | head -1)

cat > "$MIGRATION_FILE" << 'MIGRATION_MEMBRES'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration ultra-professionnelle système membres karaté
     * StudiosDB v5 Pro - Laravel 12.20
     */
    public function up(): void
    {
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            
            // Relations système
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('numero_membre', 20)->unique(); // SDB-2025-001
            
            // Informations personnelles
            $table->string('prenom', 100);
            $table->string('nom', 100);
            $table->string('nom_complet')->virtualAs("CONCAT(prenom, ' ', nom)");
            $table->date('date_naissance');
            $table->enum('sexe', ['M', 'F', 'Autre'])->default('Autre');
            $table->string('nationalite', 3)->default('CAN'); // Code ISO 3
            
            // Coordonnées complètes
            $table->string('telephone_principal', 20)->nullable();
            $table->string('telephone_secondaire', 20)->nullable();
            $table->string('email_personnel')->nullable();
            
            // Adresse complète
            $table->text('adresse_complete')->nullable();
            $table->string('rue', 255)->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('province', 100)->default('Québec');
            $table->string('code_postal', 10)->nullable();
            $table->string('pays', 100)->default('Canada');
            
            // Contact d'urgence
            $table->string('urgence_nom', 150)->nullable();
            $table->string('urgence_lien', 100)->nullable(); // Parent, Conjoint, etc.
            $table->string('urgence_telephone', 20)->nullable();
            $table->string('urgence_email')->nullable();
            
            // Informations karaté
            $table->foreignId('ceinture_actuelle_id')->nullable()->constrained('ceintures');
            $table->date('date_obtention_ceinture')->nullable();
            $table->string('ecole_precedente')->nullable(); // École précédente si transfert
            $table->unsignedSmallInteger('annees_experience')->default(0);
            
            // Inscription et statut
            $table->date('date_inscription');
            $table->date('date_derniere_presence')->nullable();
            $table->date('date_derniere_connexion')->nullable();
            $table->enum('statut', [
                'actif', 'inactif', 'suspendu', 'exclus', 
                'en_pause', 'diplome', 'transfere'
            ])->default('actif');
            
            $table->text('raison_statut')->nullable(); // Raison changement statut
            $table->date('date_changement_statut')->nullable();
            
            // Informations médicales (Conformité Loi 25)
            $table->boolean('problemes_medicaux')->default(false);
            $table->text('details_medicaux')->nullable(); // Chiffré
            $table->json('allergies')->nullable(); // Liste allergies
            $table->text('medicaments')->nullable(); // Médications actuelles
            $table->string('medecin_nom')->nullable();
            $table->string('medecin_telephone')->nullable();
            
            // Assurances et légal
            $table->string('numero_assurance_maladie')->nullable(); // RAMQ
            $table->date('expiration_assurance')->nullable();
            $table->boolean('assurance_complementaire')->default(false);
            $table->string('compagnie_assurance')->nullable();
            
            // Consentements et confidentialité (Loi 25)
            $table->boolean('consentement_photos')->default(false);
            $table->boolean('consentement_videos')->default(false);
            $table->boolean('consentement_communications')->default(true);
            $table->boolean('consentement_donnees_medicales')->default(false);
            $table->boolean('consentement_partage_progres')->default(true);
            $table->datetime('date_consentements')->nullable();
            
            // Préférences et objectifs
            $table->json('objectifs_personnels')->nullable(); // Compétition, santé, etc.
            $table->json('interets_particuliers')->nullable(); // Kata, combat, self-defense
            $table->enum('niveau_engagement', ['occasionnel', 'regulier', 'intensif', 'competition'])
                  ->default('regulier');
            
            // Informations famille (pour mineurs)
            $table->boolean('est_mineur')->virtualAs('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) < 18');
            $table->string('tuteur_legal_nom')->nullable();
            $table->string('tuteur_legal_telephone')->nullable();
            $table->enum('autorisation_sortie', ['aucune', 'accompagne', 'autonome'])
                  ->default('aucune');
            
            // Données comportementales et progression
            $table->json('traits_caracteristiques')->nullable(); // Timide, leader, etc.
            $table->text('notes_comportementales')->nullable();
            $table->unsignedSmallInteger('niveau_motivation')->default(5); // 1-10
            $table->json('recompenses_obtenues')->nullable(); // Trophées, mentions
            
            // Méta-données et analytics
            $table->json('preferences_systeme')->nullable(); // Langue, notifications
            $table->json('donnees_analytics')->nullable(); // Patterns présences, etc.
            $table->timestamp('derniere_mise_a_jour_profil')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // Suppression douce pour conformité RGPD
            
            // Index ultra-optimisés
            $table->index(['statut', 'date_derniere_presence']);
            $table->index(['ceinture_actuelle_id', 'statut']);
            $table->index(['date_inscription', 'statut']);
            $table->index(['nom', 'prenom']);
            $table->index('numero_membre');
            $table->index('est_mineur');
            $table->fullText(['nom', 'prenom', 'numero_membre']); // Recherche full-text
        });
        
        // Table historique modifications membres
        Schema::create('membres_historique', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_modificateur_id')->constrained('users');
            
            $table->string('type_modification', 50); // statut, ceinture, coordonnees
            $table->json('anciennes_valeurs'); // État avant modification
            $table->json('nouvelles_valeurs'); // État après modification
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
MIGRATION_MEMBRES

echo "✅ Migration membres ultra-professionnelle créée"
echo "Fichier: $MIGRATION_FILE"
