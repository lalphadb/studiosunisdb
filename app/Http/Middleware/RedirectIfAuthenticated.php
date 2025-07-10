<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                
                // Redirection selon le rôle
                if ($user->hasAnyRole(['superadmin', 'admin_ecole'])) {
                    return redirect('/admin/dashboard');
                } else {
                    return redirect('/dashboard');
                }
            }
        }

        return $next($request);
    }
}
