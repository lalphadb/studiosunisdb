<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inscriptions_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->date('date_inscription')->default(now());
            $table->enum('statut', ['active', 'suspendue', 'terminee', 'annulee'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['membre_id', 'cours_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inscriptions_cours');
    }
};
