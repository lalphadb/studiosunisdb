<?php
// Diagnostic StudiosDB - Problème de navigation
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Route;

echo "\n=== DIAGNOSTIC NAVIGATION STUDIOSDB ===\n\n";

// 1. Vérifier les routes
echo "1. ROUTES DISPONIBLES:\n";
$routes = Route::getRoutes();
$webRoutes = [];
foreach ($routes as $route) {
    if (in_array('web', $route->middleware())) {
        $uri = $route->uri();
        $name = $route->getName();
        $methods = implode('|', $route->methods());
        if ($name) {
            echo "   - {$name}: {$methods} {$uri}\n";
            $webRoutes[$name] = $uri;
        }
    }
}

// 2. Vérifier HandleInertiaRequests
echo "\n2. MIDDLEWARE INERTIA:\n";
if (class_exists(\App\Http\Middleware\HandleInertiaRequests::class)) {
    echo "   ✓ HandleInertiaRequests existe\n";
    $inertia = new \App\Http\Middleware\HandleInertiaRequests();
    echo "   - Root view: app\n";
} else {
    echo "   ✗ HandleInertiaRequests manquant!\n";
}

// 3. Vérifier les utilisateurs
echo "\n3. UTILISATEURS DANS LA DB:\n";
try {
    $users = User::with('roles')->limit(5)->get();
    foreach ($users as $user) {
        $roles = $user->roles->pluck('name')->join(', ') ?: 'Aucun rôle';
        echo "   - {$user->email} [{$roles}]\n";
    }
} catch (Exception $e) {
    echo "   Erreur: " . $e->getMessage() . "\n";
}

// 4. Vérifier la configuration auth
echo "\n4. CONFIG AUTH:\n";
echo "   - Guard par défaut: " . config('auth.defaults.guard') . "\n";
echo "   - Provider: " . config('auth.guards.web.provider') . "\n";
echo "   - APP_URL: " . config('app.url') . "\n";
echo "   - Session driver: " . config('session.driver') . "\n";

// 5. Vérifier les contrôleurs
echo "\n5. CONTRÔLEURS:\n";
$controllers = [
    'DashboardController',
    'MembreController',
    'CoursController',
    'UserController'
];
foreach ($controllers as $controller) {
    $class = "App\\Http\\Controllers\\{$controller}";
    if (class_exists($class)) {
        echo "   ✓ {$controller}\n";
    } else {
        echo "   ✗ {$controller} manquant\n";
    }
}

// 6. Recommandations
echo "\n6. RECOMMANDATIONS:\n";
echo "   1. Vérifier que l'utilisateur est connecté\n";
echo "   2. Vider le cache: php artisan optimize:clear\n";
echo "   3. Créer un utilisateur admin: php artisan tinker\n";
echo "      >>> \$user = User::first();\n";
echo "      >>> \$user->assignRole('admin_ecole');\n";
echo "   4. Vérifier les cookies dans le navigateur\n";
echo "   5. Tester avec: http://localhost:8000/login\n";

echo "\n=== FIN DU DIAGNOSTIC ===\n";
