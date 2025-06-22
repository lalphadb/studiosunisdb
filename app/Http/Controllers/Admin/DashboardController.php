<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use App\Models\Ceinture;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Informations utilisateur
        $userInfo = [
            'name' => $user->name,
            'role' => $user->roles->first()?->name ?? 'membre',
            'ecole' => $user->ecole ? $user->ecole->nom : 'Global'
        ];
        
        // Statistiques selon le rôle
        $stats = $this->getStats($user);
        
        return view('admin.dashboard', compact('userInfo', 'stats'));  // ✅ CORRECTION ICI
    }
    
    private function getStats($user)
    {
        if ($user->hasRole('superadmin')) {
            return [
                'total_users' => User::count(),
                'total_ecoles' => Ecole::count(),
                'total_cours' => 0,
                'paiements_mois' => 0,
            ];
        } else {
            // Stats pour admin d'école
            return [
                'total_users' => User::where('ecole_id', $user->ecole_id)->count(),
                'total_ecoles' => 1,
                'total_cours' => 0,
                'paiements_mois' => 0,
            ];
        }
    }
}
