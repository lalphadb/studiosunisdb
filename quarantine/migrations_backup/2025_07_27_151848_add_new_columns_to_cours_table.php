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
            // Nouvelles colonnes pour le système de cours amélioré
            $table->enum('saison', ['automne', 'hiver', 'printemps', 'ete'])->default('automne')->after('niveau');
            $table->integer('age_minimum')->nullable()->after('saison');
            $table->integer('capacite_max')->default(15)->after('age_minimum');
            $table->decimal('tarif_seance', 8, 2)->nullable()->after('tarif_mensuel');
            $table->decimal('tarif_carte', 8, 2)->nullable()->after('tarif_seance');
            $table->enum('statut', ['actif', 'inactif', 'complet', 'annule'])->default('actif')->after('tarif_carte');
            $table->boolean('visible_inscription')->default(true)->after('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->dropColumn([
                'saison',
                'age_minimum',
                'capacite_max',
                'tarif_seance',
                'tarif_carte',
                'statut',
                'visible_inscription',
            ]);
        });
    }
};
