<?php

namespace App\Http\Middleware;

use App\Services\TurnstileService;
use Closure;
use Illuminate\Http\Request;

class VerifyTurnstile
{
    protected TurnstileService $turnstile;
    
    public function __construct(TurnstileService $turnstile)
    {
        $this->turnstile = $turnstile;
    }
    
    public function handle(Request $request, Closure $next)
    {
        // Si Turnstile désactivé, passer
        if (!$this->turnstile->isEnabled()) {
            return $next($request);
        }
        
        // Vérifier la présence du token
        $token = $request->input('cf-turnstile-response');
        
        if (!$token) {
            return back()->withErrors([
                'turnstile' => 'Veuillez compléter la vérification de sécurité.'
            ]);
        }
        
        // Vérifier le token
        if (!$this->turnstile->verify($token)) {
            return back()->withErrors([
                'turnstile' => 'La vérification de sécurité a échoué. Veuillez réessayer.'
            ]);
        }
        
        return $next($request);
    }
}
