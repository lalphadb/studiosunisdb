#!/bin/bash
echo "🚀 Création de toutes les migrations StudiosDB..."

# Migration 5: User Ceintures
cat > database/migrations/2024_01_01_000005_create_user_ceintures_table.php << 'MIGRATION'
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
MIGRATION

# Migration 6: Cours Horaires
cat > database/migrations/2024_01_01_000006_create_cours_horaires_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cours_horaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->tinyInteger('jour'); // 0=Dimanche, 6=Samedi
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('salle', 50)->nullable();
            $table->foreignId('instructeur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            $table->index(['cours_id', 'jour']);
            $table->index('instructeur_id');
            $table->index('actif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cours_horaires');
    }
};
MIGRATION

# Migration 7: Inscriptions Cours
cat > database/migrations/2024_01_01_000007_create_inscriptions_cours_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cours_id')->constrained()->onDelete('restrict');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('restrict');
            $table->date('date_inscription');
            $table->date('date_fin')->nullable();
            $table->enum('statut', ['actif', 'suspendu', 'termine'])->default('actif');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'cours_id', 'date_inscription']);
            $table->index('statut');
            $table->index(['date_inscription', 'date_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions_cours');
    }
};
MIGRATION

# Migration 8: Session Cours
cat > database/migrations/2024_01_01_000008_create_session_cours_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->datetime('date_debut');
            $table->datetime('date_fin');
            $table->foreignId('instructeur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('salle', 50)->nullable();
            $table->integer('capacite_max')->nullable();
            $table->enum('statut', ['planifie', 'en_cours', 'termine', 'annule'])->default('planifie');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['cours_id', 'date_debut']);
            $table->index('statut');
            $table->index('instructeur_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_cours');
    }
};
MIGRATION

# Migration 9: Presences
cat > database/migrations/2024_01_01_000009_create_presences_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_cours_id')->constrained('session_cours')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->enum('status', ['present', 'absent', 'retard', 'excuse'])->default('present');
            $table->datetime('heure_arrivee')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['session_cours_id', 'user_id']);
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
MIGRATION

# Migration 10: Paiements
cat > database/migrations/2024_01_01_000010_create_paiements_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('restrict');
            $table->decimal('montant', 10, 2);
            $table->date('date_paiement');
            $table->enum('type_paiement', ['mensualite', 'inscription', 'seminaire', 'equipement', 'autre']);
            $table->enum('methode_paiement', ['especes', 'cheque', 'carte', 'virement', 'autre']);
            $table->string('reference_paiement', 100)->nullable();
            $table->enum('statut', ['en_attente', 'complete', 'annule', 'rembourse'])->default('complete');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'date_paiement']);
            $table->index('type_paiement');
            $table->index('statut');
            $table->index('date_paiement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
MIGRATION

# Migration 11: Seminaires
cat > database/migrations/2024_01_01_000011_create_seminaires_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('cascade');
            $table->string('nom', 200);
            $table->text('description')->nullable();
            $table->datetime('date_debut');
            $table->datetime('date_fin');
            $table->string('lieu', 255)->nullable();
            $table->foreignId('instructeur_principal_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('prix', 10, 2)->nullable();
            $table->integer('capacite_max')->nullable();
            $table->enum('statut', ['planifie', 'en_cours', 'termine', 'annule'])->default('planifie');
            $table->boolean('ouvert_externe')->default(false);
            $table->timestamps();
            
            $table->index(['ecole_id', 'date_debut']);
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminaires');
    }
};
MIGRATION

# Migration 12: Inscriptions Seminaires
cat > database/migrations/2024_01_01_000012_create_inscriptions_seminaires_table.php << 'MIGRATION'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions_seminaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seminaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ecole_id')->constrained('ecoles')->onDelete('restrict');
            $table->date('date_inscription');
            $table->enum('statut', ['inscrit', 'confirme', 'present', 'absent', 'annule'])->default('inscrit');
            $table->foreignId('paiement_id')->nullable()->constrained()->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['seminaire_id', 'user_id']);
            $table->index('statut');
            $table->index('date_inscription');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions_seminaires');
    }
};
MIGRATION

# Migration 13: Famille Membres
cat > database/migrations/2024_01_01_000013_create_famille_membres_table.php << 'MIGRATION'
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
MIGRATION

echo "✅ Toutes les migrations créées!"
