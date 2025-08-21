<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tables = [
            'users',
            'membres',
            'cours',
            'presences',
            'paiements',
            // ajoute ici d'autres tables si jamais tu avais ecole_id ailleurs
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'ecole_id')) {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    // essayer de drop la FK si existante, sinon drop la colonne
                    try {
                        $t->dropConstrainedForeignId('ecole_id');
                    } catch (\Throwable $e) {
                        try {
                            $t->dropForeign([$table.'_ecole_id_foreign']);
                        } catch (\Throwable $e2) {}
                        if (Schema::hasColumn($table, 'ecole_id')) {
                            $t->dropColumn('ecole_id');
                        }
                    }
                });
            }
        }
    }

    public function down(): void
    {
        // pas de restauration: on reste mono-école
    }
};
