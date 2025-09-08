<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('progression_ceintures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->foreignId('ceinture_actuelle_id')->constrained('ceintures');
            $table->foreignId('ceinture_cible_id')->constrained('ceintures');
            $table->foreignId('instructeur_id')->constrained('users');
            $table->enum('statut', [
                'eligible', 'candidat', 'examen_planifie',
                'examen_reussi', 'certifie', 'echec',
            ])->default('eligible');
            $table->date('date_eligibilite');
            $table->date('date_examen')->nullable();
            $table->text('notes_instructeur')->nullable();
            $table->json('evaluation_techniques')->nullable();
            $table->integer('note_finale')->nullable(); // /100
            $table->text('recommandations')->nullable();
            $table->timestamps();

            // Index pour les requêtes du dashboard
            $table->index(['date_examen', 'statut']);
            $table->index(['membre_id', 'statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progression_ceintures');
    }
};
