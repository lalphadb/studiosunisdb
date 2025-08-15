<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ScopeBySchool
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && !$user->hasRole('superadmin') && !$user->ecole_id) {
            abort(403, "Aucune école associée.");
        }
        return $next($request);
    }
}
