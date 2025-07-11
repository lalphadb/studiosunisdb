<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->datetime('date_debut');
            $table->datetime('date_fin');
            $table->foreignId('instructeur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('salle', 50)->nullable();
            $table->integer('capacite_max')->nullable();
            $table->enum('statut', ['planifie', 'en_cours', 'termine', 'annule'])->default('planifie');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['cours_id', 'date_debut']);
            $table->index('statut');
            $table->index('instructeur_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_cours');
    }
};
