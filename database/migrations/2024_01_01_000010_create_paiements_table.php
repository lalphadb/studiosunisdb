<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('restrict');
            $table->decimal('montant', 10, 2);
            $table->date('date_paiement');
            $table->enum('type_paiement', ['mensualite', 'inscription', 'seminaire', 'equipement', 'autre']);
            $table->enum('methode_paiement', ['especes', 'cheque', 'carte', 'virement', 'autre']);
            $table->string('reference_paiement', 100)->nullable();
            $table->enum('statut', ['en_attente', 'complete', 'annule', 'rembourse'])->default('complete');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'date_paiement']);
            $table->index('type_paiement');
            $table->index('statut');
            $table->index('date_paiement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
