<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Vérifier que l'utilisateur a un rôle admin
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Vérifier si l'utilisateur a un rôle admin
        if (!$user->hasAnyRole(['superadmin', 'admin_ecole'])) {
            abort(403, 'Accès refusé. Vous devez être administrateur pour accéder à cette section.');
        }

        // Vérifier si le compte est actif
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['error' => 'Votre compte a été désactivé. Contactez un administrateur.']);
        }

        return $next($request);
    }
}
