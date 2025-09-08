<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // FIX 1: Rendre instructeur_id nullable (pas obligatoire pour admin)
            $table->unsignedBigInteger('instructeur_id')->nullable()->change();

            // FIX 2: Ajouter colonne statut manquante
            if (! Schema::hasColumn('cours', 'statut')) {
                $table->enum('statut', ['actif', 'inactif', 'suspendu', 'archive'])
                    ->default('actif')
                    ->after('actif');
            }

            // FIX 3: Ajouter index pour performance
            $table->index(['ecole_id', 'statut', 'actif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Restaurer instructeur_id NOT NULL
            $table->unsignedBigInteger('instructeur_id')->nullable(false)->change();

            // Supprimer colonne statut si ajoutée
            if (Schema::hasColumn('cours', 'statut')) {
                $table->dropColumn('statut');
            }

            // Supprimer index
            $table->dropIndex(['ecole_id', 'statut', 'actif']);
        });
    }
};
