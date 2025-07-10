<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Enforce2FA
{
    /**
     * Forcer l'activation du 2FA pour certains rôles
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Rôles nécessitant 2FA obligatoire
        $requires2FA = [
            'superadmin' => config('studiosdb.security.enforce_2fa_superadmin', true),
            'admin_ecole' => config('studiosdb.security.enforce_2fa_admin', true),
        ];
        
        foreach ($requires2FA as $role => $enforced) {
            if ($enforced && $user->hasRole($role)) {
                // Vérifier si 2FA est activé
                if (!$user->two_factor_secret) {
                    session()->flash('warning', 'L\'authentification à deux facteurs est obligatoire pour votre rôle.');
                    return redirect()->route('profile.show')
                        ->with('error', 'Veuillez activer l\'authentification à deux facteurs.');
                }
                
                // Vérifier si 2FA est confirmé pour cette session
                if (!$request->session()->get('auth.two_factor_confirmed_at')) {
                    return redirect()->route('two-factor.login');
                }
            }
        }
        
        return $next($request);
    }
}
