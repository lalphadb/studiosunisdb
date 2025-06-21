<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Changé de membre_id à user_id
            $table->unsignedBigInteger('cours_id');
            $table->date('date_cours');
            $table->boolean('present')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'cours_id', 'date_cours']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cours_id')->references('id')->on('cours')->onDelete('cascade');
            
            $table->index(['cours_id', 'date_cours']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
