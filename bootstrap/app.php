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
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias des middlewares StudiosDB
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'enforce2fa' => \App\Http\Middleware\Enforce2FA::class,
            'log.superadmin' => \App\Http\Middleware\LogSuperAdminActions::class,
        ]);

        // Middleware web par défaut
        $middleware->web(append: [
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Middleware API avec Sanctum
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
        
        // Middleware global pour toutes les requêtes
        $middleware->append([
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configuration des exceptions personnalisées StudiosDB
        $exceptions->report(function (\Throwable $e) {
            // Logger les erreurs importantes
            if ($e instanceof \Illuminate\Database\QueryException) {
                \Log::channel('superadmin')->error('Database Query Error', [
                    'message' => $e->getMessage(),
                    'user_id' => auth()->id(),
                    'url' => request()->url(),
                ]);
            }
        });
    })->create();
