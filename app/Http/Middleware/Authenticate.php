<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Pour les requêtes Inertia, toujours rediriger vers login
        if ($request->header('X-Inertia')) {
            return route('login');
        }

        // Pour les requêtes API, retourner null (401)
        if ($request->expectsJson()) {
            return null;
        }

        // Pour toutes les autres requêtes, rediriger vers login
        return route('login');
    }
}
