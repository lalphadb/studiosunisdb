<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();

            // Informations personnelles
            $table->string('first_name')->index();
            $table->string('last_name')->index();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('birth_date');
            $table->enum('gender', ['M', 'F', 'Other'])->default('Other');

            // Adresse
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('province')->default('QC');

            // Contact urgence
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();

            // Statut et progression
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->index();
            $table->foreignId('current_belt_id')->nullable()->constrained('belts');
            $table->date('registration_date')->index();
            $table->date('last_attendance_date')->nullable();

            // Informations médicales
            $table->text('medical_notes')->nullable();
            $table->json('allergies')->nullable();
            $table->json('medications')->nullable();

            // Consentements (Loi 25)
            $table->boolean('consent_photos')->default(false);
            $table->boolean('consent_communications')->default(true);
            $table->timestamp('consent_date')->nullable();

            // Relations
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('family_id')->nullable()->constrained()->nullOnDelete();

            // Métadonnées
            $table->json('custom_fields')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index pour performances
            $table->index(['status', 'last_attendance_date']);
            $table->index(['first_name', 'last_name']);
            $table->fullText(['first_name', 'last_name', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
