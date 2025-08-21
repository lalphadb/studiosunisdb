<?php

namespace App\Http\Controllers;

use App\Models\{Membre, Cours, Presence, Paiement, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BladeController extends Controller
{
    /**
     * Page debug avec navigation (copie du pattern qui fonctionne)
     */
    public function debug()
    {
        $extensions = get_loaded_extensions();
        
        return view('blade.debug', [
            'extensions' => $extensions,
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ]);
    }
    
    /**
     * Login Blade (comme debug mais pour login)
     */
    public function login()
    {
        if (Auth::check()) {
            return redirect('/blade/dashboard');
        }
        
        return view('blade.login', [
            'title' => 'StudiosDB v5 - Login Blade',
            'message' => 'Laravel fonctionne - Inertia temporairement bypassé',
        ]);
    }
    
    /**
     * Traitement login
     */
    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect('/blade/dashboard');
        }
        
        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ]);
    }
    
    /**
     * Dashboard Blade fonctionnel
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect('/blade/login');
        }
        
        $user = Auth::user();
        
        // Métriques comme dans le debug - pattern qui fonctionne
        $metriques = [
            'membres_actifs' => Membre::where('statut', 'actif')->count(),
            'total_membres' => Membre::count(),
            'cours_actifs' => Cours::where('actif', true)->count(),
            'total_cours' => Cours::count(),
            'users_total' => User::count(),
            'presences_semaine' => Presence::whereBetween('date_cours', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
        ];
        
        return view('blade.dashboard', [
            'user' => $user,
            'metriques' => $metriques,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);
    }
    
    /**
     * Membres Blade
     */
    public function membres()
    {
        if (!Auth::check()) {
            return redirect('/blade/login');
        }
        
        $membres = Membre::with('user')->paginate(10);
        
        return view('blade.membres', [
            'membres' => $membres,
            'total' => Membre::count(),
            'actifs' => Membre::where('statut', 'actif')->count(),
        ]);
    }
    
    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/blade/login');
    }
}
