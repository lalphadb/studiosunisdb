<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        // Gestion personnalisée des erreurs HTTP pour Inertia
        if ($e instanceof HttpException) {
            $status = $e->getStatusCode();

            // Erreur 403 personnalisée avec diagnostic
            if ($status === 403) {
                return $this->render403($request, $e);
            }

            // Autres erreurs HTTP
            if (in_array($status, [404, 500, 503])) {
                return $this->renderInertiaError($request, $status, $e->getMessage());
            }
        }

        return $response;
    }

    /**
     * Render une erreur 403 personnalisée avec diagnostic
     */
    protected function render403($request, $exception)
    {
        // Si c'est une requête API, renvoyer JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $exception->getMessage() ?: 'Accès non autorisé',
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
                'error_message' => $exception->getMessage(),
            ];
        }

        // Page d'erreur Inertia personnalisée
        return Inertia::render('Error403', [
            'status' => 403,
            'message' => $exception->getMessage() ?: 'Accès non autorisé',
            'diagnostic' => $diagnostic,
        ])->toResponse($request)->setStatusCode(403);
    }

    /**
     * Render une erreur générique avec Inertia
     */
    protected function renderInertiaError($request, $status, $message = null)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message ?: 'Une erreur est survenue',
                'status' => $status,
            ], $status);
        }

        $titles = [
            404 => 'Page non trouvée',
            500 => 'Erreur serveur',
            503 => 'Service indisponible',
        ];

        return Inertia::render('Error', [
            'status' => $status,
            'title' => $titles[$status] ?? 'Erreur',
            'message' => $message ?: 'Une erreur est survenue',
        ])->toResponse($request)->setStatusCode($status);
    }
}
