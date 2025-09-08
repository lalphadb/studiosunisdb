<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $tables = [
            'consentements',
            'examens',
            'factures',
            'paiements',
            'presences',
            'progression_ceintures'
        ];

        foreach ($tables as $tableName) {
            // Ajouter user_id si elle n'existe pas
            if (!Schema::hasColumn($tableName, 'user_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id');
                    $table->index('user_id');
                });
            }

            // Utiliser la table de mapping pour migrer les données
            DB::statement("
                UPDATE {$tableName} t
                INNER JOIN migration_membres_users m ON t.membre_id = m.membre_id
                SET t.user_id = m.user_id
                WHERE t.user_id IS NULL
            ");

            // Gérer les contraintes de foreign key
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                // Vérifier et supprimer l'ancienne FK membre_id si elle existe
                $oldForeignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = '{$tableName}' 
                    AND COLUMN_NAME = 'membre_id'
                    AND REFERENCED_TABLE_NAME = 'membres'
                ");
                
                foreach ($oldForeignKeys as $fk) {
                    $table->dropForeign($fk->CONSTRAINT_NAME);
                }
                
                // Vérifier si la FK user_id existe déjà
                $existingUserFK = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = '{$tableName}' 
                    AND COLUMN_NAME = 'user_id'
                    AND REFERENCED_TABLE_NAME = 'users'
                ");
                
                // Ajouter la nouvelle FK seulement si elle n'existe pas
                if (empty($existingUserFK)) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
            });
        }
    }

    public function down()
    {
        $tables = [
            'consentements',
            'examens',
            'factures',
            'paiements',
            'presences',
            'progression_ceintures'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                // Supprimer la FK user_id si elle existe
                $userForeignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = '{$tableName}' 
                    AND COLUMN_NAME = 'user_id'
                    AND REFERENCED_TABLE_NAME = 'users'
                ");
                
                foreach ($userForeignKeys as $fk) {
                    $table->dropForeign($fk->CONSTRAINT_NAME);
                }
                
                // Supprimer la colonne user_id
                if (Schema::hasColumn($tableName, 'user_id')) {
                    $table->dropColumn('user_id');
                }
            });
        }
    }
};
