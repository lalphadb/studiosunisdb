<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cours_membres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->date('date_inscription');
            $table->date('date_fin')->nullable();
            $table->enum('statut_inscription', ['actif', 'inactif', 'suspendu', 'termine'])->default('actif');
            $table->decimal('prix_personnalise', 6, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Contrainte unicité + index performance
            $table->unique(['cours_id', 'membre_id']);
            $table->index(['statut_inscription', 'date_inscription']);
            $table->index('date_fin');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cours_membres');
    }
};
