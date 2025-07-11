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
        Schema::create('ecoles', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 200);
            $table->string('code', 20)->unique();
            $table->string('adresse', 255)->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('province', 50)->default('QC');
            $table->string('code_postal', 10)->nullable();
            $table->string('pays', 50)->default('Canada');
            $table->string('telephone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('site_web', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('logo', 255)->nullable();
            $table->json('config')->nullable();
            $table->boolean('actif')->default(true);
            $table->date('date_ouverture')->nullable();
            $table->timestamps();
            
            $table->index('code');
            $table->index('actif');
            $table->index('ville');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecoles');
    }
};
