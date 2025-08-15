<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('course_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->enum('statut', ['brouillon','actif','complet','archive'])->default('brouillon')->index();
            $table->date('debut')->nullable();
            $table->date('fin')->nullable();
            $table->json('meta')->nullable(); // couleurs/icones/etc
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('course_sessions');
    }
};
