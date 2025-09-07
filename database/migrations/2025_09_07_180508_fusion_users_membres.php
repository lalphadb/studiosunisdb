<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ajouter colonnes karaté dans users
        Schema::table('users', function (Blueprint $table) {
            // Informations personnelles
            $table->string('prenom')->nullable()->after('name');
            $table->string('nom')->nullable()->after('prenom');
            $table->string('telephone')->nullable()->after('email');
            $table->date('date_naissance')->nullable()->after('telephone');
            $table->enum('sexe', ['M', 'F', 'Autre'])->default('Autre')->after('date_naissance');
            
            // Adresse
            $table->text('adresse')->nullable()->after('sexe');
            $table->string('ville')->nullable()->after('adresse');
            $table->string('code_postal')->nullable()->after('ville');
            $table->string('province')->default('QC')->after('code_postal');
            
            // Contact urgence
            $table->string('contact_urgence_nom')->nullable()->after('province');
            $table->string('contact_urgence_telephone')->nullable()->after('contact_urgence_nom');
            $table->string('contact_urgence_relation')->nullable()->after('contact_urgence_telephone');
            
            // Statut et karaté
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif')->after('active');
            $table->unsignedBigInteger('ceinture_actuelle_id')->nullable()->after('statut');
            $table->date('date_inscription')->nullable()->after('ceinture_actuelle_id');
            $table->date('date_derniere_presence')->nullable()->after('date_inscription');
            
            // Médical
            $table->text('notes_medicales')->nullable()->after('date_derniere_presence');
            $table->json('allergies')->nullable()->after('notes_medicales');
            $table->json('medicaments')->nullable()->after('allergies');
            
            // Consentements
            $table->boolean('consentement_photos')->default(false)->after('medicaments');
            $table->boolean('consentement_communications')->default(true)->after('consentement_photos');
            $table->timestamp('date_consentement')->nullable()->after('consentement_communications');
            
            // Liens familiaux
            $table->unsignedBigInteger('family_id')->nullable()->after('date_consentement');
            
            // Champs personnalisés
            $table->json('champs_personnalises')->nullable()->after('family_id');
            
            // Soft deletes
            $table->softDeletes()->after('updated_at');
            
            // Index pour performance
            $table->index(['prenom', 'nom'], 'idx_nom_complet');
            $table->index('date_naissance', 'idx_date_naissance');
            $table->index('statut', 'idx_statut');
            $table->index('date_inscription', 'idx_date_inscription');
            $table->index('family_id', 'idx_family_id');
        });

        // Ajouter les clés étrangères après avoir ajouté les colonnes
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('ceinture_actuelle_id')->references('id')->on('ceintures')->onDelete('set null');
            $table->foreign('family_id')->references('id')->on('families')->onDelete('set null');
        });

        // 2. Migrer données membres → users
        DB::statement("
            UPDATE users u 
            JOIN membres m ON u.id = m.user_id 
            SET 
                u.prenom = m.prenom,
                u.nom = m.nom,
                u.telephone = m.telephone,
                u.date_naissance = m.date_naissance,
                u.sexe = m.sexe,
                u.adresse = m.adresse,
                u.ville = m.ville,
                u.code_postal = m.code_postal,
                u.province = m.province,
                u.contact_urgence_nom = m.contact_urgence_nom,
                u.contact_urgence_telephone = m.contact_urgence_telephone,
                u.contact_urgence_relation = m.contact_urgence_relation,
                u.statut = m.statut,
                u.ceinture_actuelle_id = m.ceinture_actuelle_id,
                u.date_inscription = m.date_inscription,
                u.date_derniere_presence = m.date_derniere_presence,
                u.notes_medicales = m.notes_medicales,
                u.allergies = m.allergies,
                u.medicaments = m.medicaments,
                u.consentement_photos = m.consentement_photos,
                u.consentement_communications = m.consentement_communications,
                u.date_consentement = m.date_consentement,
                u.family_id = m.family_id,
                u.champs_personnalises = m.champs_personnalises,
                u.deleted_at = m.deleted_at
            WHERE m.user_id IS NOT NULL
        ");

        // 3. Créer users pour membres orphelins (sans user_id)
        $membresOrphelins = DB::select("
            SELECT * FROM membres 
            WHERE user_id IS NULL OR user_id NOT IN (SELECT id FROM users)
        ");

        foreach ($membresOrphelins as $membre) {
            $userId = DB::table('users')->insertGetId([
                'name' => $membre->prenom . ' ' . $membre->nom,
                'email' => $membre->email,
                'password' => bcrypt('password123'), // Mot de passe temporaire
                'ecole_id' => $membre->ecole_id,
                'prenom' => $membre->prenom,
                'nom' => $membre->nom,
                'telephone' => $membre->telephone,
                'date_naissance' => $membre->date_naissance,
                'sexe' => $membre->sexe,
                'adresse' => $membre->adresse,
                'ville' => $membre->ville,
                'code_postal' => $membre->code_postal,
                'province' => $membre->province,
                'contact_urgence_nom' => $membre->contact_urgence_nom,
                'contact_urgence_telephone' => $membre->contact_urgence_telephone,
                'contact_urgence_relation' => $membre->contact_urgence_relation,
                'statut' => $membre->statut,
                'ceinture_actuelle_id' => $membre->ceinture_actuelle_id,
                'date_inscription' => $membre->date_inscription,
                'date_derniere_presence' => $membre->date_derniere_presence,
                'notes_medicales' => $membre->notes_medicales,
                'allergies' => $membre->allergies,
                'medicaments' => $membre->medicaments,
                'consentement_photos' => $membre->consentement_photos,
                'consentement_communications' => $membre->consentement_communications,
                'date_consentement' => $membre->date_consentement,
                'family_id' => $membre->family_id,
                'champs_personnalises' => $membre->champs_personnalises,
                'created_at' => $membre->created_at,
                'updated_at' => $membre->updated_at,
                'deleted_at' => $membre->deleted_at,
                'active' => $membre->statut === 'actif',
                'email_verified_at' => now()
            ]);

            // Assigner rôle membre
            $roleId = DB::table('roles')->where('name', 'membre')->value('id');
            if ($roleId) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $roleId,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $userId
                ]);
            }
        }

        // 4. Créer nouvelle table cours_users avec structure complète
        Schema::create('cours_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cours_id');
            $table->unsignedBigInteger('user_id');
            $table->date('date_inscription');
            $table->date('date_fin')->nullable();
            $table->enum('statut_inscription', ['actif', 'inactif', 'suspendu', 'termine'])->default('actif');
            $table->decimal('prix_personnalise', 6, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('cours_id')->references('id')->on('cours')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unique(['cours_id', 'user_id']);
            $table->index(['statut_inscription', 'date_inscription']);
            $table->index('date_fin');
        });

        // 5. Migrer données cours_membres → cours_users
        DB::statement("
            INSERT INTO cours_users (cours_id, user_id, date_inscription, date_fin, statut_inscription, prix_personnalise, notes, created_at, updated_at)
            SELECT 
                cm.cours_id,
                m.user_id,
                cm.date_inscription,
                cm.date_fin,
                cm.statut_inscription,
                cm.prix_personnalise,
                cm.notes,
                cm.created_at,
                cm.updated_at
            FROM cours_membres cm
            JOIN membres m ON cm.membre_id = m.id
            WHERE m.user_id IS NOT NULL
        ");

        // 6. Mettre à jour autres tables : ajouter user_id et migrer données
        $tablesToUpdate = [
            'presences' => 'membre_id',
            'paiements' => 'membre_id',
            'progression_ceintures' => 'membre_id',
            'examens' => 'membre_id'
        ];

        foreach ($tablesToUpdate as $table => $memberColumn) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, $memberColumn)) {
                // Ajouter colonne user_id si pas encore présente
                if (!Schema::hasColumn($table, 'user_id')) {
                    Schema::table($table, function (Blueprint $table) {
                        $table->unsignedBigInteger('user_id')->nullable()->after('id');
                        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    });
                }

                // Migrer données membre_id → user_id
                DB::statement("
                    UPDATE {$table} t
                    JOIN membres m ON t.{$memberColumn} = m.id
                    SET t.user_id = m.user_id
                    WHERE m.user_id IS NOT NULL
                ");
            }
        }

        // 7. Supprimer les anciennes tables après migration
        Schema::dropIfExists('cours_membres');
        
        // Garder table membres temporairement pour rollback possible
        // Schema::dropIfExists('membres'); // On supprimera après validation
    }

    public function down(): void
    {
        // Rollback - recréer cours_membres
        Schema::create('cours_membres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cours_id');
            $table->unsignedBigInteger('membre_id');
            $table->date('date_inscription');
            $table->date('date_fin')->nullable();
            $table->enum('statut_inscription', ['actif', 'inactif', 'suspendu', 'termine'])->default('actif');
            $table->decimal('prix_personnalise', 6, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('cours_id')->references('id')->on('cours')->onDelete('cascade');
            $table->foreign('membre_id')->references('id')->on('membres')->onDelete('cascade');
            $table->unique(['cours_id', 'membre_id']);
        });

        // Supprimer colonnes ajoutées à users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['ceinture_actuelle_id']);
            $table->dropForeign(['family_id']);
            
            $table->dropColumn([
                'prenom', 'nom', 'telephone', 'date_naissance', 'sexe',
                'adresse', 'ville', 'code_postal', 'province',
                'contact_urgence_nom', 'contact_urgence_telephone', 'contact_urgence_relation',
                'statut', 'ceinture_actuelle_id', 'date_inscription', 'date_derniere_presence',
                'notes_medicales', 'allergies', 'medicaments',
                'consentement_photos', 'consentement_communications', 'date_consentement',
                'family_id', 'champs_personnalises', 'deleted_at'
            ]);
        });

        Schema::dropIfExists('cours_users');
    }
};