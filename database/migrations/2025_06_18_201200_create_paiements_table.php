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
            $table->unsignedBigInteger('user_id'); // Changé de membre_id à user_id
            $table->unsignedBigInteger('ecole_id');
            $table->unsignedBigInteger('processed_by_user_id')->nullable(); // User qui a traité le paiement
            $table->string('reference_interne')->unique()->nullable();
            $table->string('type_paiement')->default('interac');
            $table->enum('motif', ['session_automne', 'session_hiver', 'session_printemps', 'session_ete', 'seminaire', 'examen_ceinture', 'equipement', 'autre']);
            $table->string('description')->nullable();
            $table->decimal('montant', 10, 2);
            $table->decimal('frais', 10, 2)->default(0);
            $table->decimal('montant_net', 10, 2);
            $table->string('email_expediteur')->nullable();
            $table->string('nom_expediteur')->nullable();
            $table->string('reference_interac')->nullable();
            $table->text('message_interac')->nullable();
            $table->enum('statut', ['en_attente', 'recu', 'valide', 'rejete', 'rembourse'])->default('en_attente');
            $table->timestamp('date_facture')->nullable();
            $table->timestamp('date_echeance')->nullable();
            $table->timestamp('date_reception')->nullable();
            $table->timestamp('date_validation')->nullable();
            $table->string('periode_facturation')->nullable();
            $table->year('annee_fiscale')->default(date('Y'));
            $table->boolean('recu_fiscal_emis')->default(false);
            $table->json('metadonnees')->nullable();
            $table->text('notes_admin')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ecole_id')->references('id')->on('ecoles')->onDelete('cascade');
            $table->foreign('processed_by_user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['ecole_id', 'statut']);
            $table->index(['user_id', 'created_at']);
            $table->index('reference_interne');
            $table->index(['type_paiement', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
