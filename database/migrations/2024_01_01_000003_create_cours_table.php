<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->string('nom', 200);
            $table->string('code', 20);
            $table->text('description')->nullable();
            $table->enum('type', ['regulier', 'special', 'prive'])->default('regulier');
            $table->string('niveau', 50)->nullable();
            $table->integer('duree_minutes')->default(60);
            $table->integer('capacite_max')->nullable();
            $table->decimal('prix_mensuel', 8, 2)->nullable();
            $table->decimal('prix_seance', 8, 2)->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            $table->unique(['ecole_id', 'code']);
            $table->index('type');
            $table->index('actif');
            $table->index('niveau');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
