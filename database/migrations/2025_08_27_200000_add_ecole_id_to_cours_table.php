<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Ajouter colonne ecole_id (nullable temporairement)
            $table->foreignId('ecole_id')->nullable()->constrained()->onDelete('cascade');
            $table->index(['ecole_id', 'actif']); // Performance planning
        });
        
        // Populer avec première école (mono-école)
        $premiereEcole = DB::table('ecoles')->first();
        if ($premiereEcole) {
            DB::table('cours')->update(['ecole_id' => $premiereEcole->id]);
        }
        
        // Rendre obligatoire après population
        Schema::table('cours', function (Blueprint $table) {
            $table->foreignId('ecole_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->dropForeign(['ecole_id']);
            $table->dropColumn('ecole_id');
        });
    }
};
