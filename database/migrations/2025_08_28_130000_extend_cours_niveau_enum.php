<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Extension colonne niveau ENUM
     */
    public function up(): void
    {
        // Méthode MySQL pour étendre un ENUM existant
        DB::statement("ALTER TABLE cours MODIFY COLUMN niveau ENUM('tous', 'debutant', 'intermediaire', 'avance', 'prive', 'competition', 'a_la_carte') NOT NULL");
        
        // Optionnel : mettre à jour les anciens niveaux vers 'tous' si nécessaire
        // DB::statement("UPDATE cours SET niveau = 'tous' WHERE niveau = 'debutant' AND age_min <= 5");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer l'ancien ENUM (attention : perte de données si nouveaux niveaux utilisés)
        DB::statement("UPDATE cours SET niveau = 'debutant' WHERE niveau IN ('tous', 'prive', 'a_la_carte')");
        DB::statement("ALTER TABLE cours MODIFY COLUMN niveau ENUM('debutant', 'intermediaire', 'avance', 'competition') NOT NULL");
    }
};
