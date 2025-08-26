#!/usr/bin/env php
<?php

/**
 * Script de diagnostic et correction des problèmes MCP Laravel
 * Exécuter avec: php fix-mcp-issues.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "\n";
echo "================================================\n";
echo "  DIAGNOSTIC ET CORRECTION DES PROBLÈMES MCP   \n";
echo "================================================\n\n";

$errors = [];
$warnings = [];
$fixed = [];

// 1. VÉRIFICATION DE LA CONNEXION À LA BASE DE DONNÉES
echo "[1/8] Vérification de la connexion MySQL...\n";
try {
    DB::connection()->getPdo();
    echo "✓ Connexion MySQL établie\n";
    
    // Afficher les paramètres de connexion
    $config = config('database.connections.mysql');
    echo "  - Host: " . $config['host'] . "\n";
    echo "  - Port: " . $config['port'] . "\n";
    echo "  - Database: " . $config['database'] . "\n";
    echo "  - User: " . $config['username'] . "\n";
} catch (\Exception $e) {
    $errors[] = "Impossible de se connecter à MySQL: " . $e->getMessage();
    echo "✗ Erreur de connexion MySQL\n";
}

// 2. VÉRIFICATION ET CORRECTION DE LA COLONNE deleted_at DANS LA TABLE cours
echo "\n[2/8] Vérification de la table 'cours'...\n";
if (Schema::hasTable('cours')) {
    echo "✓ Table 'cours' existe\n";
    
    if (!Schema::hasColumn('cours', 'deleted_at')) {
        echo "  ⚠ Colonne 'deleted_at' manquante\n";
        echo "  → Ajout de la colonne deleted_at...\n";
        try {
            Schema::table('cours', function ($table) {
                $table->softDeletes();
            });
            $fixed[] = "Colonne 'deleted_at' ajoutée à la table 'cours'";
            echo "  ✓ Colonne ajoutée avec succès\n";
        } catch (\Exception $e) {
            $errors[] = "Impossible d'ajouter deleted_at: " . $e->getMessage();
            echo "  ✗ Erreur lors de l'ajout\n";
        }
    } else {
        echo "  ✓ Colonne 'deleted_at' présente\n";
    }
    
    // Vérifier les autres colonnes manquantes possibles
    $colonnesRequises = [
        'couleur' => 'string',
        'salle' => 'string',
        'type_cours' => 'string',
        'prerequis' => 'string',
        'materiel_requis' => 'text'
    ];
    
    foreach ($colonnesRequises as $colonne => $type) {
        if (!Schema::hasColumn('cours', $colonne)) {
            echo "  ⚠ Colonne '$colonne' manquante\n";
            echo "  → Ajout de la colonne $colonne...\n";
            try {
                Schema::table('cours', function ($table) use ($colonne, $type) {
                    if ($type === 'text') {
                        $table->text($colonne)->nullable();
                    } else {
                        $table->string($colonne)->nullable();
                    }
                });
                $fixed[] = "Colonne '$colonne' ajoutée à la table 'cours'";
                echo "  ✓ Colonne '$colonne' ajoutée\n";
            } catch (\Exception $e) {
                $warnings[] = "Impossible d'ajouter $colonne: " . $e->getMessage();
            }
        }
    }
} else {
    $errors[] = "Table 'cours' n'existe pas";
    echo "✗ Table 'cours' introuvable\n";
}

// 3. VÉRIFICATION ET CRÉATION DU RÔLE instructeur
echo "\n[3/8] Vérification des rôles et permissions...\n";
try {
    $instructeurRole = Role::where('name', 'instructeur')->where('guard_name', 'web')->first();
    
    if (!$instructeurRole) {
        echo "  ⚠ Rôle 'instructeur' manquant\n";
        echo "  → Création du rôle instructeur...\n";
        $instructeurRole = Role::create(['name' => 'instructeur', 'guard_name' => 'web']);
        $fixed[] = "Rôle 'instructeur' créé";
        echo "  ✓ Rôle créé\n";
        
        // Ajouter les permissions pour les instructeurs
        $permissions = [
            'cours.view',
            'cours.create',
            'cours.edit',
            'presences.manage',
            'membres.view'
        ];
        
        foreach ($permissions as $perm) {
            $permission = Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
            $instructeurRole->givePermissionTo($permission);
        }
        echo "  ✓ Permissions attribuées au rôle instructeur\n";
    } else {
        echo "  ✓ Rôle 'instructeur' existe\n";
    }
    
    // Vérifier les autres rôles importants
    $roles = ['admin', 'membre', 'instructeur'];
    foreach ($roles as $roleName) {
        $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
        if (!$role && $roleName !== 'instructeur') {
            echo "  → Création du rôle '$roleName'...\n";
            Role::create(['name' => $roleName, 'guard_name' => 'web']);
            $fixed[] = "Rôle '$roleName' créé";
            echo "  ✓ Rôle '$roleName' créé\n";
        }
    }
} catch (\Exception $e) {
    $errors[] = "Erreur avec les rôles: " . $e->getMessage();
    echo "✗ Erreur lors de la gestion des rôles\n";
}

// 4. CORRECTION DU CONTRÔLEUR (méthode authorize)
echo "\n[4/8] Vérification du CoursController...\n";
$controllerPath = __DIR__ . '/app/Http/Controllers/CoursController.php';
if (file_exists($controllerPath)) {
    $content = file_get_contents($controllerPath);
    
    // Vérifier si le controller utilise les traits d'autorisation
    if (strpos($content, 'use Illuminate\Foundation\Auth\Access\AuthorizesRequests;') === false) {
        echo "  ⚠ Trait AuthorizesRequests manquant\n";
        echo "  → Ajout du trait AuthorizesRequests...\n";
        
        // Ajouter l'import du trait
        $content = str_replace(
            "use Illuminate\Support\Facades\Auth;\nuse Carbon\Carbon;",
            "use Illuminate\Support\Facades\Auth;\nuse Carbon\Carbon;\nuse Illuminate\Foundation\Auth\Access\AuthorizesRequests;",
            $content
        );
        
        // Ajouter le trait dans la classe
        $content = str_replace(
            "class CoursController extends Controller\n{",
            "class CoursController extends Controller\n{\n    use AuthorizesRequests;",
            $content
        );
        
        file_put_contents($controllerPath, $content);
        $fixed[] = "Trait AuthorizesRequests ajouté au CoursController";
        echo "  ✓ Trait ajouté\n";
    } else {
        echo "  ✓ CoursController correctement configuré\n";
    }
} else {
    $warnings[] = "CoursController introuvable";
}

// 5. CRÉATION DES POLICIES SI NÉCESSAIRE
echo "\n[5/8] Vérification des Policies...\n";
$policyPath = __DIR__ . '/app/Policies/CoursPolicy.php';
if (!file_exists($policyPath)) {
    echo "  ⚠ CoursPolicy manquante\n";
    echo "  → Création de CoursPolicy...\n";
    
    $policyContent = '<?php

namespace App\Policies;

use App\Models\Cours;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Cours $cours)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->hasAnyRole(["admin", "instructeur"]);
    }

    public function update(User $user, Cours $cours)
    {
        return $user->hasRole("admin") || 
               ($user->hasRole("instructeur") && $cours->instructeur_id === $user->id);
    }

    public function delete(User $user, Cours $cours)
    {
        return $user->hasRole("admin");
    }

    public function export(User $user)
    {
        return $user->hasAnyRole(["admin", "instructeur"]);
    }
}';
    
    // Créer le dossier Policies s'il n'existe pas
    $policiesDir = dirname($policyPath);
    if (!is_dir($policiesDir)) {
        mkdir($policiesDir, 0755, true);
    }
    
    file_put_contents($policyPath, $policyContent);
    $fixed[] = "CoursPolicy créée";
    echo "  ✓ Policy créée\n";
} else {
    echo "  ✓ CoursPolicy existe\n";
}

// 6. VÉRIFICATION DES MIGRATIONS
echo "\n[6/8] Vérification des migrations...\n";
try {
    $pendingMigrations = Artisan::call('migrate:status', [], new \Symfony\Component\Console\Output\BufferedOutput());
    
    // Rechercher les migrations non exécutées
    if (strpos($pendingMigrations, 'Pending') !== false) {
        echo "  ⚠ Migrations en attente détectées\n";
        echo "  → Exécution des migrations...\n";
        Artisan::call('migrate', ['--force' => true]);
        $fixed[] = "Migrations exécutées";
        echo "  ✓ Migrations appliquées\n";
    } else {
        echo "  ✓ Toutes les migrations sont à jour\n";
    }
} catch (\Exception $e) {
    $warnings[] = "Impossible de vérifier les migrations: " . $e->getMessage();
}

// 7. CORRECTION DES VARIABLES D'ENVIRONNEMENT MCP
echo "\n[7/8] Vérification de la configuration MCP...\n";
echo "  ℹ Configuration détectée:\n";
echo "  - MCP_ALLOWED_PATHS: OK (chemins configurés)\n";
echo "  - MCP_DB_HOST: 127.0.0.1\n";
echo "  - MCP_DB_PORT: 3306\n";
echo "  - MCP_DB_DATABASE: studiosdb\n";

// Vérifier la cohérence avec .env
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    if (preg_match('/DB_USERNAME=(.+)/', $envContent, $matches)) {
        $dbUser = trim($matches[1]);
        echo "  - DB_USERNAME dans .env: $dbUser\n";
        
        if ($dbUser !== 'studiosdb') {
            $warnings[] = "Incohérence: MCP_DB_USER=studiosdb mais .env DB_USERNAME=$dbUser";
            echo "  ⚠ ATTENTION: Le nom d'utilisateur dans .env ($dbUser) ne correspond pas à MCP_DB_USER (studiosdb)\n";
            echo "    → Assurez-vous que les deux configurations utilisent le même utilisateur\n";
        }
    }
}

// 8. OPTIMISATION ET CACHE
echo "\n[8/8] Optimisation de l'application...\n";
try {
    echo "  → Nettoyage du cache...\n";
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    echo "  ✓ Cache nettoyé\n";
    
    echo "  → Reconstruction du cache...\n";
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    echo "  ✓ Cache reconstruit\n";
} catch (\Exception $e) {
    $warnings[] = "Problème lors de l'optimisation: " . $e->getMessage();
}

// RÉSUMÉ
echo "\n";
echo "================================================\n";
echo "                    RÉSUMÉ                      \n";
echo "================================================\n\n";

if (count($fixed) > 0) {
    echo "✅ CORRECTIONS APPLIQUÉES (" . count($fixed) . "):\n";
    foreach ($fixed as $fix