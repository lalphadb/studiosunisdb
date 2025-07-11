<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('famille_membres', function (Blueprint $table) {
            $table->id();
            $table->string('code_famille', 20);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->enum('role_famille', ['parent', 'enfant', 'conjoint', 'autre']);
            $table->foreignId('responsable_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            $table->index(['code_famille', 'ecole_id']);
            $table->index('user_id');
            $table->index('responsable_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('famille_membres');
    }
};
