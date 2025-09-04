<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tables = ['users','membres','cours','presences','paiements'];
        $dbName = DB::getDatabaseName();

        foreach ($tables as $table) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'ecole_id')) {
                continue;
            }

            // 1) Drop FK si elle existe (détectée dynamiquement)
            $constraint = DB::table('information_schema.KEY_COLUMN_USAGE')
                ->where('TABLE_SCHEMA', $dbName)
                ->where('TABLE_NAME', $table)
                ->where('COLUMN_NAME', 'ecole_id')
                ->whereNotNull('REFERENCED_TABLE_NAME')
                ->value('CONSTRAINT_NAME');

            if ($constraint) {
                try {
                    DB::statement(sprintf('ALTER TABLE `%s` DROP FOREIGN KEY `%s`', $table, $constraint));
                } catch (\Throwable $e) {
                    // ignorer si déjà supprimée
                }
            }

            // 2) Drop tous les index impliquant ecole_id (s’ils existent)
            $indexes = DB::table('information_schema.STATISTICS')
                ->select('INDEX_NAME')
                ->where('TABLE_SCHEMA', $dbName)
                ->where('TABLE_NAME', $table)
                ->where('COLUMN_NAME', 'ecole_id')
                ->pluck('INDEX_NAME')
                ->unique()
                ->toArray();

            foreach ($indexes as $indexName) {
                try {
                    Schema::table($table, function (Blueprint $t) use ($indexName) {
                        $t->dropIndex($indexName);
                    });
                } catch (\Throwable $e) {
                    // ignorer si déjà supprimé
                }
            }

            // 3) Supprime la colonne si elle existe encore
            if (Schema::hasColumn($table, 'ecole_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropColumn('ecole_id');
                });
            }
        }
    }

    public function down(): void
    {
        // No-op (on reste mono-école)
    }
};
