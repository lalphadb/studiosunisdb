<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('numero')->unique(); // ID membre
            $table->string('prenom');
            $table->string('nom');
            $table->date('date_naissance')->nullable();
            $table->string('courriel')->nullable();
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();
            $table->date('date_inscription')->nullable();
            $table->enum('statut', ['actif','suspendu','inactif'])->default('actif')->index();
            $table->foreignId('ceinture_actuelle_id')->nullable()->constrained('belts')->nullOnDelete();
            $table->string('photo_path')->nullable();
            $table->text('remarques')->nullable();
            $table->timestamps();

            $table->index(['ecole_id','statut']);
            $table->index(['ecole_id','nom']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('members');
    }
};
