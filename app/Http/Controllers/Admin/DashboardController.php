<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use App\Models\Ceinture;
use App\Models\Cours;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['auth', 'verified'];
    }

    public function index()
    {
        $user = auth()->user();
        
        // Informations utilisateur pour la vue
        $userInfo = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->getRoleNames()->first() ?? 'membre',
            'ecole' => $user->ecole->nom ?? 'Aucune école assignée',
        ];
        
        // Statistiques selon le rôle - CORRECTION DES NOMS DE RÔLES
        if ($user->hasRole('super-admin')) { // AVEC TIRET
            // SuperAdmin voit tout
            $stats = [
                'total_users' => User::count(),
                'total_ecoles' => Ecole::count(),
                'total_ceintures' => Ceinture::count(),
                'total_cours' => Cours::count(),
                'cours_actifs' => Cours::where('active', true)->count(),
                'users_actifs' => User::where('active', true)->count(),
                'paiements_mois' => 0, // TODO: Calculer quand module paiements sera fait
            ];
        } else {
            // Admin d'école voit son école seulement
            $stats = [
                'total_users' => User::where('ecole_id', $user->ecole_id)->count(),
                'total_ecoles' => 1, // Son école
                'total_ceintures' => Ceinture::count(),
                'total_cours' => Cours::where('ecole_id', $user->ecole_id)->count(),
                'cours_actifs' => Cours::where('ecole_id', $user->ecole_id)->where('active', true)->count(),
                'users_actifs' => User::where('ecole_id', $user->ecole_id)->where('active', true)->count(),
                'paiements_mois' => 0, // TODO: Calculer quand module paiements sera fait
            ];
        }

        return view('admin.dashboard', compact('stats', 'userInfo'));
    }

    /**
     * Dashboard spécifique au SuperAdmin avec vue globale
     */
    public function superadminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_ecoles' => Ecole::count(),
            'total_cours' => Cours::count(),
        ];
        
        return view('admin.dashboard.superadmin', compact('stats'));
    }
    
    /**
     * Dashboard spécifique à l'Admin d'école avec vue limitée
     */
    public function adminDashboard()
    {
        $admin = auth()->user();
        $ecole = $admin->ecole;
        
        if (!$ecole) {
            abort(403, 'Aucune école associée à cet administrateur');
        }
        
        $stats = [
            'my_school_users' => User::where('ecole_id', $ecole->id)->count(),
            'my_school_cours' => Cours::where('ecole_id', $ecole->id)->count(),
            'pending_paiements' => 0, // TODO: Calculer les paiements en attente
        ];
        
        return view('admin.dashboard.admin', compact('stats', 'ecole'));
    }
}
