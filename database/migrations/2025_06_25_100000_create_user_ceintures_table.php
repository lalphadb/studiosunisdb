<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_ceintures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ceinture_id')->constrained('ceintures')->onDelete('cascade');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->foreignId('instructeur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('examen_id')->nullable();
            $table->date('date_obtention');
            $table->boolean('valide')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['ecole_id', 'date_obtention']);
            $table->index(['user_id', 'date_obtention']);
            $table->index('examen_id');
            $table->unique(['user_id', 'ceinture_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_ceintures');
    }
};
