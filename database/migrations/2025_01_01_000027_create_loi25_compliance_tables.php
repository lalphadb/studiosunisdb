<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables de conformité Loi 25 (RGPD Québec)
     */
    public function up(): void
    {
        // 1. Table audit_logs pour traçabilité complète
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ecole_id')->constrained()->cascadeOnDelete();
            
            // Informations sur l'action
            $table->string('action', 50); // create, update, delete, login, logout, export, etc.
            $table->string('model_type')->nullable(); // Ex: App\Models\Membre
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('description');
            
            // Données avant/après pour traçabilité
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            
            // Contexte de l'action
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->string('request_id')->nullable(); // Pour corréler les logs
            
            // Métadonnées
            $table->enum('severity', ['info', 'warning', 'error', 'critical'])->default('info');
            $table->boolean('is_sensitive')->default(false); // Pour données sensibles
            $table->timestamp('created_at')->useCurrent();
            
            // Index pour performances
            $table->index(['user_id', 'created_at']);
            $table->index(['ecole_id', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index('action');
            $table->index('created_at');
            $table->index('request_id');
        });
        
        // 2. Table consentements pour gestion RGPD
        Schema::create('consentements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ecole_id')->constrained()->cascadeOnDelete();
            
            // Type et version du consentement
            $table->string('type', 50); // photos, communications, donnees_medicales, etc.
            $table->string('version', 10)->default('1.0'); // Version du texte de consentement
            $table->boolean('consent_given');
            
            // Détails du consentement
            $table->text('consent_text'); // Texte exact présenté à l'utilisateur
            $table->string('consent_method', 50); // web, papier, verbal, email
            $table->json('consent_details')->nullable(); // Détails additionnels
            
            // Traçabilité
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('device_type', 50)->nullable(); // mobile, desktop, tablet
            $table->string('browser', 50)->nullable();
            
            // Signature électronique
            $table->string('signature_hash')->nullable(); // Hash de la signature si applicable
            $table->text('signature_data')->nullable(); // Données de signature (base64)
            
            // Parent/tuteur pour mineurs
            $table->string('guardian_name')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_relationship')->nullable();
            
            // Révocation
            $table->timestamp('revoked_at')->nullable();
            $table->string('revocation_reason')->nullable();
            $table->foreignId('revoked_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Timestamps
            $table->timestamp('expires_at')->nullable(); // Pour consentements temporaires
            $table->timestamps();
            
            // Index
            $table->index(['membre_id', 'type', 'consent_given']);
            $table->index(['ecole_id', 'created_at']);
            $table->index('type');
            $table->index('consent_given');
            $table->index('revoked_at');
            
            // Contrainte unique pour éviter doublons
            $table->unique(['membre_id', 'type', 'version']);
        });
        
        // 3. Table pour historique des exports (RGPD)
        Schema::create('export_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ecole_id')->constrained()->cascadeOnDelete();
            
            // Détails de l'export
            $table->string('export_type', 50); // membres, paiements, presences, etc.
            $table->string('format', 10); // pdf, xlsx, csv, json
            $table->integer('records_count');
            $table->json('filters_applied')->nullable(); // Filtres utilisés
            $table->json('columns_exported')->nullable(); // Colonnes incluses
            
            // Sécurité
            $table->string('file_hash')->nullable(); // Hash SHA256 du fichier
            $table->boolean('contains_pii')->default(true); // Contient données personnelles
            $table->string('purpose')->nullable(); // Raison de l'export
            
            // Traçabilité
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            // Index
            $table->index(['user_id', 'created_at']);
            $table->index(['ecole_id', 'created_at']);
            $table->index('export_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_logs');
        Schema::dropIfExists('consentements');
        Schema::dropIfExists('audit_logs');
    }
};
