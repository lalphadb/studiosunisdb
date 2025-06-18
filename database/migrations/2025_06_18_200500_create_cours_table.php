<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->string('nom');
            $table->text('description')->nullable();
            $table->enum('niveau', ['debutant', 'intermediaire', 'avance', 'tous_niveaux']);
            $table->integer('capacite_max')->default(20);
            $table->decimal('prix', 8, 2)->nullable();
            $table->integer('duree_minutes')->default(60);
            $table->string('instructeur')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cours');
    }
};
