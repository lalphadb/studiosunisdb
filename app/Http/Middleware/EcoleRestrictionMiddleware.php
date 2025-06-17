<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EcoleRestrictionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // SuperAdmin peut tout voir
        if ($user->hasRole('superadmin')) {
            return $next($request);
        }

        // Admin école : restreindre à son école
        if ($user->hasRole('admin')) {
            // Vérifier que l'utilisateur a une école assignée
            if (! $user->ecole_id) {
                abort(403, 'Aucune école assignée à votre compte.');
            }

            // Pour les routes d'écoles - bloquer l'accès sauf à sa propre école
            if ($request->route('ecole')) {
                $ecole = $request->route('ecole');
                if ($ecole->id != $user->ecole_id) {
                    abort(403, 'Vous ne pouvez accéder qu\'aux informations de votre école.');
                }
            }

            // Pour les routes de membres - vérifier que le membre appartient à son école
            if ($request->route('membre')) {
                $membre = $request->route('membre');
                if ($membre->ecole_id != $user->ecole_id) {
                    abort(403, 'Vous ne pouvez accéder qu\'aux membres de votre école.');
                }
            }

            // Ajouter automatiquement le filtre école dans les requêtes
            $request->merge(['user_ecole_id' => $user->ecole_id]);
        }

        return $next($request);
    }
}
