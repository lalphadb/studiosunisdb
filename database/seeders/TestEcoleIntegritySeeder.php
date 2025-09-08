<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TestEcoleIntegritySeeder extends Seeder
{
    /**
     * Test l'intégrité de la structure multi-écoles
     */
    public function run(): void
    {
        $this->command->info('========================================');
        $this->command->info('  TEST D\'INTÉGRITÉ STRUCTURE ÉCOLES');
        $this->command->info('========================================');

        $errors = [];
        $warnings = [];
        $success = [];

        // 1. Vérifier l'existence de la table ecoles
        if (! Schema::hasTable('ecoles')) {
            $errors[] = '❌ Table "ecoles" MANQUANTE - Structure mono-école impossible';
        } else {
            $success[] = '✅ Table "ecoles" existe';

            // Vérifier qu'il y a au moins une école
            $ecoleCount = DB::table('ecoles')->count();
            if ($ecoleCount == 0) {
                $errors[] = '❌ Aucune école dans la base de données';
            } else {
                $success[] = "✅ {$ecoleCount} école(s) trouvée(s)";
            }
        }

        // 2. Vérifier les colonnes ecole_id sur toutes les tables critiques
        $tablesRequiringEcoleId = [
            'users' => 'critique',
            'membres' => 'critique',
            'cours' => 'critique',
            'paiements' => 'important',
            'presences' => 'important',
            'factures' => 'important',
            'progression_ceintures' => 'normal',
            'examens' => 'normal',
        ];

        foreach ($tablesRequiringEcoleId as $table => $niveau) {
            if (! Schema::hasTable($table)) {
                $warnings[] = "⚠️ Table '{$table}' n'existe pas";

                continue;
            }

            if (! Schema::hasColumn($table, 'ecole_id')) {
                if ($niveau === 'critique') {
                    $errors[] = "❌ Colonne 'ecole_id' MANQUANTE sur table '{$table}' (CRITIQUE)";
                } else {
                    $warnings[] = "⚠️ Colonne 'ecole_id' manquante sur table '{$table}' ({$niveau})";
                }
            } else {
                $success[] = "✅ Table '{$table}' a la colonne 'ecole_id'";

                // Vérifier les données orphelines
                if (Schema::hasTable('ecoles')) {
                    $orphans = DB::table($table)
                        ->whereNull('ecole_id')
                        ->orWhereNotIn('ecole_id', DB::table('ecoles')->pluck('id'))
                        ->count();

                    if ($orphans > 0) {
                        $warnings[] = "⚠️ {$orphans} enregistrement(s) orphelin(s) dans '{$table}'";
                    }
                }
            }
        }

        // 3. Vérifier les contraintes de clés étrangères
        $this->command->info("\n📋 Vérification des contraintes FK:");
        $dbName = DB::getDatabaseName();

        foreach ($tablesRequiringEcoleId as $table => $niveau) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'ecole_id')) {
                continue;
            }

            $constraint = DB::table('information_schema.KEY_COLUMN_USAGE')
                ->where('TABLE_SCHEMA', $dbName)
                ->where('TABLE_NAME', $table)
                ->where('COLUMN_NAME', 'ecole_id')
                ->whereNotNull('REFERENCED_TABLE_NAME')
                ->first();

            if ($constraint) {
                $success[] = "✅ FK '{$table}.ecole_id' → '{$constraint->REFERENCED_TABLE_NAME}.{$constraint->REFERENCED_COLUMN_NAME}'";
            } else {
                $warnings[] = "⚠️ Pas de contrainte FK sur '{$table}.ecole_id'";
            }
        }

        // 4. Vérifier les rôles Spatie
        $this->command->info("\n🔐 Vérification des rôles:");
        if (Schema::hasTable('roles')) {
            $roles = DB::table('roles')->pluck('name')->toArray();
            $expectedRoles = ['superadmin', 'admin', 'instructeur', 'membre'];

            foreach ($expectedRoles as $role) {
                if (in_array($role, $roles)) {
                    $success[] = "✅ Rôle '{$role}' existe";
                } else {
                    $warnings[] = "⚠️ Rôle '{$role}' manquant";
                }
            }

            // Vérifier les rôles obsolètes
            $obsoleteRoles = ['admin_ecole', 'super-admin', 'gestionnaire'];
            foreach ($obsoleteRoles as $role) {
                if (in_array($role, $roles)) {
                    $warnings[] = "⚠️ Rôle obsolète '{$role}' toujours présent";
                }
            }
        } else {
            $errors[] = "❌ Table 'roles' n'existe pas";
        }

        // 5. Résumé
        $this->command->info("\n========================================");
        $this->command->info('           RÉSUMÉ DU TEST');
        $this->command->info('========================================');

        if (! empty($success)) {
            $this->command->info("\n✅ SUCCÈS (".count($success).'):');
            foreach ($success as $msg) {
                $this->command->info('  '.$msg);
            }
        }

        if (! empty($warnings)) {
            $this->command->warn("\n⚠️ AVERTISSEMENTS (".count($warnings).'):');
            foreach ($warnings as $msg) {
                $this->command->warn('  '.$msg);
            }
        }

        if (! empty($errors)) {
            $this->command->error("\n❌ ERREURS CRITIQUES (".count($errors).'):');
            foreach ($errors as $msg) {
                $this->command->error('  '.$msg);
            }

            $this->command->error("\n🔧 ACTIONS REQUISES:");
            $this->command->error('1. Exécutez: php artisan migrate');
            $this->command->error("2. Si erreur, vérifiez l'ordre des migrations");
            $this->command->error('3. Relancez ce test après correction');
        } else {
            $this->command->info("\n✅ STRUCTURE MONO-ÉCOLE VALIDE!");
        }

        // Score final
        $totalChecks = count($success) + count($warnings) + count($errors);
        $score = round((count($success) / $totalChecks) * 100, 1);

        $this->command->info("\n📊 Score d'intégrité: {$score}%");

        if ($score < 50) {
            $this->command->error('⚠️ Score critique - Corrections urgentes requises!');
        } elseif ($score < 80) {
            $this->command->warn('⚠️ Score moyen - Améliorations recommandées');
        } else {
            $this->command->info('✅ Score satisfaisant');
        }
    }
}
