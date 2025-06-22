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
        Schema::table('users', function (Blueprint $table) {
            // --- CORRECTION CLÉ ---
            // On vérifie si l'index n'existe pas déjà avant de le créer.
            // La méthode de vérification est différente pour mysql et sqlite.
            $tableName = $table->getTable();
            $connection = Schema::getConnection()->getDriverName();

            $indexName = 'users_ecole_id_active_index';
            if (!$this->indexExists($tableName, $indexName, $connection)) {
                $table->index(['ecole_id', 'active'], $indexName);
            }

            $indexName = 'users_created_at_index';
            if (!$this->indexExists($tableName, $indexName, $connection)) {
                $table->index('created_at', $indexName);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndexIfExists('users_ecole_id_active_index');
            $table->dropIndexIfExists('users_created_at_index');
        });
    }

    /**
     * Vérifie si un index existe, compatible avec MySQL et SQLite.
     */
    private function indexExists(string $tableName, string $indexName, string $connection): bool
    {
        if ($connection === 'sqlite') {
            $sql = "PRAGMA index_list(`{$tableName}`)";
            $indexes = \Illuminate\Support\Facades\DB::select($sql);
            foreach ($indexes as $index) {
                if (isset($index->name) && $index->name === $indexName) {
                    return true;
                }
            }
            return false;
        }

        // Pour MySQL
        $sql = "SHOW INDEX FROM `{$tableName}` WHERE Key_name = '{$indexName}'";
        return !empty(\Illuminate\Support\Facades\DB::select($sql));
    }
};
