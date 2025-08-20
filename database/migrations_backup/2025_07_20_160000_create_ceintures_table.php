<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ceintures', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Blanc, Jaune, Orange, Vert, Bleu, Marron, Noir 1er Dan, etc.
            $table->string('couleur_hex', 7)->nullable(); // #FFFFFF, #FFFF00, etc.
            $table->integer('ordre'); // 1, 2, 3, 4, etc.
            $table->text('description')->nullable();
            $table->json('prerequis_techniques')->nullable(); // Techniques requises
            $table->integer('duree_minimum_mois')->default(3); // Temps minimum avant examen
            $table->integer('presences_minimum')->default(24); // Nombre de présences requises
            $table->integer('age_minimum')->default(5); // Âge minimum
            $table->decimal('tarif_examen', 8, 2)->default(0); // Prix de l'examen
            $table->boolean('examen_requis')->default(true); // Si examen nécessaire
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            // Index pour performance
            $table->unique('ordre');
            $table->index('actif');
            $table->index(['actif', 'ordre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ceintures');
    }
};
