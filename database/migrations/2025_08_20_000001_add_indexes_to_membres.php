<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('membres', function (Blueprint $table) {
            $table->index(['statut', 'date_derniere_presence']);
            $table->index('date_inscription');
            $table->index('ecole_id');
        });
    }

    public function down(): void
    {
        Schema::table('membres', function (Blueprint $table) {
            $table->dropIndex(['statut', 'date_derniere_presence']);
            $table->dropIndex(['date_inscription']);
            $table->dropIndex(['ecole_id']);
        });
    }
};
