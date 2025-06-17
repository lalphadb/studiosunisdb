<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cours')) {
            Schema::create('cours', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ecole_id');
                $table->string('nom');
                $table->text('description')->nullable();
                $table->enum('type_cours', ['regulier', 'specialise', 'competition', 'examen'])->default('regulier');
                $table->string('niveau_requis', 100)->nullable();
                $table->integer('age_min')->default(5);
                $table->integer('age_max')->default(99);
                $table->integer('capacite_max')->default(20);
                $table->integer('duree_minutes')->default(60);
                $table->decimal('prix', 8, 2)->nullable();
                $table->unsignedBigInteger('instructeur_principal_id')->nullable();
                $table->unsignedBigInteger('instructeur_assistant_id')->nullable();
                $table->enum('statut', ['actif', 'inactif', 'complet', 'annule'])->default('actif');
                $table->string('salle', 100)->nullable();
                $table->text('materiel_requis')->nullable();
                $table->text('objectifs')->nullable();
                $table->timestamps();

                $table->foreign('ecole_id')->references('id')->on('ecoles')->onDelete('cascade');
                $table->foreign('instructeur_principal_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('instructeur_assistant_id')->references('id')->on('users')->onDelete('set null');

                $table->index(['ecole_id']);
                $table->index(['statut']);
                $table->index(['instructeur_principal_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
