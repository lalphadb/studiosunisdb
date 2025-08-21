<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tables qui doivent avoir ecole_id
        $tables = ['cours', 'presences', 'paiements', 'ceintures', 'examens', 'families', 'progression_ceintures', 'factures'];
        
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'ecole_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->unsignedBigInteger('ecole_id')->nullable()->after('id')->index();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = ['cours', 'presences', 'paiements', 'ceintures', 'examens', 'families', 'progression_ceintures', 'factures'];
        
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'ecole_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('ecole_id');
                });
            }
        }
    }
};
