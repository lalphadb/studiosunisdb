<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Routes admin séparées
            if (file_exists(base_path('routes/admin.php'))) {
                Route::middleware('web')
                    ->group(base_path('routes/admin.php'));
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias de middleware Laravel 12.19
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);
        
        // Redirection des invités
        $middleware->redirectGuestsTo('/login');
        
        // Redirection des utilisateurs connectés
        $middleware->redirectUsersTo('/admin');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Pas de gestion personnalisée pour éviter les erreurs de vue
    })->create();
