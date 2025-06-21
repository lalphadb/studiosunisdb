<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TelescopeAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Telescope uniquement pour SuperAdmin
        if (!auth()->check() || !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Accès Telescope réservé aux Super Administrateurs');
        }

        return $next($request);
    }
}
