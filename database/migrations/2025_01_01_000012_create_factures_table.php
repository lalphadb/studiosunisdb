<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('numero_facture')->unique();
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('family_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date_emission');
            $table->date('date_echeance');
            $table->decimal('montant_ht', 8, 2);
            $table->decimal('montant_tps', 8, 2)->default(0);
            $table->decimal('montant_tvq', 8, 2)->default(0);
            $table->decimal('montant_total', 8, 2);
            $table->enum('statut', ['brouillon', 'envoyee', 'payee', 'en_retard', 'annulee']);
            $table->json('details_lignes'); // Détail des services facturés
            $table->text('notes')->nullable();
            $table->boolean('envoi_email')->default(false);
            $table->timestamp('date_envoi')->nullable();
            $table->timestamps();
            
            $table->index(['statut', 'date_echeance']);
            $table->index('membre_id');
            $table->index('date_emission');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
