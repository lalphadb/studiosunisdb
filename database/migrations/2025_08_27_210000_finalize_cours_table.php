<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Instructeur optionnel (ecole_id déjà ajouté)
     */
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Rendre instructeur_id nullable (optionnel) - SEULEMENT si pas déjà fait
            if (Schema::hasColumn('cours', 'instructeur_id')) {
                $table->foreignId('instructeur_id')->nullable()->change();
            }
            
            // Ajouter ecole_id SEULEMENT s'il n'existe pas déjà
            if (!Schema::hasColumn('cours', 'ecole_id')) {
                $table->foreignId('ecole_id')->nullable()->constrained('ecoles')->onDelete('cascade');
                $table->index(['ecole_id', 'actif']); // Performance
                
                // Populer ecole_id avec première école
                $premiereEcole = \DB::table('ecoles')->first();
                if ($premiereEcole) {
                    \DB::table('cours')->update(['ecole_id' => $premiereEcole->id]);
                    
                    // Rendre obligatoire après population
                    $table->foreignId('ecole_id')->nullable(false)->change();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Remettre instructeur_id required si modifié
            if (Schema::hasColumn('cours', 'instructeur_id')) {
                $table->foreignId('instructeur_id')->nullable(false)->change();
            }
            
            // Supprimer ecole_id seulement s'il existe
            if (Schema::hasColumn('cours', 'ecole_id')) {
                $table->dropForeign(['ecole_id']);
                $table->dropIndex(['ecole_id', 'actif']);
                $table->dropColumn('ecole_id');
            }
        });
    }
};
