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
        Schema::create('ceintures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->string('nom', 100);
            $table->string('nom_anglais', 100)->nullable();
            $table->string('couleur_principale', 50);
            $table->string('couleur_secondaire', 50)->nullable();
            $table->integer('ordre');
            $table->text('description')->nullable();
            $table->integer('mois_minimum')->default(0);
            $table->integer('cours_minimum')->default(0);
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            $table->unique(['ecole_id', 'ordre']);
            $table->index('couleur_principale');
            $table->index('actif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ceintures');
    }
};
