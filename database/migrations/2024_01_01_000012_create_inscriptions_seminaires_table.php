<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions_seminaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seminaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('restrict');
            $table->date('date_inscription');
            $table->enum('statut', ['inscrit', 'confirme', 'present', 'absent', 'annule'])->default('inscrit');
            $table->foreignId('paiement_id')->nullable()->constrained()->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['seminaire_id', 'user_id']);
            $table->index('statut');
            $table->index('date_inscription');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions_seminaires');
    }
};
