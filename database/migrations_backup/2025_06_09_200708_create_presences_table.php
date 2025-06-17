<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('membre_id');
            $table->unsignedBigInteger('cours_id');
            $table->date('session_date');
            $table->time('heure_arrivee')->nullable();
            $table->time('heure_depart')->nullable();
            $table->enum('statut', ['present', 'absent', 'retard', 'excuse', 'maladie'])->default('present');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('prise_par_user_id')->nullable();
            $table->enum('methode_pointage', ['manuel', 'qrcode', 'nfc', 'facial'])->default('manuel');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->foreign('membre_id')->references('id')->on('membres')->onDelete('cascade');
            $table->foreign('cours_id')->references('id')->on('cours')->onDelete('cascade');
            $table->foreign('prise_par_user_id')->references('id')->on('users')->onDelete('set null');

            $table->unique(['membre_id', 'cours_id', 'session_date']);
            $table->index(['membre_id']);
            $table->index(['cours_id']);
            $table->index(['session_date']);
            $table->index(['statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
