<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajout des index recommandés pour optimisation des performances
     */
    public function up(): void
    {
        // 1. Index pour les recherches fréquentes
        $indexesToAdd = [
            // Table membres - recherches fréquentes
            'membres' => [
                ['columns' => ['ecole_id', 'statut'], 'name' => 'idx_membres_ecole_statut'],
                ['columns' => ['famille_id'], 'name' => 'idx_membres_famille'],
                ['columns' => ['date_naissance'], 'name' => 'idx_membres_date_naissance'],
            ],

            // Table cours - filtres dashboard
            'cours' => [
                ['columns' => ['ecole_id', 'actif'], 'name' => 'idx_cours_ecole_actif'],
                ['columns' => ['instructeur_id', 'actif'], 'name' => 'idx_cours_instructeur_actif'],
                ['columns' => ['session', 'actif'], 'name' => 'idx_cours_session_actif'],
                ['columns' => ['jour_semaine', 'heure_debut'], 'name' => 'idx_cours_horaire'],
            ],

            // Table presences - rapports
            'presences' => [
                ['columns' => ['ecole_id', 'date_presence'], 'name' => 'idx_presences_ecole_date'],
                ['columns' => ['membre_id', 'date_presence'], 'name' => 'idx_presences_membre_date'],
                ['columns' => ['cours_id', 'date_presence'], 'name' => 'idx_presences_cours_date'],
            ],

            // Table paiements - comptabilité
            'paiements' => [
                ['columns' => ['ecole_id', 'date_paiement'], 'name' => 'idx_paiements_ecole_date'],
                ['columns' => ['membre_id', 'statut'], 'name' => 'idx_paiements_membre_statut'],
                ['columns' => ['type_paiement', 'statut'], 'name' => 'idx_paiements_type_statut'],
            ],

            // Table cours_membres - inscriptions
            'cours_membres' => [
                ['columns' => ['cours_id', 'statut'], 'name' => 'idx_cours_membres_cours_statut'],
                ['columns' => ['membre_id', 'statut'], 'name' => 'idx_cours_membres_membre_statut'],
                ['columns' => ['date_inscription'], 'name' => 'idx_cours_membres_date_inscription'],
            ],

            // Table users - authentification
            'users' => [
                ['columns' => ['ecole_id', 'active'], 'name' => 'idx_users_ecole_active'],
                ['columns' => ['last_login'], 'name' => 'idx_users_last_login'],
            ],
        ];

        // Ajouter les index s'ils n'existent pas
        foreach ($indexesToAdd as $table => $indexes) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($indexes as $indexDef) {
                $this->addIndexIfNotExists($table, $indexDef['columns'], $indexDef['name']);
            }
        }

        // 2. Index composites pour les pivots (garantir unicité)
        $pivotTables = [
            'cours_membres' => ['cours_id', 'membre_id'],
            'role_has_permissions' => ['role_id', 'permission_id'],
            'model_has_roles' => ['model_type', 'model_id', 'role_id'],
            'model_has_permissions' => ['model_type', 'model_id', 'permission_id'],
        ];

        foreach ($pivotTables as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $this->addUniqueIndexIfNotExists($table, $columns);
        }

        // 3. Index Full-Text pour recherche rapide (MySQL/MariaDB seulement)
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            // Membres - recherche par nom/email
            if (Schema::hasTable('membres')) {
                try {
                    DB::statement('ALTER TABLE membres ADD FULLTEXT idx_membres_search (prenom, nom, email)');
                } catch (\Exception $e) {
                    // Index déjà existant
                }
            }

            // Cours - recherche par nom/description
            if (Schema::hasTable('cours')) {
                try {
                    DB::statement('ALTER TABLE cours ADD FULLTEXT idx_cours_search (nom, description)');
                } catch (\Exception $e) {
                    // Index déjà existant
                }
            }
        }

        // 4. Analyser et optimiser les tables
        $this->optimizeTables();
    }

    public function down(): void
    {
        // Supprimer les index créés
        $indexesToRemove = [
            'membres' => ['idx_membres_ecole_statut', 'idx_membres_famille', 'idx_membres_date_naissance', 'idx_membres_search'],
            'cours' => ['idx_cours_ecole_actif', 'idx_cours_instructeur_actif', 'idx_cours_session_actif', 'idx_cours_horaire', 'idx_cours_search'],
            'presences' => ['idx_presences_ecole_date', 'idx_presences_membre_date', 'idx_presences_cours_date'],
            'paiements' => ['idx_paiements_ecole_date', 'idx_paiements_membre_statut', 'idx_paiements_type_statut'],
            'cours_membres' => ['idx_cours_membres_cours_statut', 'idx_cours_membres_membre_statut', 'idx_cours_membres_date_inscription'],
            'users' => ['idx_users_ecole_active', 'idx_users_last_login'],
        ];

        foreach ($indexesToRemove as $table => $indexes) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $t) use ($indexes) {
                foreach ($indexes as $index) {
                    try {
                        $t->dropIndex($index);
                    } catch (\Exception $e) {
                        // Index n'existe pas
                    }
                }
            });
        }
    }

    /**
     * Ajouter un index s'il n'existe pas
     */
    private function addIndexIfNotExists($table, $columns, $indexName): void
    {
        $exists = DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('INDEX_NAME', $indexName)
            ->exists();

        if (! $exists) {
            Schema::table($table, function (Blueprint $t) use ($columns, $indexName) {
                // Vérifier que toutes les colonnes existent
                foreach ($columns as $column) {
                    if (! Schema::hasColumn($t->getTable(), $column)) {
                        return;
                    }
                }
                $t->index($columns, $indexName);
            });
        }
    }

    /**
     * Ajouter un index unique composite s'il n'existe pas
     */
    private function addUniqueIndexIfNotExists($table, $columns): void
    {
        $indexName = $table.'_'.implode('_', $columns).'_unique';

        $exists = DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('INDEX_NAME', $indexName)
            ->exists();

        if (! $exists) {
            Schema::table($table, function (Blueprint $t) use ($columns, $indexName) {
                // Vérifier que toutes les colonnes existent
                foreach ($columns as $column) {
                    if (! Schema::hasColumn($t->getTable(), $column)) {
                        return;
                    }
                }
                $t->unique($columns, $indexName);
            });
        }
    }

    /**
     * Optimiser les tables MySQL
     */
    private function optimizeTables(): void
    {
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            return;
        }

        $tables = [
            'membres', 'cours', 'presences', 'paiements',
            'cours_membres', 'users', 'ceintures',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                try {
                    DB::statement("ANALYZE TABLE {$table}");
                } catch (\Exception $e) {
                    // Ignorer les erreurs
                }
            }
        }
    }
};
