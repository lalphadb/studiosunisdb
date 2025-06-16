<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class AuditStudiosUnisDB extends Command
{
    protected $signature = 'audit:studiosunisdb';
    protected $description = 'Audit complet StudiosUnisDB - Standards Laravel 12.17 Professionnels';

    public function handle()
    {
        $this->info('🎯 AUDIT COMPLET STUDIOSUNISDB v3.7.0.0');
        $this->info('Standards Laravel 12.17 Professionnels');
        $this->line('================================================');

        // Créer dossier audit
        $auditDir = base_path('audite');
        if (!File::exists($auditDir)) {
            File::makeDirectory($auditDir);
        }

        $rapport = [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'version' => 'v3.7.0.0',
            'laravel_version' => app()->version(),
        ];

        // 1. AUDIT STRUCTURE PROJET
        $this->info('📁 Audit Structure Projet...');
        $rapport['structure'] = $this->auditerStructure();

        // 2. AUDIT CONTRÔLEURS
        $this->info('🎮 Audit Contrôleurs Admin...');
        $rapport['controleurs'] = $this->auditerControleurs();

        // 3. AUDIT MODÈLES
        $this->info('📊 Audit Modèles Eloquent...');
        $rapport['modeles'] = $this->auditerModeles();

        // 4. AUDIT VUES & LAYOUTS
        $this->info('🎨 Audit Vues & Layouts...');
        $rapport['vues'] = $this->auditerVues();

        // 5. AUDIT ROUTES
        $this->info('🛣️ Audit Routes...');
        $rapport['routes'] = $this->auditerRoutes();

        // 6. AUDIT BASE DE DONNÉES
        $this->info('🗄️ Audit Base de Données...');
        $rapport['database'] = $this->auditerDatabase();

        // 7. AUDIT SÉCURITÉ
        $this->info('🔒 Audit Sécurité...');
        $rapport['securite'] = $this->auditerSecurite();

        // 8. AUDIT PERMISSIONS SPATIE
        $this->info('👥 Audit Permissions Spatie...');
        $rapport['permissions'] = $this->auditerPermissions();

        // 9. AUDIT CONFORMITÉ STUDIOSUNISDB
        $this->info('🥋 Audit Conformité StudiosUnisDB...');
        $rapport['conformite'] = $this->auditerConformite();

        // GÉNÉRER RAPPORT
        $this->genererRapport($rapport, $auditDir);
        
        $this->info('✅ AUDIT TERMINÉ - Voir rapport dans /audite/');
    }

    protected function auditerStructure()
    {
        $resultats = [];
        
        // Fichiers essentiels
        $fichiersEssentiels = [
            '.env' => base_path('.env'),
            'composer.json' => base_path('composer.json'),
            'artisan' => base_path('artisan'),
        ];

        foreach ($fichiersEssentiels as $nom => $chemin) {
            $resultats[$nom] = File::exists($chemin) ? '✅ Présent' : '❌ Manquant';
        }

        // Dossiers structure Laravel
        $dossiersLaravel = [
            'app/Http/Controllers/Admin',
            'app/Models', 
            'app/Policies',
            'database/migrations',
            'resources/views/admin',
            'resources/views/layouts',
            'routes'
        ];

        foreach ($dossiersLaravel as $dossier) {
            $chemin = base_path($dossier);
            $resultats["dossier_$dossier"] = File::exists($chemin) ? '✅ Présent' : '❌ Manquant';
        }

        return $resultats;
    }

    protected function auditerControleurs()
    {
        $resultats = [];
        $controleurs = [
            'DashboardController',
            'EcoleController', 
            'MembreController',
            'CoursController',
            'PresenceController'
        ];

        foreach ($controleurs as $controleur) {
            $chemin = app_path("Http/Controllers/Admin/{$controleur}.php");
            if (File::exists($chemin)) {
                $contenu = File::get($chemin);
                $resultats[$controleur] = [
                    'existe' => '✅ Présent',
                    'namespace' => str_contains($contenu, 'namespace App\Http\Controllers\Admin') ? '✅' : '❌',
                    'methodes_crud' => $this->compterMethodesCRUD($contenu),
                    'middleware' => str_contains($contenu, 'middleware') ? '✅' : '❌',
                    'syntaxe' => $this->verifierSyntaxePHP($chemin)
                ];
            } else {
                $resultats[$controleur] = ['existe' => '❌ Manquant'];
            }
        }

        return $resultats;
    }

    protected function auditerModeles()
    {
        $resultats = [];
        $modeles = [
            'User', 'Ecole', 'Membre', 'Cours', 'Presence', 
            'Ceinture', 'MembreCeinture', 'CoursHoraire'
        ];

        foreach ($modeles as $modele) {
            $chemin = app_path("Models/{$modele}.php");
            if (File::exists($chemin)) {
                $contenu = File::get($chemin);
                $resultats[$modele] = [
                    'existe' => '✅ Présent',
                    'extends_model' => str_contains($contenu, 'extends Model') ? '✅' : '❌',
                    'fillable' => str_contains($contenu, 'fillable') ? '✅' : '❌',
                    'relations' => $this->compterRelations($contenu),
                    'syntaxe' => $this->verifierSyntaxePHP($chemin)
                ];
            } else {
                $resultats[$modele] = ['existe' => '❌ Manquant'];
            }
        }

        return $resultats;
    }

    protected function auditerVues()
    {
        $resultats = [];
        
        // Vérifier layouts
        $layouts = ['admin.blade.php', 'guest.blade.php'];
        foreach ($layouts as $layout) {
            $chemin = resource_path("views/layouts/{$layout}");
            $resultats["layout_$layout"] = File::exists($chemin) ? '✅ Présent' : '❌ Manquant';
        }

        // Vérifier modules admin
        $modules = ['ecoles', 'membres', 'cours', 'presences'];
        foreach ($modules as $module) {
            $dossier = resource_path("views/admin/{$module}");
            if (File::exists($dossier)) {
                $vues = ['index.blade.php', 'create.blade.php', 'edit.blade.php', 'show.blade.php'];
                $vuesPresentes = 0;
                
                foreach ($vues as $vue) {
                    if (File::exists("{$dossier}/{$vue}")) {
                        $vuesPresentes++;
                        
                        // Vérifier cohérence @extends
                        $contenu = File::get("{$dossier}/{$vue}");
                        $resultats["{$module}_{$vue}"] = [
                            'existe' => '✅',
                            'extends' => str_contains($contenu, '@extends(\'layouts.admin\')') ? '✅' : '❌',
                            'composants' => str_contains($contenu, '<x-') ? '⚠️ Utilise composants' : '✅ @extends'
                        ];
                    }
                }
                
                $resultats["module_{$module}"] = "✅ {$vuesPresentes}/4 vues";
            } else {
                $resultats["module_{$module}"] = '❌ Dossier manquant';
            }
        }

        return $resultats;
    }

    protected function auditerRoutes()
    {
        $resultats = [];
        
        // Vérifier fichiers routes
        $fichierRoutes = ['web.php', 'admin.php', 'auth.php'];
        foreach ($fichierRoutes as $fichier) {
            $chemin = base_path("routes/{$fichier}");
            $resultats["fichier_{$fichier}"] = File::exists($chemin) ? '✅ Présent' : '❌ Manquant';
        }

        // Compter routes par type
        try {
            $routes = collect(\Route::getRoutes())->map(function ($route) {
                return [
                    'method' => implode('|', $route->methods()),
                    'uri' => $route->uri(),
                    'name' => $route->getName()
                ];
            });

            $resultats['total_routes'] = $routes->count();
            $resultats['routes_admin'] = $routes->filter(fn($r) => str_starts_with($r['uri'], 'admin'))->count();
            $resultats['routes_presences'] = $routes->filter(fn($r) => str_contains($r['name'] ?? '', 'presence'))->count();
            
        } catch (\Exception $e) {
            $resultats['erreur_routes'] = $e->getMessage();
        }

        return $resultats;
    }

    protected function auditerDatabase()
    {
        $resultats = [];
        
        try {
            // Test connexion
            DB::connection()->getPdo();
            $resultats['connexion'] = '✅ OK';
            
            // Compter tables
            $tables = DB::select('SHOW TABLES');
            $resultats['nombre_tables'] = count($tables);
            
            // Vérifier tables StudiosUnisDB
            $tablesAttendues = [
                'users', 'ecoles', 'membres', 'cours', 'presences', 
                'ceintures', 'permissions', 'roles'
            ];
            
            foreach ($tablesAttendues as $table) {
                try {
                    $count = DB::table($table)->count();
                    $resultats["table_{$table}"] = "✅ {$count} enregistrements";
                } catch (\Exception $e) {
                    $resultats["table_{$table}"] = '❌ Manquante/Erreur';
                }
            }
            
            // État migrations
            $migrations = DB::table('migrations')->count();
            $resultats['migrations_executees'] = $migrations;
            
        } catch (\Exception $e) {
            $resultats['connexion'] = '❌ Erreur: ' . $e->getMessage();
        }

        return $resultats;
    }

    protected function auditerSecurite()
    {
        $resultats = [];
        
        // Vérifier .env
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $env = File::get($envPath);
            $resultats['app_key'] = str_contains($env, 'APP_KEY=base64:') ? '✅ Définie' : '❌ Manquante';
            $resultats['app_debug'] = str_contains($env, 'APP_DEBUG=false') ? '✅ Production' : '⚠️ Debug activé';
            $resultats['db_password'] = str_contains($env, 'DB_PASSWORD=') ? '✅ Défini' : '❌ Manquant';
        }
        
        // Vérifier middleware
        $middleware = app_path('Http/Kernel.php');
        if (File::exists($middleware)) {
            $contenu = File::get($middleware);
            $resultats['csrf_middleware'] = str_contains($contenu, 'VerifyCsrfToken') ? '✅' : '❌';
        }

        return $resultats;
    }

    protected function auditerPermissions()
    {
        $resultats = [];
        
        try {
            // Compter permissions Spatie
            $permissions = DB::table('permissions')->count();
            $roles = DB::table('roles')->count();
            $rolePermissions = DB::table('role_has_permissions')->count();
            
            $resultats['permissions_total'] = $permissions;
            $resultats['roles_total'] = $roles;
            $resultats['attributions'] = $rolePermissions;
            
            // Vérifier permissions présences
            $permissionsPresences = DB::table('permissions')
                ->where('name', 'like', 'presence%')
                ->count();
            $resultats['permissions_presences'] = $permissionsPresences;
            
        } catch (\Exception $e) {
            $resultats['erreur'] = $e->getMessage();
        }

        return $resultats;
    }

    protected function auditerConformite()
    {
        $resultats = [];
        
        // Vérifier branding StudiosUnisDB
        $adminLayout = resource_path('views/layouts/admin.blade.php');
        if (File::exists($adminLayout)) {
            $contenu = File::get($adminLayout);
            $resultats['branding'] = str_contains($contenu, 'StudiosUnisDB') ? '✅ Présent' : '❌ Manquant';
            $resultats['theme_dark'] = str_contains($contenu, '#0f172a') ? '✅ Thème dark' : '❌';
            $resultats['emoji_karatie'] = str_contains($contenu, '🥋') ? '✅ Emoji karaté' : '❌';
        }
        
        // Vérifier structure multi-école
        $resultats['ecoles_configurees'] = DB::table('ecoles')->count();
        $resultats['superadmin_existe'] = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'superadmin')
            ->exists() ? '✅' : '❌';

        return $resultats;
    }

    protected function compterMethodesCRUD($contenu)
    {
        $methodes = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
        $count = 0;
        foreach ($methodes as $methode) {
            if (str_contains($contenu, "function {$methode}(")) {
                $count++;
            }
        }
        return "{$count}/7 méthodes CRUD";
    }

    protected function compterRelations($contenu)
    {
        $relations = ['belongsTo', 'hasMany', 'hasOne', 'belongsToMany'];
        $count = 0;
        foreach ($relations as $relation) {
            $count += substr_count($contenu, $relation);
        }
        return "{$count} relations";
    }

    protected function verifierSyntaxePHP($chemin)
    {
        $output = shell_exec("php -l {$chemin} 2>&1");
        return str_contains($output, 'No syntax errors') ? '✅ Syntaxe OK' : '❌ Erreur syntaxe';
    }

    protected function genererRapport($rapport, $auditDir)
    {
        $timestamp = now()->format('Ymd_His');
        
        // Rapport détaillé
        $rapportFile = "{$auditDir}/audit_studiosunisdb_{$timestamp}.json";
        File::put($rapportFile, json_encode($rapport, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        // Rapport résumé
        $resumeFile = "{$auditDir}/resume_audit_{$timestamp}.txt";
        $resume = $this->genererResume($rapport);
        File::put($resumeFile, $resume);
        
        $this->info("📄 Rapport détaillé: {$rapportFile}");
        $this->info("📋 Résumé: {$resumeFile}");
    }

    protected function genererResume($rapport)
    {
        $resume = "🎯 AUDIT STUDIOSUNISDB {$rapport['version']}\n";
        $resume .= "Date: {$rapport['timestamp']}\n";
        $resume .= "Laravel: {$rapport['laravel_version']}\n";
        $resume .= str_repeat('=', 50) . "\n\n";
        
        // Calculer score global
        $total = 0;
        $ok = 0;
        
        foreach ($rapport as $section => $donnees) {
            if (is_array($donnees)) {
                foreach ($donnees as $cle => $valeur) {
                    $total++;
                    if (is_string($valeur) && str_contains($valeur, '✅')) {
                        $ok++;
                    }
                }
            }
        }
        
        $score = $total > 0 ? round(($ok / $total) * 100, 1) : 0;
        
        $resume .= "📊 SCORE GLOBAL: {$score}% ({$ok}/{$total})\n\n";
        
        $resume .= "🔍 RÉSUMÉ PAR SECTION:\n";
        foreach ($rapport as $section => $donnees) {
            if (is_array($donnees) && !in_array($section, ['timestamp', 'version', 'laravel_version'])) {
                $sectionOk = 0;
                $sectionTotal = count($donnees);
                
                foreach ($donnees as $valeur) {
                    if (is_string($valeur) && str_contains($valeur, '✅')) {
                        $sectionOk++;
                    }
                }
                
                $sectionScore = $sectionTotal > 0 ? round(($sectionOk / $sectionTotal) * 100, 1) : 0;
                $resume .= sprintf("%-15s: %5.1f%% (%d/%d)\n", strtoupper($section), $sectionScore, $sectionOk, $sectionTotal);
            }
        }
        
        return $resume;
    }
}
