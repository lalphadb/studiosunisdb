<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // FIX CRITIQUE: Corriger enum niveau pour inclure toutes les valeurs frontend
        DB::statement("ALTER TABLE cours MODIFY COLUMN niveau ENUM(
            'tous',
            'debutant', 
            'intermediaire', 
            'avance', 
            'prive',
            'competition',
            'a_la_carte'
        ) NOT NULL DEFAULT 'debutant'");

        // Migrer les données existantes si nécessaire
        DB::update("UPDATE cours SET niveau = 'competition' WHERE niveau = 'competiteur'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer l'ancien enum si rollback nécessaire
        DB::update("UPDATE cours SET niveau = 'competiteur' WHERE niveau = 'competition'");

        DB::statement("ALTER TABLE cours MODIFY COLUMN niveau ENUM(
            'debutant',
            'intermediaire', 
            'avance', 
            'competiteur',
            'tous'
        ) NOT NULL DEFAULT 'debutant'");
    }
};
