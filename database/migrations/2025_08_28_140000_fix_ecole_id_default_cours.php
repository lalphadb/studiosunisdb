<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Fix ecole_id default pour mono-école
     */
    public function up(): void
    {
        // Étape 1: Rendre temporairement ecole_id nullable
        Schema::table('cours', function (Blueprint $table) {
            $table->unsignedBigInteger('ecole_id')->nullable()->change();
        });
        
        // Étape 2: Assigner ecole_id par défaut (mono-école)
        $defaultEcoleId = 1; // ID école par défaut pour mono-école
        
        // Vérifier s'il y a une table ecoles et prendre le premier ID
        if (Schema::hasTable('ecoles')) {
            $premiereEcole = DB::table('ecoles')->first();
            if ($premiereEcole) {
                $defaultEcoleId = $premiereEcole->id;
            }
        }
        
        // Mettre à jour tous les cours sans ecole_id
        DB::table('cours')
            ->whereNull('ecole_id')
            ->update(['ecole_id' => $defaultEcoleId]);
        
        // Étape 3: Rendre ecole_id obligatoire avec valeur par défaut
        Schema::table('cours', function (Blueprint $table) use ($defaultEcoleId) {
            $table->unsignedBigInteger('ecole_id')->default($defaultEcoleId)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Retirer la valeur par défaut et rendre nullable
            $table->unsignedBigInteger('ecole_id')->nullable()->change();
        });
    }
};
