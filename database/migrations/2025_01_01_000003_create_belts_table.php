<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('belts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Blanc, Jaune, Orange, Vert, etc.
            $table->string('color_hex')->nullable();
            $table->integer('order'); // 1, 2, 3, 4, etc.
            $table->text('description')->nullable();
            $table->json('technical_requirements')->nullable();
            $table->integer('minimum_duration_months')->default(3);
            $table->integer('minimum_attendances')->default(24);
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->unique('order');
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('belts');
    }
};
