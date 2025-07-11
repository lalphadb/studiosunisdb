<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EcoleSessionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Stocker l'école de l'utilisateur en session
            if ($user->ecole_id) {
                session(['ecole_id' => $user->ecole_id]);
                session(['is_super_admin' => $user->hasRole('super-admin')]);
            }
        }
        
        return $next($request);
    }
}
