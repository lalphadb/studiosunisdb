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
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('seminaire_id')->constrained('seminaires')->onDelete('cascade');
            $table->date('date_inscription')->default(now());
            $table->enum('statut', ['inscrit', 'confirme', 'present', 'absent', 'annule'])->default('inscrit');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['membre_id', 'seminaire_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inscriptions_seminaires');
    }
};
