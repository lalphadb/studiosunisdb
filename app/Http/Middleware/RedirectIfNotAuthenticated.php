<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            // Pour les requêtes Inertia, rediriger vers login
            if ($request->header('X-Inertia')) {
                return redirect()->route('login');
            }
            
            // Pour les requêtes normales, aussi rediriger
            return redirect()->route('login');
        }

        return $next($request);
    }
}
