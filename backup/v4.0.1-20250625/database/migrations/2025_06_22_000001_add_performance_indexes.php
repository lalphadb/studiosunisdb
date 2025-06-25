<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // On utilise une méthode de vérification robuste avant de créer les index
            $this->createIndexIfNotExists($table, ['ecole_id', 'active'], 'users_ecole_id_active_index');
            $this->createIndexIfNotExists($table, ['created_at'], 'users_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // dropIndexIfExists est sûr car il ne génère pas d'erreur si l'index n'existe pas.
            $table->dropIndexIfExists('users_ecole_id_active_index');
            $table->dropIndexIfExists('users_created_at_index');
        });
    }

    /**
     * Crée un index seulement s'il n'existe pas déjà.
     * Compatible avec MySQL et SQLite.
     *
     * @param Blueprint $table
     * @param string|array $columns
     * @param string $indexName
     * @return void
     */
    private function createIndexIfNotExists(Blueprint $table, $columns, string $indexName): void
    {
        $connection = Schema::getConnection()->getDriverName();
        $tableName = $table->getTable();

        $indexExists = false;
        if ($connection === 'sqlite') {
            $sql = "PRAGMA index_list(`{$tableName}`)";
            $indexes = DB::select($sql);
            foreach ($indexes as $index) {
                if (isset($index->name) && $index->name === $indexName) {
                    $indexExists = true;
                    break;
                }
            }
        } else { // Pour MySQL
            $sql = "SHOW INDEX FROM `{$tableName}` WHERE Key_name = '{$indexName}'";
            $indexExists = !empty(DB::select($sql));
        }

        if (! $indexExists) {
            $table->index($columns, $indexName);
        }
    }
};
