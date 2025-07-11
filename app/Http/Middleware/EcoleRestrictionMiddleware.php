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
