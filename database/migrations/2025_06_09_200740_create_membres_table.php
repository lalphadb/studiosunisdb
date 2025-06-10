<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('membres')) {
            Schema::create('membres', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ecole_id');
                $table->string('numero_membre', 20)->unique()->nullable();
                $table->string('prenom', 100);
                $table->string('nom', 100);
                $table->date('date_naissance')->nullable();
                $table->string('telephone', 20)->nullable();
                $table->string('email')->nullable();
                $table->text('adresse')->nullable();
                $table->string('ville', 100)->nullable();
                $table->string('code_postal', 10)->nullable();
                $table->string('contact_urgence')->nullable();
                $table->string('telephone_urgence', 20)->nullable();
                $table->date('date_inscription');
                $table->date('date_fin_inscription')->nullable();
                $table->enum('statut', ['actif', 'inactif', 'suspendu', 'expire'])->default('actif');
                $table->enum('type_membre', ['enfant', 'adolescent', 'adulte', 'senior'])->default('adulte');
                $table->enum('niveau_experience', ['debutant', 'intermediaire', 'avance', 'expert'])->default('debutant');
                $table->unsignedBigInteger('ceinture_actuelle_id')->nullable();
                $table->text('notes')->nullable();
                $table->string('photo_url', 500)->nullable();
                $table->boolean('consentement_photo')->default(false);
                $table->boolean('consentement_email')->default(true);
                $table->timestamps();
                
                $table->foreign('ecole_id')->references('id')->on('ecoles')->onDelete('cascade');
                $table->foreign('ceinture_actuelle_id')->references('id')->on('ceintures')->onDelete('set null');
                
                $table->index(['ecole_id']);
                $table->index(['statut']);
                $table->index(['nom', 'prenom']);
                $table->index(['numero_membre']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('membres');
    }
};
