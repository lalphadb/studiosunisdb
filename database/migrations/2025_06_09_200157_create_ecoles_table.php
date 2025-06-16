<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ecoles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('adresse')->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('province', 50)->default('Quebec');
            $table->string('code_postal', 10)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('site_web')->nullable();
            $table->string('directeur')->nullable();
            $table->integer('capacite_max')->default(100);
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ecoles');
    }
};
