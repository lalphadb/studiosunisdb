<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Alias pour l'authentification
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
        ]);

        // Redirection pour les requêtes non authentifiées
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo('/dashboard');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Laravel 12 - Configuration par callbacks pour les erreurs HTTP spécifiques
        $exceptions->render(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 403) {
                // Si c'est une requête API, renvoyer JSON
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => $e->getMessage() ?: 'Accès non autorisé',
                        'status' => 403,
                    ], 403);
                }

                // Diagnostic pour le développement
                $diagnostic = null;
                if (app()->environment('local', 'development')) {
                    $user = auth()->user();
                    $diagnostic = [
                        'user_authenticated' => auth()->check(),
                        'user_id' => $user?->id,
                        'user_email' => $user?->email,
                        'user_roles' => $user?->getRoleNames()->toArray() ?? [],
                        'user_ecole_id' => $user?->ecole_id,
                        'requested_url' => $request->url(),
                        'method' => $request->method(),
                        'error_message' => $e->getMessage(),
                    ];
                }

                // Page d'erreur Inertia personnalisée pour 403
                return \Inertia\Inertia::render('Error403', [
                    'status' => 403,
                    'message' => $e->getMessage() ?: 'Accès non autorisé',
                    'diagnostic' => $diagnostic,
                ])->toResponse($request)->setStatusCode(403);
            }

            // Pour les autres erreurs, laisser Laravel gérer
            return null;
        });
    })->create();
