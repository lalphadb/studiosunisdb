<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vérifier si la table n'existe pas déjà
        if (!Schema::hasTable('ceintures')) {
            Schema::create('ceintures', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->string('couleur', 7);
                $table->integer('ordre')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
                
                $table->index('ordre');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ceintures');
    }
};
