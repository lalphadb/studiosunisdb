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
        Schema::table('cours_horaires', function (Blueprint $table) {
            // Ajouter session_id si pas déjà présent
            if (!Schema::hasColumn('cours_horaires', 'session_id')) {
                $table->foreignId('session_id')->nullable()->after('ecole_id')->constrained('sessions_cours')->onDelete('cascade');
            }
            
            // Enrichir avec données métier
            if (!Schema::hasColumn('cours_horaires', 'salle')) {
                $table->string('salle')->nullable()->after('heure_fin');
            }
            
            if (!Schema::hasColumn('cours_horaires', 'instructeur_affecte')) {
                $table->string('instructeur_affecte')->nullable()->after('salle');
            }
            
            if (!Schema::hasColumn('cours_horaires', 'capacite_max')) {
                $table->integer('capacite_max')->default(20)->after('instructeur_affecte');
            }
            
            if (!Schema::hasColumn('cours_horaires', 'prix')) {
                $table->decimal('prix', 8, 2)->nullable()->after('capacite_max');
            }
            
            if (!Schema::hasColumn('cours_horaires', 'nom_affiche')) {
                $table->string('nom_affiche')->nullable()->after('prix');
            }
            
            // Index pour performance
            $table->index(['session_id', 'active'], 'idx_session_active');
            $table->index(['cours_id', 'session_id'], 'idx_cours_session');
            $table->index(['ecole_id', 'jour_semaine'], 'idx_ecole_jour');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cours_horaires', function (Blueprint $table) {
            $columns = ['session_id', 'salle', 'instructeur_affecte', 'capacite_max', 'prix', 'nom_affiche'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('cours_horaires', $column)) {
                    if ($column === 'session_id') {
                        $table->dropForeign(['session_id']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};
