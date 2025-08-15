<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('course_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->enum('type_paiement', ['mensuel','seance','carte'])->default('mensuel');
            $table->json('tarif_personnalise')->nullable();
            $table->enum('statut', ['active','pause','annulee'])->default('active')->index();
            $table->timestamps();
            $table->unique(['course_id','member_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('course_registrations');
    }
};
