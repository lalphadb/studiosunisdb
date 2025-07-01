<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('sessions_cours')->onDelete('cascade');
            $table->foreignId('ecole_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['ouverture', 'rappel_3j', 'rappel_1j', 'fermeture']);
            $table->timestamp('date_envoi')->nullable();
            $table->integer('membres_notifies')->default(0);
            $table->boolean('envoye')->default(false);
            $table->timestamps();

            $table->index(['session_id', 'type']);
            $table->index(['date_envoi', 'envoye']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_notifications');
    }
};
