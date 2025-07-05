<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // REDIRECTION INTELLIGENTE SELON LE RÔLE
        $user = Auth::user();
        
        // SuperAdmin ou Admin École → Dashboard Admin
        if ($user->hasAnyRole(['superadmin', 'admin_ecole', 'instructeur'])) {
            return redirect()->intended(route('admin.dashboard'));
        }
        
        // Membre → Dashboard membre
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */  
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
