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
            $table->string('nom');
            $table->text('description')->nullable();
            $table->foreignId('instructeur_id')->constrained('users')->onDelete('cascade');
            $table->enum('niveau', ['debutant', 'intermediaire', 'avance', 'competiteur', 'tous'])->default('debutant');
            $table->enum('type_cours', ['regulier', 'privé', 'stage', 'competition', 'special'])->default('regulier');
            $table->enum('jour_semaine', ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->integer('duree_minutes')->virtualAs('TIMESTAMPDIFF(MINUTE, heure_debut, heure_fin)');
            $table->integer('capacite_max')->default(20);
            $table->decimal('prix_mensuel', 6, 2)->default(0);
            $table->boolean('actif')->default(true);
            $table->text('notes')->nullable();
            $table->json('materiel_requis')->nullable();
            $table->timestamps();

            // Index pour performances
            $table->index(['jour_semaine', 'heure_debut']);
            $table->index(['instructeur_id', 'actif']);
            $table->index('niveau');
            $table->index('type_cours');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
