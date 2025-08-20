<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['users','membres'] as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'ecole_id')) {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    $t->unsignedBigInteger('ecole_id')->nullable()->index()->after('id');
                    if (Schema::hasTable('ecoles')) {
                        $t->foreign('ecole_id')->references('id')->on('ecoles')->nullOnDelete();
                    }
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['users','membres'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'ecole_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropColumn('ecole_id');
                });
            }
        }
    }
};
