<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaultEcoleId = DB::table('ecoles')->first()?->id ?? 1;
        
        // Tables nécessitant ecole_id
        $tables = [
            'membres',
            'paiements', 
            'presences',
            'factures',
            'progression_ceintures',
            'examens'
        ];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'ecole_id')) {
                Schema::table($table, function (Blueprint $t) use ($defaultEcoleId) {
                    $t->foreignId('ecole_id')
                      ->default($defaultEcoleId)
                      ->constrained('ecoles')
                      ->onDelete('cascade');
                    $t->index('ecole_id');
                });
            }
        }
    }

    public function down(): void
    {
        // Retrait des colonnes
        $tables = ['membres','paiements','presences','factures','progression_ceintures','examens'];
        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'ecole_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropForeign(['ecole_id']);
                    $t->dropColumn('ecole_id');
                });
            }
        }
    }
};
