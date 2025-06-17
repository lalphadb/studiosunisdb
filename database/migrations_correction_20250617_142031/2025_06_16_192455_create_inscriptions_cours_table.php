<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inscriptions_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->date('date_inscription');
            $table->enum('status', ['active', 'suspendue', 'annulee'])->default('active');
            $table->decimal('montant_paye', 8, 2)->default(0.00);
            $table->date('date_debut_facturation')->nullable();
            $table->date('date_fin_facturation')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['cours_id', 'membre_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inscriptions_cours');
    }
};
