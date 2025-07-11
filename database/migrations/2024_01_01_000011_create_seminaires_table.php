<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->string('nom', 200);
            $table->text('description')->nullable();
            $table->datetime('date_debut');
            $table->datetime('date_fin');
            $table->string('lieu', 255)->nullable();
            $table->foreignId('instructeur_principal_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('prix', 10, 2)->nullable();
            $table->integer('capacite_max')->nullable();
            $table->enum('statut', ['planifie', 'en_cours', 'termine', 'annule'])->default('planifie');
            $table->boolean('ouvert_externe')->default(false);
            $table->timestamps();
            
            $table->index(['ecole_id', 'date_debut']);
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminaires');
    }
};
