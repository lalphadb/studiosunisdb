<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Correction contrainte tarif_mensuel
     */
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Rendre tarif_mensuel nullable pour compatibilité avec nouveau système
            $table->decimal('tarif_mensuel', 8, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Restaurer contrainte NOT NULL
            $table->decimal('tarif_mensuel', 8, 2)->nullable(false)->change();
        });
    }
};
