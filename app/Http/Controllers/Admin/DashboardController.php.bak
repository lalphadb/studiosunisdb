<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\User;
use App\Models\Cours;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request): View
    {
        try {
            $user = auth()->user();
            
            if ($user->hasRole('superadmin')) {
                return $this->getSuperadminDashboard();
            } else {
                return $this->getAdminEcoleDashboard();
            }
            
        } catch (\Exception $e) {
            \Log::error('Erreur dashboard', ['error' => $e->getMessage()]);
            return $this->getFallbackDashboard();
        }
    }

    private function getSuperadminDashboard(): View
    {
        $stats = [
            'total_ecoles' => Ecole::count(),
            'total_users' => User::count(),
            'total_cours' => Cours::count(),
            'cours_actifs' => Cours::where('statut', 'actif')->count(),
            'nouvelles_ecoles_mois' => Ecole::whereMonth('created_at', now()->month)->count(),
        ];

        $stats_ecoles = Ecole::withCount('users')->orderBy('nom')->get();

        return view('pages.admin.dashboard.superadmin', compact('stats', 'stats_ecoles'));
    }

    private function getAdminEcoleDashboard(): View
    {
        $user = auth()->user();
        $ecoleId = $user->ecole_id;

        $ecole = $ecoleId ? Ecole::find($ecoleId) : null;
        
        $stats = [
            'mes_membres' => $ecoleId ? User::where('ecole_id', $ecoleId)->count() : 0,
            'membres_actifs' => $ecoleId ? User::where('ecole_id', $ecoleId)->where('active', true)->count() : 0,
            'nouveaux_mois' => $ecoleId ? User::where('ecole_id', $ecoleId)->whereMonth('created_at', now()->month)->count() : 0,
            'mes_cours' => $ecoleId ? Cours::where('ecole_id', $ecoleId)->count() : 0,
            'cours_actifs' => $ecoleId ? Cours::where('ecole_id', $ecoleId)->where('statut', 'actif')->count() : 0,
            'revenus_mois' => 0,
        ];

        $derniers_membres = $ecoleId ? User::where('ecole_id', $ecoleId)->latest()->limit(5)->get() : collect([]);
        $prochains_cours = $ecoleId ? Cours::where('ecole_id', $ecoleId)->where('statut', 'actif')->latest()->limit(6)->get() : collect([]);

        return view('pages.admin.dashboard.admin-ecole', compact('stats', 'ecole', 'derniers_membres', 'prochains_cours'));
    }

    private function getFallbackDashboard(): View
    {
        $stats = [
            'mes_membres' => 0,
            'membres_actifs' => 0,
            'nouveaux_mois' => 0,
            'mes_cours' => 0,
            'cours_actifs' => 0,
            'revenus_mois' => 0,
        ];

        return view('pages.admin.dashboard.admin-ecole', [
            'stats' => $stats,
            'ecole' => null,
            'derniers_membres' => collect([]),
            'prochains_cours' => collect([])
        ]);
    }
}
