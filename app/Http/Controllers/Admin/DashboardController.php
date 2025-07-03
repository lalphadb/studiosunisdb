<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use App\Models\Cours;
use App\Models\UserCeinture;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:view-dashboard', only: ['index']),
        ];
    }

    public function index()
    {
        $user = auth()->user();
        
        // SUPERADMIN -> Interface système distincte
        if ($user->hasRole('superadmin')) {
            return $this->superAdminDashboard();
        }
        
        // ADMIN ÉCOLE -> Interface école complète
        if ($user->hasRole('admin_ecole')) {
            return $this->adminEcoleDashboard();
        }
        
        if ($user->hasRole('instructeur')) {
            return $this->instructeurDashboard();
        }
        
        // Par défaut, dashboard membre
        return redirect()->route('dashboard');
    }
    
    /**
     * Dashboard SuperAdmin - Interface système DISTINCTE
     */
    private function superAdminDashboard()
    {
        $stats = [
            'total_ecoles' => Ecole::count(),
            'ecoles_actives' => Ecole::where('active', true)->count(),
            'total_users' => User::count(),
            'nouvelles_ecoles_mois' => Ecole::where('created_at', '>=', now()->startOfMonth())->count(),
            'total_cours' => Cours::count(),
            'cours_actifs' => Cours::where('active', true)->count(),
        ];
        
        $ecoles_recentes = Ecole::latest()->take(5)->get();
        $stats_ecoles = Ecole::withCount('users')->get();
        
        return view('admin.dashboard.superadmin', compact('stats', 'ecoles_recentes', 'stats_ecoles'));
    }
    
    /**
     * Dashboard Admin École - Interface école complète
     */
    private function adminEcoleDashboard()
    {
        $ecoleId = auth()->user()->ecole_id;
        $ecole = auth()->user()->ecole;
        
        if (!$ecole) {
            abort(403, 'Aucune école assignée à ce compte administrateur');
        }
        
        $stats = [
            'mes_membres' => User::where('ecole_id', $ecoleId)
                ->whereHas('roles', fn($q) => $q->where('name', 'membre'))
                ->count(),
            'membres_actifs' => User::where('ecole_id', $ecoleId)
                ->where('active', true)
                ->whereHas('roles', fn($q) => $q->where('name', 'membre'))
                ->count(),
            'mes_cours' => Cours::where('ecole_id', $ecoleId)->count(),
            'cours_actifs' => Cours::where('ecole_id', $ecoleId)->where('active', true)->count(),
            'mes_instructeurs' => User::where('ecole_id', $ecoleId)
                ->whereHas('roles', fn($q) => $q->where('name', 'instructeur'))
                ->count(),
            'nouveaux_mois' => User::where('ecole_id', $ecoleId)
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
            'revenus_mois' => User::where('ecole_id', $ecoleId)->count() * 80,
        ];
        
        $derniers_membres = User::where('ecole_id', $ecoleId)
            ->whereHas('roles', fn($q) => $q->where('name', 'membre'))
            ->latest()
            ->take(5)
            ->get();
            
        $prochains_cours = Cours::where('ecole_id', $ecoleId)
            ->where('active', true)
            ->take(5)
            ->get();
        
        return view('admin.dashboard.admin-ecole', compact('stats', 'derniers_membres', 'prochains_cours', 'ecole'));
    }
    
    /**
     * Dashboard Instructeur - Vue limitée
     */
    private function instructeurDashboard()
    {
        return $this->adminEcoleDashboard();
    }
}
