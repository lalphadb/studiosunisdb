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
            $table->foreignId('session_cours_id')->constrained('session_cours')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->enum('status', ['present', 'absent', 'retard', 'excuse'])->default('present');
            $table->datetime('heure_arrivee')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['session_cours_id', 'user_id']);
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
