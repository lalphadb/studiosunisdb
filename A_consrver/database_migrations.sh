# 🗄️ CRÉATION MIGRATIONS STUDIOSDB V5 PRO

# Migration 1: Table Ceintures
cat > database/migrations/2024_01_01_000001_create_ceintures_table.php << 'EOH'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ceintures', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('couleur_hex')->nullable();
            $table->integer('ordre');
            $table->text('description')->nullable();
            $table->json('prerequis_techniques')->nullable();
            $table->integer('duree_minimum_mois')->default(3);
            $table->integer('presences_minimum')->default(24);
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            $table->unique('ordre');
            $table->index('actif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ceintures');
    }
};
EOH

# Migration 2: Table Membres (CRITIQUE)
cat > database/migrations/2024_01_01_000002_create_membres_table.php << 'EOH'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('prenom');
            $table->string('nom');
            $table->date('date_naissance');
            $table->enum('sexe', ['M', 'F', 'Autre']);
            $table->string('telephone')->nullable();
            $table->text('adresse')->nullable();
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_telephone')->nullable();
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            $table->foreignId('ceinture_actuelle_id')->nullable()->constrained('ceintures');
            $table->date('date_inscription');
            $table->date('date_derniere_presence')->nullable();
            $table->text('notes_medicales')->nullable();
            $table->boolean('consentement_photos')->default(false);
            $table->boolean('consentement_communications')->default(true);
            $table->timestamps();
            
            $table->index(['statut', 'date_derniere_presence']);
            $table->index('date_inscription');
            $table->index('ceinture_actuelle_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membres');
    }
};
EOH

# Migration 3: Table Cours
cat > database/migrations/2024_01_01_000003_create_cours_table.php << 'EOH'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->foreignId('instructeur_id')->constrained('users');
            $table->enum('niveau', ['debutant', 'intermediaire', 'avance', 'competition']);
            $table->integer('age_min')->default(5);
            $table->integer('age_max')->default(99);
            $table->integer('places_max')->default(20);
            $table->enum('jour_semaine', ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->decimal('tarif_mensuel', 8, 2);
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            $table->index(['jour_semaine', 'heure_debut']);
            $table->index(['instructeur_id', 'actif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
EOH

# Migration 4: Table Presences
cat > database/migrations/2024_01_01_000004_create_presences_table.php << 'EOH'
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
            $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructeur_id')->constrained('users');
            $table->date('date_cours');
            $table->enum('statut', ['present', 'absent', 'retard', 'excuse'])->default('present');
            $table->time('heure_arrivee')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['cours_id', 'membre_id', 'date_cours']);
            $table->index(['date_cours', 'statut']);
            $table->index('instructeur_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
EOH

# Migration 5: Table Paiements
cat > database/migrations/2024_01_01_000005_create_paiements_table.php << 'EOH'
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
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['inscription', 'mensuel', 'examen', 'stage', 'equipement', 'autre']);
            $table->decimal('montant', 8, 2);
            $table->string('description');
            $table->date('date_echeance');
            $table->date('date_paiement')->nullable();
            $table->enum('statut', ['en_attente', 'paye', 'en_retard', 'annule'])->default('en_attente');
            $table->enum('methode_paiement', ['especes', 'cheque', 'virement', 'carte', 'en_ligne'])->nullable();
            $table->string('reference_transaction')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['date_echeance', 'statut']);
            $table->index(['membre_id', 'statut']);
            $table->index('date_paiement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
EOH

# Migration 6: Tables Pivot
cat > database/migrations/2024_01_01_000006_create_cours_membres_table.php << 'EOH'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cours_membres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->date('date_inscription');
            $table->date('date_fin')->nullable();
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            $table->timestamps();
            
            $table->unique(['cours_id', 'membre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cours_membres');
    }
};
EOH

echo "✅ Toutes les migrations ont été créées!"
echo "📋 Exécutez maintenant:"
echo "cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro"
echo "php artisan migrate"
