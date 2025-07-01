<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions_historique', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ecole_id')->constrained()->onDelete('cascade');
            $table->foreignId('session_id')->constrained('sessions_cours')->onDelete('cascade');
            $table->json('cours_choisis'); // [{cours_id, horaires_ids[], frequence}]
            $table->decimal('montant_total', 8, 2);
            $table->enum('statut', ['active', 'terminee', 'annulee'])->default('active');
            $table->timestamp('date_inscription');
            $table->timestamps();

            $table->index(['user_id', 'ecole_id']);
            $table->index(['session_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions_historique');
    }
};
