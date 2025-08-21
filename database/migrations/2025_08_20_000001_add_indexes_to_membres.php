<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Méthode plus robuste avec try-catch pour chaque index
        Schema::table('membres', function (Blueprint $table) {
            // Index composite statut + date_derniere_presence
            try {
                if (!$this->indexExists('membres', 'membres_statut_date_derniere_presence_index')) {
                    $table->index(['statut', 'date_derniere_presence'], 'membres_statut_date_derniere_presence_index');
                }
            } catch (\Exception $e) {
                // Index existe déjà, on continue
            }
            
            // Index sur date_inscription
            try {
                if (!$this->indexExists('membres', 'membres_date_inscription_index')) {
                    $table->index('date_inscription', 'membres_date_inscription_index');
                }
            } catch (\Exception $e) {
                // Index existe déjà, on continue
            }
            
            // Index sur ecole_id
            try {
                if (!$this->indexExists('membres', 'membres_ecole_id_index')) {
                    $table->index('ecole_id', 'membres_ecole_id_index');
                }
            } catch (\Exception $e) {
                // Index existe déjà, on continue
            }
        });
    }

    public function down(): void
    {
        Schema::table('membres', function (Blueprint $table) {
            // Supprimer les indexes s'ils existent
            if ($this->indexExists('membres', 'membres_statut_date_derniere_presence_index')) {
                $table->dropIndex('membres_statut_date_derniere_presence_index');
            }
            
            if ($this->indexExists('membres', 'membres_date_inscription_index')) {
                $table->dropIndex('membres_date_inscription_index');
            }
            
            if ($this->indexExists('membres', 'membres_ecole_id_index')) {
                $table->dropIndex('membres_ecole_id_index');
            }
        });
    }
    
    /**
     * Vérifier si un index existe sur une table
     */
    private function indexExists($table, $index): bool
    {
        $indexes = DB::select("SHOW INDEX FROM {$table}");
        foreach ($indexes as $idx) {
            if ($idx->Key_name === $index) {
                return true;
            }
        }
        return false;
    }
};
