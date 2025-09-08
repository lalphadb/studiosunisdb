<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('prenom');
            $table->string('nom');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->date('date_naissance');
            $table->enum('sexe', ['M', 'F', 'Autre'])->default('Autre');

            // Adresse
            $table->text('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('province')->default('QC');

            // Contact urgence
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_telephone')->nullable();
            $table->string('contact_urgence_relation')->nullable();

            // Statut et progression
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            $table->foreignId('ceinture_actuelle_id')->nullable()->constrained('ceintures');
            $table->date('date_inscription');
            $table->date('date_derniere_presence')->nullable();

            // Informations médicales
            $table->text('notes_medicales')->nullable();
            $table->json('allergies')->nullable();
            $table->json('medicaments')->nullable();

            // Consentements (Loi 25)
            $table->boolean('consentement_photos')->default(false);
            $table->boolean('consentement_communications')->default(true);
            $table->timestamp('date_consentement')->nullable();

            // Relations
            $table->foreignId('family_id')->nullable()->constrained()->nullOnDelete();

            // Métadonnées
            $table->json('champs_personnalises')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index pour performances
            $table->index(['statut', 'date_derniere_presence']);
            $table->index(['prenom', 'nom']);
            $table->index('date_inscription');
            // Fulltext uniquement si driver le supporte (MySQL/MariaDB)
            $driver = Schema::getConnection()->getDriverName();
            if (in_array($driver, ['mysql', 'mariadb'])) {
                try {
                    $table->fullText(['prenom', 'nom', 'email']);
                } catch (\Throwable $e) { /* ignore */
                }
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membres');
    }
};
