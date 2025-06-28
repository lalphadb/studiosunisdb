<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vérifier si la table n'existe pas déjà
        if (!Schema::hasTable('user_ceintures')) {
            Schema::create('user_ceintures', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('ceinture_id')->constrained()->onDelete('cascade');
                $table->foreignId('ecole_id')->constrained()->onDelete('cascade');
                $table->foreignId('instructeur_id')->constrained('users')->onDelete('cascade');
                $table->date('date_obtention');
                $table->string('examen_id')->nullable();
                $table->text('notes')->nullable();
                $table->boolean('valide')->default(true);
                $table->timestamps();
                
                $table->index(['user_id', 'date_obtention']);
                $table->index(['ecole_id', 'date_obtention']);
                $table->index('examen_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_ceintures');
    }
};
