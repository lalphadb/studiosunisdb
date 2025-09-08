<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Modifier la colonne niveau avec les nouveaux enum
            $table->enum('niveau', [
                'tous',
                'debutant',
                'intermediaire',
                'avance',
                'prive',
                'competition',
                'a_la_carte',
            ])->default('tous')->change();
        });
    }

    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Retour aux anciens enum si nécessaire
            $table->enum('niveau', [
                'debutant',
                'intermediaire',
                'avance',
            ])->default('debutant')->change();
        });
    }
};
