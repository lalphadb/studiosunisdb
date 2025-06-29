<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class AuditStudiosUnisDB extends Command
{
    protected $signature = 'studiosdb:audit';
    protected $description = 'Audit complet de la structure et sécurité du projet StudiosUnisDB';

    public function handle()
    {
        $rapport = [];

        // 1. Structure
        $rapport['structure'] = $this->checkStructure();

        // 2. Contrôleurs
        $rapport['controleurs'] = $this->checkControllers();

        // 3. Modèles
        $rapport['modeles'] = $this->checkModels();

        // 4. Policies
        $rapport['policies'] = $this->checkPolicies();

        // 5. Vues
        $rapport['vues'] = $this->checkViews();

        // 6. Routes
        $rapport['routes'] = $this->checkRoutes();

        // 7. Sécurité env
        $rapport['env'] = $this->checkEnv();

        // 8. Tables DB
        $rapport['tables'] = $this->checkTables();

        // 9. Permissions
        $rapport['permissions'] = $this->checkPermissions();

        // 10. Score multi-tenant
        $rapport['multi_tenant'] = $this->scoreMultiTenant();

        // Sauvegarde rapport
        $this->saveAudit($rapport);

        $this->info('✅ Audit terminé. Rapport dans /audit/');
    }

    protected function checkStructure()
    {
        $res = [];
        $required = [
            base_path('.env'),
            base_path('composer.json'),
            base_path('artisan'),
            app_path('Http/Controllers/Admin'),
            app_path('Models'),
            app_path('Policies'),
            resource_path('views/admin'),
            resource_path('views/layouts'),
            base_path('routes'),
        ];
        foreach ($required as $path) {
            $res[$path] = File::exists($path) ? 'OK' : 'Manquant';
        }
        return $res;
    }

    protected function checkControllers()
    {
        $res = [];
        $ctrlPath = app_path('Http/Controllers/Admin');
        $controllers = File::exists($ctrlPath) ? File::files($ctrlPath) : [];
        foreach ($controllers as $file) {
            $content = File::get($file->getPathname());
            $hasMiddleware = str_contains($content, 'HasMiddleware');
            $res[$file->getFilename()] = [
                'has_middleware' => $hasMiddleware ? 'OK' : '❌',
                'multi_tenant' => (str_contains($content, 'ecole_id') && str_contains($content, 'auth()->user()')) ? 'Probable' : 'À vérifier',
            ];
        }
        return $res;
    }

    protected function checkModels()
    {
        $res = [];
        $modelsPath = app_path('Models');
        $models = File::exists($modelsPath) ? File::files($modelsPath) : [];
        foreach ($models as $file) {
            $content = File::get($file->getPathname());
            $res[$file->getFilename()] = [
                'extends_Model' => str_contains($content, 'extends Model') ? 'OK' : '❌',
                'fillable' => str_contains($content, '$fillable') ? 'OK' : '❌',
                'relations' => preg_match_all('/(belongsTo|hasMany|hasOne|belongsToMany)/', $content, $m),
            ];
        }
        return $res;
    }

    protected function checkPolicies()
    {
        $res = [];
        $policyPath = app_path('Policies');
        $policies = File::exists($policyPath) ? File::files($policyPath) : [];
        foreach ($policies as $file) {
            $content = File::get($file->getPathname());
            $res[$file->getFilename()] = [
                'viewAny' => str_contains($content, 'viewAny') ? 'OK' : '❌',
                'update' => str_contains($content, 'update') ? 'OK' : '❌',
                // Ajoute d'autres méthodes clés si besoin
            ];
        }
        return $res;
    }

    protected function checkViews()
    {
        $res = [];
        $modules = ['ecoles', 'membres', 'cours', 'presences'];
        foreach ($modules as $mod) {
            $dir = resource_path("views/admin/$mod");
            $vues = ['index.blade.php', 'create.blade.php', 'edit.blade.php', 'show.blade.php'];
            foreach ($vues as $vue) {
                $path = "$dir/$vue";
                $res["$mod/$vue"] = File::exists($path) ? 'OK' : 'Manquante';
            }
        }
        return $res;
    }

    protected function checkRoutes()
    {
        $routes = Route::getRoutes();
        $routesList = [];
        foreach ($routes as $route) {
            $routesList[] = [
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'middleware' => $route->gatherMiddleware(),
            ];
        }
        return [
            'total' => count($routesList),
            'admin_routes' => collect($routesList)->filter(fn($r) => str_contains($r['uri'], 'admin'))->count(),
        ];
    }

    protected function checkEnv()
    {
        $env = File::exists(base_path('.env')) ? File::get(base_path('.env')) : '';
        return [
            'app_key' => str_contains($env, 'APP_KEY=base64:') ? 'OK' : 'Manquant',
            'app_debug' => str_contains($env, 'APP_DEBUG=false') ? 'OK' : 'Attention: Debug activé',
            'db_password' => str_contains($env, 'DB_PASSWORD=') ? 'OK' : 'Manquant',
        ];
    }

    protected function checkTables()
    {
        $tables = ['users', 'ecoles', 'membres', 'cours', 'presences', 'ceintures', 'permissions', 'roles'];
        $res = [];
        foreach ($tables as $table) {
            try {
                $res[$table] = DB::table($table)->count() . ' enregistrements';
            } catch (\Exception $e) {
                $res[$table] = 'Manquante/Erreur';
            }
        }
        return $res;
    }

    protected function checkPermissions()
    {
        try {
            $count = DB::table('permissions')->count();
            $roles = DB::table('roles')->count();
            $attributions = DB::table('role_has_permissions')->count();
            return [
                'permissions' => $count,
                'roles' => $roles,
                'role_has_permissions' => $attributions,
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    protected function scoreMultiTenant()
    {
        // À personnaliser selon ta logique ; ici, scan simple sur les contrôleurs
        $ctrlPath = app_path('Http/Controllers/Admin');
        $controllers = File::exists($ctrlPath) ? File::files($ctrlPath) : [];
        $score = 0; $total = 0;
        foreach ($controllers as $file) {
            $total++;
            $content = File::get($file->getPathname());
            if (str_contains($content, 'ecole_id') && str_contains($content, 'auth()->user()')) $score++;
        }
        return "$score/$total contrôleurs multi-tenant";
    }

    protected function saveAudit($rapport)
    {
        $dir = base_path('audit');
        if (!File::exists($dir)) File::makeDirectory($dir);
        $file = $dir . '/audit_' . date('Ymd_His') . '.json';
        File::put($file, json_encode($rapport, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
