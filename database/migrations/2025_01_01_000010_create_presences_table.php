<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('instructeur_id')->constrained('users');
            $table->date('date_cours');
            $table->enum('statut', ['present', 'absent', 'retard', 'excuse'])->default('present');
            $table->time('heure_arrivee')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('validation_parent')->default(false);
            $table->timestamps();

            // Contrainte unicité + index performance
            $table->unique(['cours_id', 'membre_id', 'date_cours']);
            $table->index(['date_cours', 'statut']);
            $table->index('instructeur_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
