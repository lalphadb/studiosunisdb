<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membre_ceintures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Changé de membre_id à user_id
            $table->unsignedBigInteger('ceinture_id');
            $table->date('date_obtention');
            $table->string('examinateur')->nullable();
            $table->text('commentaires')->nullable();
            $table->boolean('valide')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ceinture_id')->references('id')->on('ceintures')->onDelete('cascade');
            
            $table->index(['user_id', 'date_obtention']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membre_ceintures');
    }
};
