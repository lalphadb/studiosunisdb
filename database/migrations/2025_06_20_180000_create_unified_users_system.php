<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Étendre la table users existante pour inclure les champs métier
        Schema::table('users', function (Blueprint $table) {
            // Champs métier des karatékas (ex-membres)
            $table->date('date_inscription')->default(now()->format('Y-m-d'))->after('active');
            $table->text('notes')->nullable()->after('date_inscription');
            
            // Index pour performance
            $table->index(['ecole_id', 'active']);
            $table->index('date_inscription');
        });
        
        echo "Table users étendue avec les champs métier.\n";
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['ecole_id', 'active']);
            $table->dropIndex(['date_inscription']);
            $table->dropColumn(['date_inscription', 'notes']);
        });
    }
};
