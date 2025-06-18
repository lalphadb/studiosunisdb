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
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Admin qui valide
            
            // Informations paiement
            $table->string('reference_interne')->unique(); // SU-STEMI-2025-001
            $table->string('type_paiement')->default('interac'); // interac, comptant, cheque
            $table->enum('motif', [
                'cotisation_mensuelle',
                'cotisation_annuelle', 
                'seminaire',
                'examen_ceinture',
                'equipement',
                'autre'
            ]);
            $table->string('description')->nullable();
            
            // Montants
            $table->decimal('montant', 10, 2);
            $table->decimal('frais', 10, 2)->default(0); // Frais Interac si applicable
            $table->decimal('montant_net', 10, 2); // Montant - frais
            
            // Informations Interac
            $table->string('email_expediteur')->nullable();
            $table->string('nom_expediteur')->nullable();
            $table->string('reference_interac')->nullable(); // Référence du virement
            $table->text('message_interac')->nullable();
            
            // Workflow et validation
            $table->enum('statut', [
                'en_attente',      // Facture envoyée
                'recu',            // Virement reçu, en validation
                'valide',          // Validé par admin
                'rejete',          // Rejeté (erreur montant, etc.)
                'rembourse'        // Remboursé
            ])->default('en_attente');
            
            // Dates importantes
            $table->timestamp('date_facture')->nullable();
            $table->timestamp('date_echeance')->nullable();
            $table->timestamp('date_reception')->nullable();
            $table->timestamp('date_validation')->nullable();
            
            // Informations comptables
            $table->string('periode_facturation')->nullable(); // 2025-06, 2025-Q1
            $table->year('annee_fiscale')->default(date('Y'));
            $table->boolean('recu_fiscal_emis')->default(false);
            
            // Métadonnées
            $table->json('metadonnees')->nullable(); // Infos supplémentaires
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
