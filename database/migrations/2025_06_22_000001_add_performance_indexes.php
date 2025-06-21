<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index(['ecole_id', 'active']); // Multi-tenant + actif
            $table->index('created_at'); // Tri chronologique
        });
        
        Schema::table('presences', function (Blueprint $table) {
            $table->index(['cours_id', 'date_cours']); // Requêtes fréquentes
        });
        
        Schema::table('paiements', function (Blueprint $table) {
            $table->index(['ecole_id', 'statut']); // Filtres admin
        });
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['ecole_id', 'active']);
            $table->dropIndex(['created_at']);
        });
    }
};
