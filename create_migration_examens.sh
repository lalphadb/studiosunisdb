#!/bin/bash

echo "🔧 CRÉATION MIGRATION EXAMENS SÉPARÉE"
echo "===================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

sleep 2
php artisan make:migration create_examens_ceintures_table --create=examens_ceintures

MIGRATION_FILE=$(find database/migrations -name "*_create_examens_ceintures_table.php" | head -1)

cat > "$MIGRATION_FILE" << 'MIGRATION_EXAMENS'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examens_ceintures', function (Blueprint $table) {
            $table->id();
            
            // Relations - TOUTES les tables existent maintenant
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->foreignId('ceinture_actuelle_id')->constrained('ceintures');
            $table->foreignId('ceinture_cible_id')->constrained('ceintures');
            $table->foreignId('instructeur_principal_id')->constrained('users');
            $table->foreignId('instructeur_assistant_id')->nullable()->constrained('users');
            
            // Planification
            $table->enum('statut', [
                'eligible', 'inscrit', 'planifie', 'en_cours', 
                'termine', 'reussi', 'echec', 'reporte', 'annule'
            ])->default('eligible');
            
            $table->datetime('date_examen')->nullable();
            $table->string('lieu_examen')->nullable();
            $table->unsignedSmallInteger('duree_minutes')->default(60);
            
            // Évaluations
            $table->json('evaluations_techniques')->nullable();
            $table->json('evaluations_kata')->nullable();
            $table->json('evaluations_combat')->nullable();
            $table->json('evaluation_theorique')->nullable();
            
            // Notes
            $table->unsignedTinyInteger('note_technique')->nullable();
            $table->unsignedTinyInteger('note_physique')->nullable();
            $table->unsignedTinyInteger('note_mentale')->nullable();
            $table->unsignedTinyInteger('note_generale')->nullable();
            $table->decimal('note_finale', 5, 2)->nullable();
            
            // Résultats
            $table->text('commentaires_instructeur')->nullable();
            $table->text('points_forts')->nullable();
            $table->text('axes_amelioration')->nullable();
            $table->text('recommandations')->nullable();
            
            // Validation
            $table->datetime('date_validation')->nullable();
            $table->foreignId('validateur_id')->nullable()->constrained('users');
            $table->string('numero_certificat')->nullable()->unique();
            $table->date('date_delivrance_certificat')->nullable();
            
            // Méta
            $table->json('conditions_examen')->nullable();
            $table->json('media_files')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index(['membre_id', 'statut']);
            $table->index(['date_examen', 'statut']);
            $table->index(['instructeur_principal_id', 'date_examen']);
            $table->index('numero_certificat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examens_ceintures');
    }
};
MIGRATION_EXAMENS

echo "✅ Migration examens créée: $MIGRATION_FILE"
