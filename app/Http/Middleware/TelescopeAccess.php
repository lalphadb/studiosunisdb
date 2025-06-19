<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TelescopeAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Bloquer l'accès à Telescope en production sauf pour SuperAdmin
        if (app()->environment('production')) {
            if (!auth()->check() || !auth()->user()->hasRole('superadmin')) {
                abort(404);
            }
        }

        return $next($request);
    }
}
