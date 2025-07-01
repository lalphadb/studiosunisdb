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
        if (!Schema::hasTable('sessions_cours')) {
            Schema::create('sessions_cours', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
                $table->string('nom'); // "Automne 2025", "Hiver 2026"
                $table->date('date_debut');
                $table->date('date_fin');
                $table->boolean('actif')->default(true);
                $table->text('description')->nullable();
                $table->integer('ordre')->default(0); // Pour tri chronologique
                $table->timestamps();

                // Index pour performance
                $table->index(['ecole_id', 'actif']);
                $table->index(['ecole_id', 'date_debut']);
                $table->index(['ecole_id', 'ordre']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions_cours');
    }
};
