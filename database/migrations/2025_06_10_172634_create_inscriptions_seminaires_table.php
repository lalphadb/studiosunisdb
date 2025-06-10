<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inscriptions_seminaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seminaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->date('date_inscription');
            $table->enum('statut', ['confirmee', 'liste_attente', 'annulee'])->default('confirmee');
            $table->decimal('montant_paye', 8, 2)->nullable();
            $table->boolean('certificat_obtenu')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['seminaire_id', 'membre_id']);
            $table->index(['membre_id', 'date_inscription']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inscriptions_seminaires');
    }
};
