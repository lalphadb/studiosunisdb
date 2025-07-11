<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cours_horaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->tinyInteger('jour'); // 0=Dimanche, 6=Samedi
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('salle', 50)->nullable();
            $table->foreignId('instructeur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            $table->index(['cours_id', 'jour']);
            $table->index('instructeur_id');
            $table->index('actif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cours_horaires');
    }
};
