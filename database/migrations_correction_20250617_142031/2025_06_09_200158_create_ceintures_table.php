<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ceintures', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('couleur', 50);
            $table->integer('niveau')->unique();
            $table->integer('ordre_affichage')->unique();
            $table->text('description')->nullable();
            $table->text('pre_requis')->nullable();
            $table->integer('duree_minimum_mois')->default(3);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ceintures');
    }
};
