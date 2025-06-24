<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use App\Models\Cours;
use App\Models\Seminaire;
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentUser = auth()->user();
        
        // Statistiques selon le rôle
        if ($currentUser->hasRole('superadmin')) {
            $stats = $this->getSuperAdminStats();
        } elseif ($currentUser->hasRole('admin_ecole')) {
            $stats = $this->getEcoleStats($currentUser->ecole_id);
        } else {
            $stats = $this->getBasicStats($currentUser->ecole_id);
        }

        return view('admin.dashboard', compact('stats'));
    }

    private function getSuperAdminStats(): array
    {
        return [
            'total_ecoles' => Ecole::count(),
            'ecoles_actives' => Ecole::where('active', true)->count(),
            'total_users' => User::count(),
            'users_actifs' => User::where('active', true)->count(),
            'total_cours' => Cours::count(),
            'cours_actifs' => Cours::where('active', true)->count(),
            'total_seminaires' => Seminaire::count(),
            'revenus_totaux' => Paiement::where('statut', 'paye')->sum('montant'),
            'roles_stats' => $this->getRolesStats(),
            'top_ecoles' => $this->getTopEcoles(),
        ];
    }

    private function getEcoleStats($ecoleId): array
    {
        return [
            'total_users' => User::where('ecole_id', $ecoleId)->count(),
            'users_actifs' => User::where('ecole_id', $ecoleId)->where('active', true)->count(),
            'total_cours' => Cours::where('ecole_id', $ecoleId)->count(),
            'cours_actifs' => Cours::where('ecole_id', $ecoleId)->where('active', true)->count(),
            'revenus_mois' => Paiement::where('ecole_id', $ecoleId)
                                    ->where('statut', 'paye')
                                    ->whereMonth('created_at', now()->month)
                                    ->sum('montant'),
        ];
    }

    private function getBasicStats($ecoleId): array
    {
        return [
            'mes_cours' => Cours::where('ecole_id', $ecoleId)->where('active', true)->count(),
            'mes_presences' => 0, // TODO: Calculer selon l'utilisateur
        ];
    }

    private function getRolesStats(): array
    {
        return DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('roles.name', DB::raw('count(*) as count'))
            ->where('model_type', User::class)
            ->groupBy('roles.name')
            ->pluck('count', 'name')
            ->toArray();
    }

    private function getTopEcoles(): array
    {
        return Ecole::withCount(['users' => function($query) {
            $query->where('active', true);
        }])
        ->orderBy('users_count', 'desc')
        ->limit(5)
        ->get()
        ->toArray();
    }
}
