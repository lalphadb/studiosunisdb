<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sessions_cours', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // "Hiver 2025", "Printemps 2025"
            $table->text('description')->nullable();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['actif', 'inactif', 'termine'])->default('actif');
            $table->decimal('prix_base', 8, 2)->default(0);
            $table->boolean('inscription_ouverte')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions_cours');
    }
};
