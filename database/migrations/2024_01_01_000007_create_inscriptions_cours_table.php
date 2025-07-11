<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cours_id')->constrained()->onDelete('restrict');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('restrict');
            $table->date('date_inscription');
            $table->date('date_fin')->nullable();
            $table->enum('statut', ['actif', 'suspendu', 'termine'])->default('actif');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'cours_id', 'date_inscription']);
            $table->index('statut');
            $table->index(['date_inscription', 'date_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions_cours');
    }
};
