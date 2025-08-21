<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('ceinture_id')->constrained('belts')->onDelete('cascade');
            $table->foreignId('instructeur_id')->constrained('users');
            $table->date('date_examen');
            $table->time('heure_examen')->nullable();
            $table->enum('statut', ['planifie', 'en_cours', 'reussi', 'echec', 'reporte'])->default('planifie');
            $table->integer('note_technique')->nullable(); // /100
            $table->integer('note_physique')->nullable(); // /100
            $table->integer('note_kata')->nullable(); // /100
            $table->integer('note_finale')->nullable(); // /100
            $table->text('commentaires')->nullable();
            $table->text('points_forts')->nullable();
            $table->text('points_amelioration')->nullable();
            $table->boolean('certificat_emis')->default(false);
            $table->date('date_certificat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examens');
    }
};
