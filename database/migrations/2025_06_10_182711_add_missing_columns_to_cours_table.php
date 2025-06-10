<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Ajouter instructeur_id
            if (!Schema::hasColumn('cours', 'instructeur_id')) {
                $table->foreignId('instructeur_id')->nullable()->constrained('users')->onDelete('cascade');
            }
            
            // Ajouter colonnes horaires
            if (!Schema::hasColumn('cours', 'jour_semaine')) {
                $table->enum('jour_semaine', ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'])->nullable();
            }
            
            if (!Schema::hasColumn('cours', 'heure_debut')) {
                $table->time('heure_debut')->nullable();
            }
            
            if (!Schema::hasColumn('cours', 'heure_fin')) {
                $table->time('heure_fin')->nullable();
            }
            
            // Ajouter type de cours
            if (!Schema::hasColumn('cours', 'type_cours')) {
                $table->enum('type_cours', ['karate', 'boxe', 'kickboxing', 'cardiobox', 'enfants', 'adultes'])->default('karate');
            }
            
            // Renommer statut en status pour cohérence
            if (Schema::hasColumn('cours', 'statut') && !Schema::hasColumn('cours', 'status')) {
                $table->renameColumn('statut', 'status');
            }
            
            // Ajouter prix détaillés
            if (!Schema::hasColumn('cours', 'prix_mensuel')) {
                $table->decimal('prix_mensuel', 8, 2)->default(0);
            }
            
            if (!Schema::hasColumn('cours', 'prix_session')) {
                $table->decimal('prix_session', 8, 2)->default(0);
            }
            
            // Ajouter dates de session
            if (!Schema::hasColumn('cours', 'date_debut')) {
                $table->date('date_debut')->nullable();
            }
            
            if (!Schema::hasColumn('cours', 'date_fin')) {
                $table->date('date_fin')->nullable();
            }
        });
        
        // Migrer les données existantes
        DB::statement("UPDATE cours SET prix_mensuel = prix WHERE prix IS NOT NULL AND prix_mensuel = 0");
    }

    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->dropColumn([
                'instructeur_id', 'jour_semaine', 'heure_debut', 'heure_fin', 
                'type_cours', 'prix_mensuel', 'prix_session', 
                'date_debut', 'date_fin'
            ]);
        });
    }
};
