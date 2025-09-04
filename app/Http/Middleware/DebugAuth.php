<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class DebugAuth
{
    /**
     * Handle an incoming request - Debug authentification
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debug seulement en développement
        if (app()->environment('local', 'development')) {
            $user = auth()->user();
            $session = $request->session();
            
            Log::debug('=== DEBUG AUTH ===', [
                'url' => $request->url(),
                'method' => $request->method(),
                'user_id' => $user?->id,
                'user_email' => $user?->email,
                'user_roles' => $user?->getRoleNames()->toArray() ?? [],
                'user_ecole_id' => $user?->ecole_id,
                'session_id' => $session->getId(),
                'session_token' => $session->token(),
                'csrf_token' => csrf_token(),
                'auth_check' => auth()->check(),
                'auth_guard' => auth()->getDefaultDriver(),
            ]);

            // Ajouter headers de debug (visible dans DevTools)
            if ($user) {
                $response = $next($request);
                $response->headers->set('X-Debug-User-ID', $user->id);
                $response->headers->set('X-Debug-User-Roles', implode(',', $user->getRoleNames()->toArray()));
                $response->headers->set('X-Debug-User-Ecole', $user->ecole_id ?? 'null');
                return $response;
            }
        }

        return $next($request);
    }
}
