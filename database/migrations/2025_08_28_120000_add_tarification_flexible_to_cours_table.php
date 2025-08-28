<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Système tarification flexible - ORDRE CORRECT
     */
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // 1. D'ABORD créer toutes les nouvelles colonnes
            $table->string('type_tarif')->default('mensuel')->after('tarif_mensuel');
            $table->decimal('montant', 8, 2)->default(0)->after('type_tarif');
            $table->text('details_tarif')->nullable()->after('montant');
        });
        
        // 2. ENSUITE migrer les données existantes : tarif_mensuel → montant
        \DB::statement('UPDATE cours SET montant = tarif_mensuel WHERE tarif_mensuel IS NOT NULL');
        
        // 3. FINALEMENT modifier les colonnes existantes
        Schema::table('cours', function (Blueprint $table) {
            // Rendre age_max nullable si pas déjà fait
            if (Schema::hasColumn('cours', 'age_max')) {
                $table->integer('age_max')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Restaurer données avant suppression
            \DB::statement('UPDATE cours SET tarif_mensuel = montant WHERE type_tarif = "mensuel" AND montant IS NOT NULL');
            
            // Supprimer colonnes ajoutées
            $table->dropColumn(['type_tarif', 'montant', 'details_tarif']);
            
            // Remettre age_max non nullable si nécessaire
            if (Schema::hasColumn('cours', 'age_max')) {
                $table->integer('age_max')->nullable(false)->change();
            }
        });
    }
};
