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
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['inscription', 'mensuel', 'examen', 'stage', 'equipement', 'autre']);
            $table->decimal('montant', 8, 2);
            $table->string('description');
            $table->date('date_echeance');
            $table->date('date_paiement')->nullable();
            $table->enum('statut', ['en_attente', 'paye', 'en_retard', 'annule'])->default('en_attente');
            $table->enum('methode_paiement', ['especes', 'cheque', 'virement', 'carte', 'en_ligne'])->nullable();
            $table->string('reference_transaction')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Index pour performance
            $table->index(['date_echeance', 'statut']);
            $table->index(['membre_id', 'statut']);
            $table->index('date_paiement');
            $table->index(['type', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
