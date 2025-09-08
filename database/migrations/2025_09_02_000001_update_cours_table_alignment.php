<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cours')) {
            Schema::table('cours', function (Blueprint $table) {
                if (Schema::hasColumn('cours', 'capacite_max') && ! Schema::hasColumn('cours', 'places_max')) {
                    $table->renameColumn('capacite_max', 'places_max');
                }
                if (Schema::hasColumn('cours', 'prix_mensuel') && ! Schema::hasColumn('cours', 'tarif_mensuel')) {
                    $table->renameColumn('prix_mensuel', 'tarif_mensuel');
                }
            });

            Schema::table('cours', function (Blueprint $table) {
                if (! Schema::hasColumn('cours', 'ecole_id')) {
                    if (Schema::hasTable('ecoles')) {
                        $table->foreignId('ecole_id')->nullable()->after('instructeur_id')->constrained('ecoles')->nullOnDelete();
                    } else {
                        $table->unsignedBigInteger('ecole_id')->nullable()->after('instructeur_id');
                    }
                }
                if (! Schema::hasColumn('cours', 'age_min')) {
                    $table->unsignedSmallInteger('age_min')->default(3)->after('niveau');
                }
                if (! Schema::hasColumn('cours', 'age_max')) {
                    $table->unsignedSmallInteger('age_max')->nullable()->after('age_min');
                }
                if (! Schema::hasColumn('cours', 'places_max')) {
                    $table->unsignedSmallInteger('places_max')->default(20)->after('age_max');
                }
                if (! Schema::hasColumn('cours', 'session')) {
                    $table->enum('session', ['automne', 'hiver', 'printemps', 'ete'])->nullable()->after('jour_semaine');
                }
                if (! Schema::hasColumn('cours', 'date_debut')) {
                    $table->date('date_debut')->nullable()->after('heure_fin');
                }
                if (! Schema::hasColumn('cours', 'date_fin')) {
                    $table->date('date_fin')->nullable()->after('date_debut');
                }
                if (! Schema::hasColumn('cours', 'type_tarif')) {
                    $table->enum('type_tarif', ['mensuel', 'trimestriel', 'horaire', 'a_la_carte', 'autre'])->default('mensuel')->after('date_fin');
                }
                if (! Schema::hasColumn('cours', 'montant')) {
                    $table->decimal('montant', 8, 2)->default(0)->after('type_tarif');
                }
                if (! Schema::hasColumn('cours', 'details_tarif')) {
                    $table->text('details_tarif')->nullable()->after('montant');
                }
                if (! Schema::hasColumn('cours', 'tarif_mensuel')) {
                    $table->decimal('tarif_mensuel', 8, 2)->nullable()->after('details_tarif');
                }
                if (! Schema::hasColumn('cours', 'couleur')) {
                    $table->string('couleur', 20)->nullable()->after('tarif_mensuel');
                }
                if (! Schema::hasColumn('cours', 'salle')) {
                    $table->string('salle', 100)->nullable()->after('couleur');
                }
                if (! Schema::hasColumn('cours', 'prerequis')) {
                    $table->text('prerequis')->nullable()->after('salle');
                }
                if (! Schema::hasColumn('cours', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    public function down(): void
    {
        // Non destructif
    }
};
