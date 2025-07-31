<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware Dashboard Access - StudiosDB v5 Pro
 * Laravel 11.x Ultra-Professionnel
 */
class CheckDashboardAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Log accès dashboard pour monitoring
        Log::info('Dashboard access', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => substr($request->userAgent() ?? '', 0, 100),
            'timestamp' => now()->toISOString(),
        ]);

        return $next($request);
    }
}
