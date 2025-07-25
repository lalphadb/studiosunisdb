<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            
            // Base
            $table->string('nom', 150);
            $table->string('code_cours', 20)->unique();
            $table->text('description')->nullable();
            $table->text('objectifs_cours')->nullable();
            
            // Instructeurs
            $table->foreignId('instructeur_principal_id')->constrained('users');
            $table->foreignId('instructeur_assistant_id')->nullable()->constrained('users');
            $table->json('instructeurs_suppleants')->nullable();
            
            // Classification
            $table->enum('type_cours', [
                'regulier', 'intensif', 'prive', 'stage', 
                'competition', 'demonstration', 'rattrapage'
            ])->default('regulier');
            
            $table->enum('niveau', [
                'initiation', 'debutant', 'debutant_avance',
                'intermediaire', 'intermediaire_avance', 
                'avance', 'expert', 'competition', 'mixte'
            ])->default('debutant');
            
            $table->json('ceintures_acceptees')->nullable();
            
            // Âge et capacité
            $table->unsignedTinyInteger('age_minimum')->default(5);
            $table->unsignedTinyInteger('age_maximum')->default(99);
            $table->unsignedTinyInteger('places_maximum')->default(20);
            $table->unsignedTinyInteger('places_minimum')->default(3);
            
            // Planning
            $table->enum('jour_semaine', [
                'lundi', 'mardi', 'mercredi', 'jeudi', 
                'vendredi', 'samedi', 'dimanche'
            ]);
            $table->time('heure_debut');
            $table->time('heure_fin');
            
            // Période
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->boolean('recurrent')->default(true);
            $table->json('dates_exceptions')->nullable();
            $table->json('dates_rattrapages')->nullable();
            
            // Lieu
            $table->string('lieu', 100)->default('Dojo Principal');
            $table->string('salle_specifique', 50)->nullable();
            $table->json('equipements_requis')->nullable();
            $table->json('equipements_fournis')->nullable();
            
            // Tarifs
            $table->decimal('tarif_mensuel', 8, 2)->default(0.00);
            $table->decimal('tarif_inscription', 8, 2)->default(0.00);
            $table->decimal('tarif_cours_unique', 8, 2)->nullable();
            $table->json('tarifs_speciaux')->nullable();
            
            // Programme
            $table->json('programme_technique')->nullable();
            $table->json('programme_kata')->nullable();
            $table->json('programme_physique')->nullable();
            $table->json('competences_visees')->nullable();
            
            // Gestion
            $table->boolean('actif')->default(true);
            $table->boolean('inscriptions_ouvertes')->default(true);
            $table->enum('statut', [
                'planifie', 'actif', 'suspendu', 'annule', 
                'termine', 'reporte'
            ])->default('planifie');
            
            $table->text('notes_internes')->nullable();
            $table->json('regles_particulieres')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index(['jour_semaine', 'heure_debut', 'actif']);
            $table->index(['instructeur_principal_id', 'actif']);
            $table->index(['niveau', 'type_cours', 'actif']);
            $table->index(['date_debut', 'date_fin']);
            $table->index('statut');
        });
        
        Schema::create('cours_inscriptions', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->foreignId('inscrit_par_id')->constrained('users');
            
            $table->date('date_inscription');
            $table->date('date_debut_effectif')->nullable();
            $table->date('date_fin_prevue')->nullable();
            $table->date('date_fin_effective')->nullable();
            
            $table->enum('statut', [
                'en_attente', 'confirme', 'actif', 'suspendu', 
                'termine', 'abandonne', 'transfere'
            ])->default('en_attente');
            
            $table->unsignedBigInteger('niveau_entree_id')->nullable();
            $table->json('objectifs_personnalises')->nullable();
            $table->text('notes_pedagogiques')->nullable();
            $table->json('adaptations_necessaires')->nullable();
            
            $table->decimal('tarif_applique', 8, 2);
            $table->json('remises_appliquees')->nullable();
            $table->enum('modalite_paiement', [
                'mensuel', 'trimestriel', 'semestriel', 
                'annuel', 'cours_unique'
            ])->default('mensuel');
            
            $table->json('evaluations_periodiques')->nullable();
            $table->unsignedTinyInteger('niveau_satisfaction')->nullable();
            $table->text('commentaires_membre')->nullable();
            $table->text('commentaires_parent')->nullable();
            
            $table->timestamps();
            
            $table->unique(['cours_id', 'membre_id']);
            $table->index(['membre_id', 'statut']);
            $table->index(['cours_id', 'statut']);
            $table->index('date_inscription');
            
            $table->foreign('niveau_entree_id')->references('id')->on('ceintures')->nullOnDelete();
        });
        
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructeur_id')->constrained('users');
            $table->foreignId('marquee_par_id')->nullable()->constrained('users');
            
            $table->date('date_cours');
            $table->time('heure_debut_cours');
            $table->time('heure_fin_cours');
            $table->time('heure_arrivee')->nullable();
            $table->time('heure_depart')->nullable();
            
            $table->enum('statut', [
                'present', 'absent', 'retard', 'parti_tot',
                'excuse', 'maladie', 'blessure', 'vacances',
                'suspension', 'autre'
            ])->default('present');
            
            $table->text('raison_absence')->nullable();
            $table->boolean('justifiee')->default(false);
            
            $table->unsignedTinyInteger('niveau_participation')->nullable();
            $table->unsignedTinyInteger('niveau_technique')->nullable();
            $table->unsignedTinyInteger('niveau_effort')->nullable();
            $table->unsignedTinyInteger('niveau_attitude')->nullable();
            
            $table->json('techniques_travaillees')->nullable();
            $table->json('points_forts_seance')->nullable();
            $table->json('points_amelioration')->nullable();
            $table->text('commentaires_instructeur')->nullable();
            $table->text('observations_comportement')->nullable();
            
            $table->boolean('incident_medical')->default(false);
            $table->text('details_incident')->nullable();
            $table->boolean('parents_prevus')->default(false);
            
            $table->enum('mode_marquage', [
                'manuel', 'tablette', 'qr_code', 'badge',
                'reconnaissance_faciale', 'import'
            ])->default('manuel');
            
            $table->timestamp('marquee_a')->nullable();
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            $table->unique(['cours_id', 'membre_id', 'date_cours']);
            $table->index(['date_cours', 'statut']);
            $table->index(['membre_id', 'date_cours']);
            $table->index(['instructeur_id', 'date_cours']);
            $table->index(['statut', 'justifiee']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
        Schema::dropIfExists('cours_inscriptions');
        Schema::dropIfExists('cours');
    }
};
