<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_id')->constrained('schools')->cascadeOnDelete();
            $table->string('nom');
            $table->enum('saison', ['automne','hiver','printemps','ete'])->index();
            $table->string('niveau')->nullable(); // ex: Junior, Intermédiaire…
            $table->unsignedTinyInteger('age_min')->nullable();
            $table->unsignedTinyInteger('age_max')->nullable();
            $table->unsignedSmallInteger('capacite')->nullable();
            $table->json('tarification')->nullable(); // {type:"mensuel|seance|carte10", ...}
            $table->softDeletes();
            $table->timestamps();

            $table->index(['ecole_id','saison']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('courses');
    }
};
