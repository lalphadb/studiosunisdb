<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('belts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color_hex')->nullable();
            $table->integer('order')->unique();
            $table->text('description')->nullable();
            $table->json('prerequis_techniques')->nullable();
            $table->integer('duree_minimum_mois')->default(3);
            $table->integer('presences_minimum')->default(24);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('belts');
    }
};
