<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Vérifier et ajouter index seulement s'ils n'existent pas
        $indexExists = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_ecole_id_active_index'");
        
        if (empty($indexExists)) {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['ecole_id', 'active'], 'users_ecole_id_active_index');
            });
        }
        
        $createdAtExists = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_created_at_index'");
        
        if (empty($createdAtExists)) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('created_at', 'users_created_at_index');
            });
        }
        
        // Autres tables si elles existent
        if (Schema::hasTable('presences')) {
            $presencesExists = DB::select("SHOW INDEX FROM presences WHERE Key_name = 'presences_cours_id_date_cours_index'");
            
            if (empty($presencesExists)) {
                Schema::table('presences', function (Blueprint $table) {
                    $table->index(['cours_id', 'date_cours'], 'presences_cours_id_date_cours_index');
                });
            }
        }
        
        if (Schema::hasTable('paiements')) {
            $paiementsExists = DB::select("SHOW INDEX FROM paiements WHERE Key_name = 'paiements_ecole_id_statut_index'");
            
            if (empty($paiementsExists)) {
                Schema::table('paiements', function (Blueprint $table) {
                    $table->index(['ecole_id', 'statut'], 'paiements_ecole_id_statut_index');
                });
            }
        }
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
        
        if (Schema::hasTable('presences')) {
            Schema::table('presences', function (Blueprint $table) {
                $table->dropIndex(['cours_id', 'date_cours']);
            });
        }
        
        if (Schema::hasTable('paiements')) {
            Schema::table('paiements', function (Blueprint $table) {
                $table->dropIndex(['ecole_id', 'statut']);
            });
        }
    }
};
