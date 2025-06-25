<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seminaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->enum('type', ['technique', 'kata', 'competition', 'arbitrage']);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('instructeur');
            $table->integer('capacite_max')->default(30);
            $table->decimal('prix', 8, 2)->nullable();
            $table->enum('niveau_requis', ['debutant', 'intermediaire', 'avance', 'tous_niveaux']);
            $table->boolean('inscription_ouverte')->default(true);
            $table->text('materiel_requis')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seminaires');
    }
};
