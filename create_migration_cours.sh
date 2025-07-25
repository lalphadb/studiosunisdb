#!/bin/bash

echo "📚 CRÉATION MIGRATION COURS ULTRA-PRO"
echo "===================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

php artisan make:migration create_cours_system_tables

MIGRATION_FILE=$(find database/migrations -name "*_create_cours_system_tables.php" | head -1)

cat > "$MIGRATION_FILE" << 'MIGRATION_COURS'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration ultra-professionnelle système cours karaté
     * StudiosDB v5 Pro - Laravel 12.20
     */
    public function up(): void
    {
        // Table cours principaux
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            
            // Informations de base
            $table->string('nom', 150);
            $table->string('code_cours', 20)->unique(); // KAR-DEB-LUN-19H
            $table->text('description')->nullable();
            $table->text('objectifs_cours')->nullable();
            
            // Instructeurs
            $table->foreignId('instructeur_principal_id')->constrained('users');
            $table->foreignId('instructeur_assistant_id')->nullable()->constrained('users');
            $table->json('instructeurs_suppleants')->nullable(); // IDs instructeurs remplaçants
            
            // Classification et niveau
            $table->enum('type_cours', [
                'regulier', 'intensif', 'prive', 'stage', 
                'competition', 'demonstration', 'rattrapage'
            ])->default('regulier');
            
            $table->enum('niveau', [
                'initiation', 'debutant', 'debutant_avance',
                'intermediaire', 'intermediaire_avance', 
                'avance', 'expert', 'competition', 'mixte'
            ])->default('debutant');
            
            $table->json('ceintures_acceptees')->nullable(); // IDs ceintures autorisées
            
            // Critères d'âge et capacité
            $table->unsignedTinyInteger('age_minimum')->default(5);
            $table->unsignedTinyInteger('age_maximum')->default(99);
            $table->unsignedTinyInteger('places_maximum')->default(20);
            $table->unsignedTinyInteger('places_minimum')->default(3); // Minimum pour maintenir cours
            
            // Planning détaillé
            $table->enum('jour_semaine', [
                'lundi', 'mardi', 'mercredi', 'jeudi', 
                'vendredi', 'samedi', 'dimanche'
            ]);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->unsignedSmallInteger('duree_minutes')->virtualAs(
                'TIMESTAMPDIFF(MINUTE, heure_debut, heure_fin)'
            );
            
            // Période et récurrence
            $table->date('date_debut'); // Début session
            $table->date('date_fin')->nullable(); // Fin session
            $table->boolean('recurrent')->default(true); // Cours hebdomadaire
            $table->json('dates_exceptions')->nullable(); // Congés, fériés
            $table->json('dates_rattrapages')->nullable(); // Dates make-up
            
            // Lieu et équipement
            $table->string('lieu', 100)->default('Dojo Principal');
            $table->string('salle_specifique', 50)->nullable();
            $table->json('equipements_requis')->nullable(); // Kimono, protections, armes
            $table->json('equipements_fournis')->nullable(); // Matériel fourni par école
            
            // Tarification
            $table->decimal('tarif_mensuel', 8, 2)->default(0.00);
            $table->decimal('tarif_inscription', 8, 2)->default(0.00);
            $table->decimal('tarif_cours_unique', 8, 2)->nullable(); // Pour cours ponctuels
            $table->json('tarifs_speciaux')->nullable(); // Famille, étudiant, etc.
            
            // Programme pédagogique
            $table->json('programme_technique')->nullable(); // Techniques enseignées
            $table->json('programme_kata')->nullable(); // Katas au programme
            $table->json('programme_physique')->nullable(); // Préparation physique
            $table->json('competences_visees')->nullable(); // Objectifs pédagogiques
            
            // Gestion et statut
            $table->boolean('actif')->default(true);
            $table->boolean('inscriptions_ouvertes')->default(true);
            $table->enum('statut', [
                'planifie', 'actif', 'suspendu', 'annule', 
                'termine', 'reporte'
            ])->default('planifie');
            
            $table->text('notes_internes')->nullable(); // Notes pour instructeurs
            $table->json('regles_particulieres')->nullable(); // Règles spécifiques
            
            $table->timestamps();
            
            // Index performance ultra-optimisés
            $table->index(['jour_semaine', 'heure_debut', 'actif']);
            $table->index(['instructeur_principal_id', 'actif']);
            $table->index(['niveau', 'type_cours', 'actif']);
            $table->index(['date_debut', 'date_fin']);
            $table->index('statut');
        });
        
        // Table inscriptions cours (pivot avancée)
        Schema::create('cours_inscriptions', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->foreignId('inscrit_par_id')->constrained('users'); // Qui a fait l'inscription
            
            // Dates et statut inscription
            $table->date('date_inscription');
            $table->date('date_debut_effectif')->nullable(); // Première présence
            $table->date('date_fin_prevue')->nullable();
            $table->date('date_fin_effective')->nullable(); // Dernière présence
            
            $table->enum('statut', [
                'en_attente', 'confirme', 'actif', 'suspendu', 
                'termine', 'abandonne', 'transfere'
            ])->default('en_attente');
            
            // Informations pédagogiques
            $table->foreignId('niveau_entree_id')->nullable()->constrained('ceintures');
            $table->json('objectifs_personnalises')->nullable();
            $table->text('notes_pedagogiques')->nullable();
            $table->json('adaptations_necessaires')->nullable(); // Handicap, blessures
            
            // Facturation et paiement
            $table->decimal('tarif_applique', 8, 2); // Tarif réellement payé
            $table->json('remises_appliquees')->nullable(); // Détail remises
            $table->enum('modalite_paiement', [
                'mensuel', 'trimestriel', 'semestriel', 
                'annuel', 'cours_unique'
            ])->default('mensuel');
            
            // Évaluation et progression
            $table->json('evaluations_periodiques')->nullable(); // Notes instructeur
            $table->unsignedTinyInteger('niveau_satisfaction')->nullable(); // 1-10
            $table->text('commentaires_membre')->nullable();
            $table->text('commentaires_parent')->nullable(); // Si mineur
            
            $table->timestamps();
            
            // Contraintes et index
            $table->unique(['cours_id', 'membre_id', 'date_inscription']);
            $table->index(['membre_id', 'statut']);
            $table->index(['cours_id', 'statut']);
            $table->index('date_inscription');
        });
        
        // Table présences ultra-détaillée
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructeur_id')->constrained('users');
            $table->foreignId('marquee_par_id')->nullable()->constrained('users');
            
            // Date et timing
            $table->date('date_cours');
            $table->time('heure_debut_cours');
            $table->time('heure_fin_cours');
            $table->time('heure_arrivee')->nullable();
            $table->time('heure_depart')->nullable();
            
            // Statut présence détaillé
            $table->enum('statut', [
                'present', 'absent', 'retard', 'parti_tot',
                'excuse', 'maladie', 'blessure', 'vacances',
                'suspension', 'autre'
            ])->default('present');
            
            $table->text('raison_absence')->nullable();
            $table->boolean('justifiee')->default(false);
            
            // Évaluation performance cours
            $table->unsignedTinyInteger('niveau_participation')->nullable(); // 1-10
            $table->unsignedTinyInteger('niveau_technique')->nullable(); // 1-10
            $table->unsignedTinyInteger('niveau_effort')->nullable(); // 1-10
            $table->unsignedTinyInteger('niveau_attitude')->nullable(); // 1-10
            
            // Notes détaillées
            $table->json('techniques_travaillees')->nullable();
            $table->json('points_forts_seance')->nullable();
            $table->json('points_amelioration')->nullable();
            $table->text('commentaires_instructeur')->nullable();
            $table->text('observations_comportement')->nullable();
            
            // Informations médicales cours
            $table->boolean('incident_medical')->default(false);
            $table->text('details_incident')->nullable();
            $table->boolean('parents_prevus')->default(false); // Si incident mineur
            
            // Données système
            $table->enum('mode_marquage', [
                'manuel', 'tablette', 'qr_code', 'badge',
                'reconnaissance_faciale', 'import'
            ])->default('manuel');
            
            $table->timestamp('marquee_a')->nullable();
            $table->json('metadata')->nullable(); // Données additionnelles
            
            $table->timestamps();
            
            // Contraintes et index ultra-optimisés
            $table->unique(['cours_id', 'membre_id', 'date_cours']);
            $table->index(['date_cours', 'statut']);
            $table->index(['membre_id', 'date_cours']);
            $table->index(['instructeur_id', 'date_cours']);
            $table->index(['statut', 'justifiee']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
        Schema::dropIfExists('cours_inscriptions');
        Schema::dropIfExists('cours');
    }
};
MIGRATION_COURS

echo "✅ Migration cours ultra-professionnelle créée"
echo "Fichier: $MIGRATION_FILE"
