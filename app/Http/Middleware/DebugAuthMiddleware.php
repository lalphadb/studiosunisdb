<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DebugAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Debug Auth Middleware', [
            'authenticated' => Auth::check(),
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'session_id' => session()->getId(),
            'url' => $request->url(),
            'method' => $request->method()
        ]);

        if (Auth::check()) {
            Log::info('User Details', [
                'user' => Auth::user()->toArray(),
                'roles' => Auth::user()->getRoleNames()->toArray(),
                'permissions' => Auth::user()->getAllPermissions()->pluck('name')->toArray()
            ]);
        }

        return $next($request);
    }
}
