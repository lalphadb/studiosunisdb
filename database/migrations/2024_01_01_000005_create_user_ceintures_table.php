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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ceinture_id')->constrained()->onDelete('restrict');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('restrict');
            $table->date('date_obtention');
            $table->string('numero_certificat', 50)->unique()->nullable();
            $table->foreignId('evaluateur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'ceinture_id']);
            $table->index('date_obtention');
            $table->index('numero_certificat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_ceintures');
    }
};
