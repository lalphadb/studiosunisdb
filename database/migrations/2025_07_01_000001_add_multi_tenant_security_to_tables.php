<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. inscriptions_cours
        if (!Schema::hasColumn('inscriptions_cours', 'ecole_id')) {
            Schema::table('inscriptions_cours', function (Blueprint $table) {
                $table->foreignId('ecole_id')->after('user_id')->constrained('ecoles')->onDelete('cascade');
                $table->index('ecole_id');
            });
        }

        // 2. inscriptions_seminaires  
        if (!Schema::hasColumn('inscriptions_seminaires', 'ecole_id')) {
            Schema::table('inscriptions_seminaires', function (Blueprint $table) {
                $table->foreignId('ecole_id')->after('user_id')->constrained('ecoles')->onDelete('cascade');
                $table->index('ecole_id');
            });
        }

        // 3. cours_horaires
        if (!Schema::hasColumn('cours_horaires', 'ecole_id')) {
            Schema::table('cours_horaires', function (Blueprint $table) {
                $table->foreignId('ecole_id')->after('cours_id')->constrained('ecoles')->onDelete('cascade');
                $table->index('ecole_id');
            });
        }

        // 4. presences
        if (!Schema::hasColumn('presences', 'ecole_id')) {
            Schema::table('presences', function (Blueprint $table) {
                $table->foreignId('ecole_id')->after('user_id')->constrained('ecoles')->onDelete('cascade');
                $table->index('ecole_id');
            });
        }

        // 5. ceintures (optionnel - peut être global)
        if (!Schema::hasColumn('ceintures', 'ecole_id')) {
            Schema::table('ceintures', function (Blueprint $table) {
                $table->foreignId('ecole_id')->nullable()->after('id')->constrained('ecoles')->onDelete('cascade');
                $table->index('ecole_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['inscriptions_cours', 'inscriptions_seminaires', 'cours_horaires', 'presences', 'ceintures'];
        
        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'ecole_id')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropForeign(['ecole_id']);
                    $blueprint->dropColumn('ecole_id');
                });
            }
        }
    }
};
