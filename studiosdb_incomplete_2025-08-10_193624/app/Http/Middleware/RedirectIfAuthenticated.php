<?php

namespace App\Http\MIddleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        // Si l'utilisateur est déjà connecté, on le redirige vers le dashboard
        if ($request->user()) {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
