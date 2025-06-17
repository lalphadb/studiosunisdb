<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter seulement la colonne manquante session_id
        Schema::table('cours', function (Blueprint $table) {
            $table->foreignId('session_id')->nullable()->constrained('sessions_cours')->onDelete('set null')->after('ecole_id');
        });
    }

    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropColumn('session_id');
        });
    }
};
