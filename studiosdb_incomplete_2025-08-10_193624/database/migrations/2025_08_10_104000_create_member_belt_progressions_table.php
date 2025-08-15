<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('member_belt_progressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('belt_id')->constrained('belts')->cascadeOnDelete();
            $table->enum('etat', ['compte_rendu','recommandation','examen','reussi','echec'])->default('compte_rendu')->index();
            $table->date('date_recommandation')->nullable();
            $table->date('date_examen')->nullable();
            $table->foreignId('recommande_par')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('evalue_par')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['member_id','etat']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('member_belt_progressions');
    }
};
