#!/bin/bash

echo "🥋 CRÉATION MIGRATION CEINTURES ULTRA-PRO"
echo "========================================"

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Création migration ceintures
php artisan make:migration create_ceintures_table

# Remplacement du contenu généré par version ultra-pro
MIGRATION_FILE=$(find database/migrations -name "*_create_ceintures_table.php" | head -1)

cat > "$MIGRATION_FILE" << 'MIGRATION_CEINTURES'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration ultra-professionnelle système ceintures karaté
     * StudiosDB v5 Pro - Laravel 12.20
     */
    public function up(): void
    {
        Schema::create('ceintures', function (Blueprint $table) {
            $table->id();
            
            // Informations ceinture
            $table->string('nom', 50); // Blanc, Jaune, Orange, Vert, Bleu, Marron, Noir
            $table->string('nom_en', 50)->nullable(); // White, Yellow, Orange, etc.
            $table->string('couleur_hex', 7)->default('#000000'); // Couleur hexa
            $table->string('couleur_secondaire_hex', 7)->nullable(); // Pour ceintures bicolores
            
            // Système hiérarchique
            $table->unsignedTinyInteger('ordre')->unique(); // 1-20 (Blanc=1, Noir 1er Dan=10, etc.)
            $table->unsignedTinyInteger('niveau_difficulte')->default(1); // 1-10
            $table->boolean('est_dan')->default(false); // true pour ceintures noires
            $table->unsignedTinyInteger('dan_niveau')->nullable(); // 1er dan, 2ème dan, etc.
            
            // Pré-requis techniques
            $table->json('techniques_requises')->nullable(); // Kata, combats, techniques
            $table->json('connaissances_theoriques')->nullable(); // Histoire, philosophie
            $table->unsignedSmallInteger('duree_minimum_mois')->default(6); // Temps minimum
            $table->unsignedSmallInteger('presences_minimum')->default(48); // Cours minimum
            $table->unsignedTinyInteger('age_minimum')->default(6); // Âge requis
            
            // Critères d'évaluation (sur 100)
            $table->unsignedTinyInteger('seuil_technique')->default(70); // % requis technique
            $table->unsignedTinyInteger('seuil_physique')->default(60); // % requis physique  
            $table->unsignedTinyInteger('seuil_mental')->default(50); // % requis mental
            $table->unsignedTinyInteger('seuil_global')->default(65); // % global requis
            
            // Informations administratives
            $table->decimal('cout_examen', 8, 2)->default(0.00); // Coût passage
            $table->text('description')->nullable(); // Description complète
            $table->text('objectifs_pedagogiques')->nullable(); // Objectifs d'apprentissage
            $table->string('certificat_template')->nullable(); // Template certificat
            
            // Méta-données
            $table->boolean('active')->default(true);
            $table->boolean('visible_membres')->default(true); // Visible aux élèves
            $table->json('metadata')->nullable(); // Données additionnelles
            
            $table->timestamps();
            
            // Index performance
            $table->index(['ordre', 'active']);
            $table->index(['est_dan', 'dan_niveau']);
            $table->index('active');
        });
        
        // Table examens ceintures
        Schema::create('examens_ceintures', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->foreignId('ceinture_actuelle_id')->constrained('ceintures');
            $table->foreignId('ceinture_cible_id')->constrained('ceintures');
            $table->foreignId('instructeur_principal_id')->constrained('users');
            $table->foreignId('instructeur_assistant_id')->nullable()->constrained('users');
            
            // Planification examen
            $table->enum('statut', [
                'eligible', 'inscrit', 'planifie', 'en_cours', 
                'termine', 'reussi', 'echec', 'reporte', 'annule'
            ])->default('eligible');
            
            $table->datetime('date_examen')->nullable();
            $table->string('lieu_examen')->nullable();
            $table->unsignedSmallInteger('duree_minutes')->default(60);
            
            // Évaluations détaillées
            $table->json('evaluations_techniques')->nullable(); // Notes par technique
            $table->json('evaluations_kata')->nullable(); // Notes kata
            $table->json('evaluations_combat')->nullable(); // Notes combats
            $table->json('evaluation_theorique')->nullable(); // Questions/réponses
            
            // Notes finales (sur 100)
            $table->unsignedTinyInteger('note_technique')->nullable();
            $table->unsignedTinyInteger('note_physique')->nullable();
            $table->unsignedTinyInteger('note_mentale')->nullable();
            $table->unsignedTinyInteger('note_generale')->nullable();
            $table->decimal('note_finale', 5, 2)->nullable(); // Note finale calculée
            
            // Résultats et suivi
            $table->text('commentaires_instructeur')->nullable();
            $table->text('points_forts')->nullable();
            $table->text('axes_amelioration')->nullable();
            $table->text('recommandations')->nullable();
            
            // Validation officielle
            $table->datetime('date_validation')->nullable(); // Date validation résultats
            $table->foreignId('validateur_id')->nullable()->constrained('users');
            $table->string('numero_certificat')->nullable()->unique();
            $table->date('date_delivrance_certificat')->nullable();
            
            // Méta-données
            $table->json('conditions_examen')->nullable(); // Température, nb spectateurs, etc.
            $table->json('media_files')->nullable(); // Photos, vidéos examen
            
            $table->timestamps();
            
            // Index performance
            $table->index(['membre_id', 'statut']);
            $table->index(['date_examen', 'statut']);
            $table->index(['instructeur_principal_id', 'date_examen']);
            $table->index('numero_certificat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examens_ceintures');
        Schema::dropIfExists('ceintures');
    }
};
MIGRATION_CEINTURES

echo "✅ Migration ceintures ultra-professionnelle créée"
echo "Fichier: $MIGRATION_FILE"
