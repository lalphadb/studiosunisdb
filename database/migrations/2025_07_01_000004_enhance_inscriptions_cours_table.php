<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inscriptions_cours', function (Blueprint $table) {
            // Relations avec horaires et sessions
            if (!Schema::hasColumn('inscriptions_cours', 'cours_horaire_id')) {
                $table->foreignId('cours_horaire_id')->nullable()->after('cours_id')->constrained('cours_horaires')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('inscriptions_cours', 'session_id')) {
                $table->foreignId('session_id')->nullable()->after('cours_horaire_id')->constrained('sessions_cours')->onDelete('cascade');
            }
            
            // Fréquence inscription
            if (!Schema::hasColumn('inscriptions_cours', 'frequence')) {
                $table->enum('frequence', ['1x_semaine', '2x_semaine'])->default('1x_semaine')->after('session_id');
            }
            
            // Métadonnées inscription
            if (!Schema::hasColumn('inscriptions_cours', 'prix_paye')) {
                $table->decimal('prix_paye', 8, 2)->nullable()->after('frequence');
            }
            
            if (!Schema::hasColumn('inscriptions_cours', 'horaires_choisis')) {
                $table->json('horaires_choisis')->nullable()->after('prix_paye');
            }
            
            if (!Schema::hasColumn('inscriptions_cours', 'reinscription')) {
                $table->boolean('reinscription')->default(false)->after('horaires_choisis');
            }
            
            // Index pour performance
            $table->index(['session_id', 'statut'], 'idx_session_statut');
            $table->index(['user_id', 'session_id'], 'idx_user_session');
            $table->index(['ecole_id', 'session_id'], 'idx_ecole_session');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscriptions_cours', function (Blueprint $table) {
            $foreignKeys = ['cours_horaire_id', 'session_id'];
            $columns = ['cours_horaire_id', 'session_id', 'frequence', 'prix_paye', 'horaires_choisis', 'reinscription'];
            
            foreach ($foreignKeys as $fk) {
                if (Schema::hasColumn('inscriptions_cours', $fk)) {
                    $table->dropForeign([$fk]);
                }
            }
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('inscriptions_cours', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
