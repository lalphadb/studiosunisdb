#!/bin/bash

echo "=== CRÉATION DES MIDDLEWARES StudiosDB ==="

# 1. AdminMiddleware
php artisan make:middleware AdminMiddleware
cat > app/Http/Middleware/AdminMiddleware.php << 'EOF'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasAnyRole(['super-admin', 'admin', 'gestionnaire'])) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
EOF

# 2. EcoleRestrictionMiddleware
php artisan make:middleware EcoleRestrictionMiddleware
cat > app/Http/Middleware/EcoleRestrictionMiddleware.php << 'EOF'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EcoleRestrictionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Super-admin peut tout voir
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // Définir l'école active dans la session
        if (!session()->has('ecole_id') && $user->ecole_id) {
            session(['ecole_id' => $user->ecole_id]);
        }

        return $next($request);
    }
}
EOF

# 3. LogSuperAdminActions
php artisan make:middleware LogSuperAdminActions
cat > app/Http/Middleware/LogSuperAdminActions.php << 'EOF'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LogSuperAdminActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check() && auth()->user()->hasRole('super-admin')) {
            Log::channel('superadmin')->info('Action Super Admin', [
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->nom_complet,
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}
EOF

echo -e "\n=== Configuration des middlewares dans bootstrap/app.php ==="
cat > update_bootstrap.php << 'EOF'
<?php
$content = file_get_contents('bootstrap/app.php');

// Ajouter les alias de middleware
$search = "->withMiddleware(function (Middleware \$middleware) {";
$replace = "->withMiddleware(function (Middleware \$middleware) {
        \$middleware->alias([
            'admin' => \\App\\Http\\Middleware\\AdminMiddleware::class,
            'ecole.restriction' => \\App\\Http\\Middleware\\EcoleRestrictionMiddleware::class,
            'log.superadmin' => \\App\\Http\\Middleware\\LogSuperAdminActions::class,
        ]);";

$content = str_replace($search, $replace, $content);
file_put_contents('bootstrap/app.php', $content);
echo "✓ bootstrap/app.php mis à jour\n";
EOF

php update_bootstrap.php
rm update_bootstrap.php

echo -e "\n=== Création du canal de log superadmin ==="
# Ajouter le canal dans config/logging.php
php -r "
\$config = require 'config/logging.php';
\$config['channels']['superadmin'] = [
    'driver' => 'daily',
    'path' => storage_path('logs/superadmin.log'),
    'level' => 'info',
    'days' => 30,
];
file_put_contents('config/logging.php', '<?php return ' . var_export(\$config, true) . ';');
"

echo -e "\n✓ Tous les middlewares ont été créés avec succès!"
echo -e "\nUtilisation dans les routes:"
echo "  ->middleware(['auth', 'admin'])           // Pour les pages admin"
echo "  ->middleware(['auth', 'ecole.restriction']) // Pour restreindre par école"
echo "  ->middleware(['log.superadmin'])           // Pour logger les actions super-admin"
