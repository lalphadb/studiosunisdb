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
            $table->string('nom');
            $table->string('intervenant')->nullable();
            $table->enum('type_seminaire', ['technique', 'kata', 'competition', 'arbitrage', 'self_defense', 'armes', 'meditation', 'histoire', 'autre'])->nullable();
            $table->string('niveau_cible')->nullable();
            $table->text('pre_requis')->nullable();
            $table->boolean('ouvert_toutes_ecoles')->default(true);
            $table->text('materiel_requis')->nullable();
            $table->text('description')->nullable();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('lieu')->nullable();
            $table->decimal('prix', 8, 2)->nullable();
            $table->integer('capacite_max')->default(50);
            $table->enum('statut', ['actif', 'complet', 'annule'])->default('actif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seminaires');
    }
};
