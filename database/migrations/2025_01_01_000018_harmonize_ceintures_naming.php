<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Harmonisation du naming: belts → ceintures
     */
    public function up(): void
    {
        // 1. Renommer la table belts en ceintures si elle existe
        if (Schema::hasTable('belts') && ! Schema::hasTable('ceintures')) {
            Schema::rename('belts', 'ceintures');

            // Mettre à jour les contraintes FK
            $tablesToUpdate = [
                'membres' => 'ceinture_actuelle_id',
                'progression_ceintures' => ['ceinture_actuelle_id', 'ceinture_cible_id'],
                'examens' => 'ceinture_id',
            ];

            foreach ($tablesToUpdate as $table => $columns) {
                if (! Schema::hasTable($table)) {
                    continue;
                }

                $columns = is_array($columns) ? $columns : [$columns];

                foreach ($columns as $column) {
                    if (! Schema::hasColumn($table, $column)) {
                        continue;
                    }

                    // Supprimer l'ancienne FK
                    try {
                        $constraintName = $this->getForeignKeyName($table, $column);
                        if ($constraintName) {
                            Schema::table($table, function (Blueprint $t) use ($constraintName) {
                                $t->dropForeign($constraintName);
                            });
                        }
                    } catch (\Exception $e) {
                        // Ignorer si la contrainte n'existe pas
                    }

                    // Recréer la FK vers ceintures
                    Schema::table($table, function (Blueprint $t) use ($column) {
                        $t->foreign($column)
                            ->references('id')
                            ->on('ceintures')
                            ->nullOnDelete();
                    });
                }
            }
        }

        // 2. Si les deux tables existent, migrer les données et supprimer belts
        if (Schema::hasTable('belts') && Schema::hasTable('ceintures')) {
            // Migrer les données si nécessaire
            $beltsCount = DB::table('belts')->count();
            $ceinturesCount = DB::table('ceintures')->count();

            if ($beltsCount > 0 && $ceinturesCount == 0) {
                // Copier les données de belts vers ceintures
                DB::statement('INSERT INTO ceintures SELECT * FROM belts');
            }

            // Supprimer la table belts
            Schema::dropIfExists('belts');
        }

        // 3. S'assurer que la table ceintures existe avec la bonne structure
        if (! Schema::hasTable('ceintures')) {
            Schema::create('ceintures', function (Blueprint $table) {
                $table->id();
                $table->integer('ordre')->unique();
                $table->string('nom');
                $table->string('nom_en')->nullable();
                $table->string('couleur_hex', 7)->default('#000000');
                $table->text('description')->nullable();
                $table->json('criteres_passage')->nullable();
                $table->boolean('est_active')->default(true);
                $table->timestamps();

                $table->index('ordre');
                $table->index('est_active');
            });

            // Insérer les ceintures de base
            $this->seedBasicCeintures();
        }
    }

    public function down(): void
    {
        // Renommer ceintures en belts pour rollback
        if (Schema::hasTable('ceintures') && ! Schema::hasTable('belts')) {
            Schema::rename('ceintures', 'belts');
        }
    }

    /**
     * Obtenir le nom de la contrainte FK
     */
    private function getForeignKeyName($table, $column)
    {
        $dbName = DB::getDatabaseName();

        $result = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', $dbName)
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->value('CONSTRAINT_NAME');

        return $result;
    }

    /**
     * Insérer les ceintures de base
     */
    private function seedBasicCeintures(): void
    {
        $ceintures = [
            ['ordre' => 0, 'nom' => 'Blanche', 'nom_en' => 'White', 'couleur_hex' => '#FFFFFF'],
            ['ordre' => 1, 'nom' => 'Jaune', 'nom_en' => 'Yellow', 'couleur_hex' => '#FFD700'],
            ['ordre' => 2, 'nom' => 'Orange', 'nom_en' => 'Orange', 'couleur_hex' => '#FFA500'],
            ['ordre' => 3, 'nom' => 'Verte', 'nom_en' => 'Green', 'couleur_hex' => '#00FF00'],
            ['ordre' => 4, 'nom' => 'Bleue', 'nom_en' => 'Blue', 'couleur_hex' => '#0000FF'],
            ['ordre' => 5, 'nom' => 'Violette', 'nom_en' => 'Purple', 'couleur_hex' => '#800080'],
            ['ordre' => 6, 'nom' => 'Brune', 'nom_en' => 'Brown', 'couleur_hex' => '#8B4513'],
            ['ordre' => 7, 'nom' => 'Noire', 'nom_en' => 'Black', 'couleur_hex' => '#000000'],
        ];

        foreach ($ceintures as $ceinture) {
            DB::table('ceintures')->insertOrIgnore(array_merge($ceinture, [
                'est_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
};
