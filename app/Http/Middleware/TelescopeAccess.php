<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TelescopeAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Telescope uniquement pour SuperAdmin en production
        if (app()->environment('production')) {
            abort_unless(
                auth()->check() && auth()->user()->hasRole('superadmin'),
                403,
                'Accès Telescope réservé aux SuperAdmins'
            );
        }

        return $next($request);
    }
}
