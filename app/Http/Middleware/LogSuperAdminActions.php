<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Monitoring\SuperAdminMonitor;

class LogSuperAdminActions
{
    public function __construct(
        private SuperAdminMonitor $monitor
    ) {}
    
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user() && auth()->user()->hasRole('superadmin')) {
            $this->monitor->logAction(
                $request->route()->getName() ?? 'unknown',
                auth()->user(),
                [
                    'method' => $request->method(),
                    'path' => $request->path(),
                    'ip' => $request->ip()
                ]
            );
        }
        
        return $next($request);
    }
}
