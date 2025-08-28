<?php
/**
 * Script de test rapide StudiosDB - Laravel 12.24
 * Usage: php test-quick.php
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "=== TEST RAPIDE STUDIOSDB ===\n";

try {
    // Test 1: Configuration Laravel
    echo "1. Version Laravel: " . app()->version() . "\n";
    
    // Test 2: Base de données
    echo "2. Test connexion DB: ";
    $pdo = DB::connection()->getPdo();
    echo "OK\n";
    
    // Test 3: Tables principales
    echo "3. Tables principales: ";
    $tables = ['users', 'cours', 'membres', 'ecoles'];
    $missing = [];
    foreach ($tables as $table) {
        if (!Schema::hasTable($table)) {
            $missing[] = $table;
        }
    }
    if (empty($missing)) {
        echo "OK\n";
    } else {
        echo "MANQUANTES: " . implode(', ', $missing) . "\n";
    }
    
    // Test 4: Utilisateurs
    echo "4. Utilisateurs: " . App\Models\User::count() . " trouvés\n";
    
    // Test 5: Rôles
    echo "5. Rôles système: ";
    if (class_exists('Spatie\Permission\Models\Role')) {
        echo Spatie\Permission\Models\Role::count() . " rôles\n";
    } else {
        echo "Spatie Permission non installé\n";
    }
    
    // Test 6: Premier utilisateur avec rôles
    $user = App\Models\User::with('roles')->first();
    if ($user) {
        echo "6. Premier utilisateur: {$user->email} - Rôles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
        
        // Test permissions
        echo "7. Permissions cours: ";
        echo "viewAny=" . ($user->can('viewAny', App\Models\Cours::class) ? 'OUI' : 'NON');
        echo ", create=" . ($user->can('create', App\Models\Cours::class) ? 'OUI' : 'NON') . "\n";
    } else {
        echo "6. Aucun utilisateur trouvé\n";
    }
    
    echo "\n✅ Test terminé avec succès\n";
    echo "Démarrer le serveur: php artisan serve --port=8001\n";
    
} catch (Exception $e) {
    echo "\n❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    // Solutions suggérées
    echo "\nSolutions:\n";
    echo "- php artisan migrate\n";
    echo "- php artisan db:seed\n";
    echo "- Vérifier fichier .env\n";
}
