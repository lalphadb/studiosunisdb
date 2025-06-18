#!/bin/bash
echo "🔧 CORRECTION ORDRE MIGRATIONS"
echo "=============================="

# Supprimer les anciens fichiers de migration s'ils existent
rm -f database/migrations/2025_06_18_*

# Recréer avec l'ordre correct et horodatage séquentiel
echo "📝 Recréation des migrations dans l'ordre correct..."

# Base Laravel (déjà existants - ne pas toucher)
echo "✅ Base Laravel migrations conservées"

# Nos migrations dans l'ordre OBLIGATOIRE
echo "🏢 1. Écoles (base)"
cat > database/migrations/2025_06_18_200100_create_ecoles_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ecoles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code', 10)->unique();
            $table->string('adresse');
            $table->string('ville');
            $table->string('province')->default('QC');
            $table->string('code_postal');
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('site_web')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ecoles');
    }
};
MIGRATION

echo "🥋 2. Ceintures (autonome)"
cat > database/migrations/2025_06_18_200200_create_ceintures_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ceintures', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('couleur');
            $table->integer('ordre');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ceintures');
    }
};
MIGRATION

echo "👤 3. Ajout ecole_id aux users"
cat > database/migrations/2025_06_18_200300_add_fields_to_users_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('ecole_id')->nullable()->constrained('ecoles')->onDelete('set null');
            $table->string('telephone')->nullable();
            $table->date('date_naissance')->nullable();
            $table->enum('sexe', ['M', 'F', 'Autre'])->nullable();
            $table->text('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_telephone')->nullable();
            $table->boolean('active')->default(true);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['ecole_id']);
            $table->dropColumn([
                'ecole_id', 'telephone', 'date_naissance', 'sexe',
                'adresse', 'ville', 'code_postal',
                'contact_urgence_nom', 'contact_urgence_telephone', 'active'
            ]);
        });
    }
};
MIGRATION

echo "👥 4. Membres"
cat > database/migrations/2025_06_18_200400_create_membres_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->date('date_naissance')->nullable();
            $table->enum('sexe', ['M', 'F', 'Autre'])->nullable();
            $table->text('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_telephone')->nullable();
            $table->date('date_inscription')->default(now());
            $table->boolean('active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('membres');
    }
};
MIGRATION

echo "📚 5. Cours"
cat > database/migrations/2025_06_18_200500_create_cours_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->string('nom');
            $table->text('description')->nullable();
            $table->enum('niveau', ['debutant', 'intermediaire', 'avance', 'tous_niveaux']);
            $table->integer('capacite_max')->default(20);
            $table->decimal('prix', 8, 2)->nullable();
            $table->integer('duree_minutes')->default(60);
            $table->string('instructeur')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cours');
    }
};
MIGRATION

echo "🎓 6. Séminaires"
cat > database/migrations/2025_06_18_200600_create_seminaires_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seminaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->enum('type', ['technique', 'kata', 'competition', 'arbitrage']);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('instructeur');
            $table->integer('capacite_max')->default(30);
            $table->decimal('prix', 8, 2)->nullable();
            $table->enum('niveau_requis', ['debutant', 'intermediaire', 'avance', 'tous_niveaux']);
            $table->boolean('inscription_ouverte')->default(true);
            $table->text('materiel_requis')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seminaires');
    }
};
MIGRATION

echo "🕐 7. Horaires Cours"
cat > database/migrations/2025_06_18_200700_create_cours_horaires_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cours_horaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->enum('jour_semaine', ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cours_horaires');
    }
};
MIGRATION

echo "🏆 8. Membre Ceintures"
cat > database/migrations/2025_06_18_200800_create_membre_ceintures_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('membre_ceintures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('ceinture_id')->constrained('ceintures')->onDelete('cascade');
            $table->date('date_obtention');
            $table->string('examinateur')->nullable();
            $table->text('commentaires')->nullable();
            $table->boolean('valide')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('membre_ceintures');
    }
};
MIGRATION

echo "📝 9. Inscriptions Cours"
cat > database/migrations/2025_06_18_200900_create_inscriptions_cours_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inscriptions_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->date('date_inscription')->default(now());
            $table->enum('statut', ['active', 'suspendue', 'terminee', 'annulee'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['membre_id', 'cours_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inscriptions_cours');
    }
};
MIGRATION

echo "🎓 10. Inscriptions Séminaires"
cat > database/migrations/2025_06_18_201000_create_inscriptions_seminaires_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inscriptions_seminaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('seminaire_id')->constrained('seminaires')->onDelete('cascade');
            $table->date('date_inscription')->default(now());
            $table->enum('statut', ['inscrit', 'confirme', 'present', 'absent', 'annule'])->default('inscrit');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['membre_id', 'seminaire_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inscriptions_seminaires');
    }
};
MIGRATION

echo "✅ 11. Présences"
cat > database/migrations/2025_06_18_201100_create_presences_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->date('date_cours');
            $table->boolean('present')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['membre_id', 'cours_id', 'date_cours']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('presences');
    }
};
MIGRATION

echo "💳 12. Paiements"
cat > database/migrations/2025_06_18_201200_create_paiements_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Informations paiement
            $table->string('reference_interne')->nullable()->unique();
            $table->string('type_paiement')->default('interac');
            $table->enum('motif', [
                'session_automne',
                'session_hiver',
                'session_printemps', 
                'session_ete',
                'seminaire',
                'examen_ceinture',
                'equipement',
                'autre'
            ]);
            $table->string('description')->nullable();
            
            // Montants
            $table->decimal('montant', 10, 2);
            $table->decimal('frais', 10, 2)->default(0);
            $table->decimal('montant_net', 10, 2);
            
            // Informations Interac
            $table->string('email_expediteur')->nullable();
            $table->string('nom_expediteur')->nullable();
            $table->string('reference_interac')->nullable();
            $table->text('message_interac')->nullable();
            
            // Workflow et validation
            $table->enum('statut', [
                'en_attente',
                'recu',
                'valide',
                'rejete',
                'rembourse'
            ])->default('en_attente');
            
            // Dates importantes
            $table->timestamp('date_facture')->nullable();
            $table->timestamp('date_echeance')->nullable();
            $table->timestamp('date_reception')->nullable();
            $table->timestamp('date_validation')->nullable();
            
            // Informations comptables
            $table->string('periode_facturation')->nullable();
            $table->year('annee_fiscale')->default(date('Y'));
            $table->boolean('recu_fiscal_emis')->default(false);
            
            // Métadonnées
            $table->json('metadonnees')->nullable();
            $table->text('notes_admin')->nullable();
            
            $table->timestamps();
            
            // Index pour performance
            $table->index(['ecole_id', 'statut']);
            $table->index(['membre_id', 'created_at']);
            $table->index(['reference_interne']);
            $table->index(['type_paiement', 'statut']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
};
MIGRATION

echo "🔐 13. Permissions Spatie"
cat > database/migrations/2025_06_18_230000_create_permission_tables.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }
        if ($teams && empty($columnNames['team_foreign_key'] ?? null)) {
            throw new \Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id'); // permission id
            $table->string('name');       // For MySQL 8.0 use string('name', 125);
            $table->string('guard_name'); // For MySQL 8.0 use string('guard_name', 125);
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) use ($teams, $columnNames) {
            $table->bigIncrements('id'); // role id
            if ($teams || config('permission.testing')) { // permission.testing is a fix for sqlite testing
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
            }
            $table->string('name');       // For MySQL 8.0 use string('name', 125);
            $table->string('guard_name'); // For MySQL 8.0 use string('guard_name', 125);
            $table->timestamps();
            if ($teams || config('permission.testing')) {
                $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
            } else {
                $table->unique(['name', 'guard_name']);
            }
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $teams) {
            $table->unsignedBigInteger(PermissionTableNames::getColumnName('permission_id'));

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign(PermissionTableNames::getColumnName('permission_id'))
                ->references('id') // permission id
                ->on($tableNames['permissions'])
                ->onDelete('cascade');
            if ($teams) {
                $table->unsignedBigInteger($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');

                $table->primary([$columnNames['team_foreign_key'], PermissionTableNames::getColumnName('permission_id'), $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            } else {
                $table->primary([PermissionTableNames::getColumnName('permission_id'), $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            }

        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $teams) {
            $table->unsignedBigInteger(PermissionTableNames::getColumnName('role_id'));

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign(PermissionTableNames::getColumnName('role_id'))
                ->references('id') // role id
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            if ($teams) {
                $table->unsignedBigInteger($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');

                $table->primary([$columnNames['team_foreign_key'], PermissionTableNames::getColumnName('role_id'), $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
            } else {
                $table->primary([PermissionTableNames::getColumnName('role_id'), $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
            }
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger(PermissionTableNames::getColumnName('permission_id'));
            $table->unsignedBigInteger(PermissionTableNames::getColumnName('role_id'));

            $table->foreign(PermissionTableNames::getColumnName('permission_id'))
                ->references('id') // permission id
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign(PermissionTableNames::getColumnName('role_id'))
                ->references('id') // role id
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary([PermissionTableNames::getColumnName('permission_id'), PermissionTableNames::getColumnName('role_id')], 'role_has_permissions_permission_id_role_id_primary');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    public function down()
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
};

class PermissionTableNames
{
    public static function getColumnName(string $name): string
    {
        return config('permission.column_names.' . $name, $name);
    }
}
MIGRATION

echo "📊 14. Activity Log"
cat > database/migrations/2025_06_18_230100_create_activity_log_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection(config('activitylog.database_connection'))->create(config('activitylog.table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject', 'subject');
            $table->nullableMorphs('causer', 'causer');
            $table->json('properties')->nullable();
            $table->timestamps();
            $table->index('log_name');
        });
    }

    public function down()
    {
        Schema::connection(config('activitylog.database_connection'))->dropIfExists(config('activitylog.table_name'));
    }
};
MIGRATION

echo ""
echo "✅ MIGRATIONS RECRÉÉES DANS L'ORDRE CORRECT!"
echo "📋 Ordre final:"
ls -1 database/migrations/2025_06_18_*.php | sort | nl

echo ""
echo "🚀 Pour appliquer:"
echo "php artisan migrate:fresh --seed"
