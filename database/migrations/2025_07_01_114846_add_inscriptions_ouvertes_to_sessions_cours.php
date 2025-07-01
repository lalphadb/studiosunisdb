<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sessions_cours', function (Blueprint $table) {
            $table->boolean('inscriptions_ouvertes')->default(false)->after('actif');
            $table->date('date_limite_inscription')->nullable()->after('inscriptions_ouvertes');
        });
    }

    public function down(): void
    {
        Schema::table('sessions_cours', function (Blueprint $table) {
            $table->dropColumn(['inscriptions_ouvertes', 'date_limite_inscription']);
        });
    }
};
