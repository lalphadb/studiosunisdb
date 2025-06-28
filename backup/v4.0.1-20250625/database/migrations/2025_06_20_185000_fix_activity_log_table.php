<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter la colonne event manquante à activity_log
        if (Schema::hasTable('activity_log') && !Schema::hasColumn('activity_log', 'event')) {
            Schema::table('activity_log', function (Blueprint $table) {
                $table->string('event')->nullable()->after('description');
            });
            echo "Colonne 'event' ajoutée à activity_log.\n";
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('activity_log') && Schema::hasColumn('activity_log', 'event')) {
            Schema::table('activity_log', function (Blueprint $table) {
                $table->dropColumn('event');
            });
        }
    }
};
