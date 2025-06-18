<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Informations paiement
            $table->string('reference_interne')->nullable()->unique();
            $table->string('type_paiement')->default('interac');
            $table->enum('motif', [
                'session_automne',
                'session_hiver',
                'session_printemps', 
                'session_ete',
                'seminaire',
                'examen_ceinture',
                'equipement',
                'autre'
            ]);
            $table->string('description')->nullable();
            
            // Montants
            $table->decimal('montant', 10, 2);
            $table->decimal('frais', 10, 2)->default(0);
            $table->decimal('montant_net', 10, 2);
            
            // Informations Interac
            $table->string('email_expediteur')->nullable();
            $table->string('nom_expediteur')->nullable();
            $table->string('reference_interac')->nullable();
            $table->text('message_interac')->nullable();
            
            // Workflow et validation
            $table->enum('statut', [
                'en_attente',
                'recu',
                'valide',
                'rejete',
                'rembourse'
            ])->default('en_attente');
            
            // Dates importantes
            $table->timestamp('date_facture')->nullable();
            $table->timestamp('date_echeance')->nullable();
            $table->timestamp('date_reception')->nullable();
            $table->timestamp('date_validation')->nullable();
            
            // Informations comptables
            $table->string('periode_facturation')->nullable();
            $table->year('annee_fiscale')->default(date('Y'));
            $table->boolean('recu_fiscal_emis')->default(false);
            
            // Métadonnées
            $table->json('metadonnees')->nullable();
            $table->text('notes_admin')->nullable();
            
            $table->timestamps();
            
            // Index pour performance
            $table->index(['ecole_id', 'statut']);
            $table->index(['membre_id', 'created_at']);
            $table->index(['reference_interne']);
            $table->index(['type_paiement', 'statut']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
};
