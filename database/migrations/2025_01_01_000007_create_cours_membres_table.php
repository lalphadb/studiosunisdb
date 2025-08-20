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
            $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->date('date_inscription');
            $table->date('date_fin')->nullable();
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['cours_id', 'membre_id']);
            $table->index(['statut', 'date_inscription']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cours_membres');
    }
};
