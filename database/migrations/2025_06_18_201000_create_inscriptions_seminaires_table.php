<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions_seminaires', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Changé de membre_id à user_id
            $table->unsignedBigInteger('seminaire_id');
            $table->date('date_inscription')->default(now()->format('Y-m-d'));
            $table->enum('statut', ['inscrit', 'confirme', 'present', 'absent', 'annule'])->default('inscrit');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'seminaire_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('seminaire_id')->references('id')->on('seminaires')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions_seminaires');
    }
};
