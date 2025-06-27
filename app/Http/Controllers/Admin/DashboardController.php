<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use App\Models\Cours;
use App\Models\UserCeinture;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // REDIRECTION SELON LE RÔLE
        if ($user->hasRole('superadmin')) {
            return $this->superAdminDashboard();
        }
        
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
     * Dashboard SuperAdmin - Vue globale
     */
    private function superAdminDashboard()
    {
        $stats = [
            'total_ecoles' => Ecole::count(),
            'total_users' => User::count(),
            'total_cours' => Cours::count(),
            'cours_actifs' => Cours::where('active', true)->count(),
        ];
        
        return view('admin.dashboard.index', compact('stats'));
    }
    
    /**
     * Dashboard Admin École - Vue limitée à son école
     */
    private function adminEcoleDashboard()
    {
        $ecoleId = auth()->user()->ecole_id;
        
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
            'mes_admins' => User::where('ecole_id', $ecoleId)
                ->whereHas('roles', fn($q) => $q->where('name', 'admin_ecole'))
                ->count(),
            'revenus_mois' => User::where('ecole_id', $ecoleId)->count() * 80, // Estimation
            'mes_attributions' => UserCeinture::whereHas('user', fn($q) => $q->where('ecole_id', $ecoleId))->count(),
            'attributions_mois' => UserCeinture::whereHas('user', fn($q) => $q->where('ecole_id', $ecoleId))
                ->where('date_obtention', '>=', now()->subMonth())
                ->count(),
            'examens_attente' => UserCeinture::whereHas('user', fn($q) => $q->where('ecole_id', $ecoleId))
                ->where('valide', false)
                ->count(),
        ];
        
        // Derniers membres
        $derniers_membres = User::where('ecole_id', $ecoleId)
            ->whereHas('roles', fn($q) => $q->where('name', 'membre'))
            ->latest()
            ->take(5)
            ->get();
            
        // Prochains cours
        $prochains_cours = Cours::where('ecole_id', $ecoleId)
            ->where('active', true)
            ->take(5)
            ->get();
        
        return view('admin.dashboard.admin-ecole', compact('stats', 'derniers_membres', 'prochains_cours'));
    }
    
    /**
     * Dashboard Instructeur - Vue limitée
     */
    private function instructeurDashboard()
    {
        // TODO: Dashboard instructeur spécifique
        return $this->adminEcoleDashboard(); // Pour l'instant, même vue
    }
}
