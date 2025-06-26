<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use App\Models\Cours;
use App\Models\Seminaire;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Statistiques globales pour SuperAdmin
        if ($user->hasRole('superadmin')) {
            $stats = [
                'total_users' => User::count(),
                'total_ecoles' => Ecole::count(),
                'total_cours' => Cours::count(),
                'total_seminaires' => Seminaire::count(), // PLUS DE WHERE ecole_id
                'revenus_mois' => Paiement::where('statut', 'valide')
                    ->whereMonth('created_at', now()->month)
                    ->sum('montant_net'),
                'users_actifs' => User::where('active', true)->count(),
                'ecoles_actives' => Ecole::where('active', true)->count(),
                'seminaires_a_venir' => Seminaire::where('date_debut', '>', now())->count(),
            ];
            
            // Graphiques pour SuperAdmin
            $charts = [
                'users_par_mois' => $this->getUsersParMois(),
                'revenus_par_mois' => $this->getRevenusParMois(),
                'ecoles_par_province' => $this->getEcolesParProvince(),
            ];
            
        // Statistiques pour Admin École
        } elseif ($user->hasRole('admin_ecole')) {
            $ecole_id = $user->ecole_id;
            
            $stats = [
                'total_users' => User::where('ecole_id', $ecole_id)->count(),
                'total_cours' => Cours::where('ecole_id', $ecole_id)->count(),
                'total_seminaires' => Seminaire::count(), // SÉMINAIRES INTER-ÉCOLES - PAS DE FILTRE
                'revenus_mois' => Paiement::where('ecole_id', $ecole_id)
                    ->where('statut', 'valide')
                    ->whereMonth('created_at', now()->month)
                    ->sum('montant_net'),
                'users_actifs' => User::where('ecole_id', $ecole_id)->where('active', true)->count(),
                'cours_actifs' => Cours::where('ecole_id', $ecole_id)->where('active', true)->count(),
                'seminaires_a_venir' => Seminaire::where('date_debut', '>', now())->count(),
            ];
            
            // Graphiques pour Admin École
            $charts = [
                'users_par_mois' => $this->getUsersParMoisEcole($ecole_id),
                'revenus_par_mois' => $this->getRevenusParMoisEcole($ecole_id),
            ];
            
        // Statistiques pour autres rôles
        } else {
            $stats = [
                'total_users' => User::count(),
                'total_ecoles' => Ecole::count(),
                'total_cours' => Cours::count(),
                'total_seminaires' => Seminaire::count(),
            ];
            
            $charts = [];
        }
        
        // Activités récentes
        $activites_recentes = $this->getActivitesRecentes($user);
        
        return view('admin.dashboard', compact('stats', 'charts', 'activites_recentes'));
    }

    /**
     * Obtenir les utilisateurs par mois (Global)
     */
    private function getUsersParMois()
    {
        return User::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->pluck('total', 'mois')
            ->toArray();
    }

    /**
     * Obtenir les revenus par mois (Global)
     */
    private function getRevenusParMois()
    {
        return Paiement::selectRaw('MONTH(created_at) as mois, SUM(montant_net) as total')
            ->where('statut', 'valide')
            ->whereYear('created_at', now()->year)
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->pluck('total', 'mois')
            ->toArray();
    }

    /**
     * Obtenir les écoles par province
     */
    private function getEcolesParProvince()
    {
        return Ecole::selectRaw('province, COUNT(*) as total')
            ->groupBy('province')
            ->get()
            ->pluck('total', 'province')
            ->toArray();
    }

    /**
     * Obtenir les utilisateurs par mois pour une école
     */
    private function getUsersParMoisEcole($ecole_id)
    {
        return User::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->where('ecole_id', $ecole_id)
            ->whereYear('created_at', now()->year)
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->pluck('total', 'mois')
            ->toArray();
    }

    /**
     * Obtenir les revenus par mois pour une école
     */
    private function getRevenusParMoisEcole($ecole_id)
    {
        return Paiement::selectRaw('MONTH(created_at) as mois, SUM(montant_net) as total')
            ->where('ecole_id', $ecole_id)
            ->where('statut', 'valide')
            ->whereYear('created_at', now()->year)
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->pluck('total', 'mois')
            ->toArray();
    }

    /**
     * Obtenir les activités récentes
     */
    private function getActivitesRecentes($user)
    {
        $activites = collect();
        
        // Nouvelles inscriptions
        $inscriptions = User::with('ecole')
            ->when($user->hasRole('admin_ecole'), function($q) use ($user) {
                $q->where('ecole_id', $user->ecole_id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($user) {
                return [
                    'type' => 'inscription',
                    'titre' => "Nouvelle inscription: {$user->name}",
                    'date' => $user->created_at,
                    'url' => route('admin.users.show', $user),
                ];
            });
        
        // Nouveaux paiements
        $paiements = Paiement::with(['user', 'ecole'])
            ->when($user->hasRole('admin_ecole'), function($q) use ($user) {
                $q->where('ecole_id', $user->ecole_id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($paiement) {
                return [
                    'type' => 'paiement',
                    'titre' => "Nouveau paiement: {$paiement->user->name} - {$paiement->montant}$",
                    'date' => $paiement->created_at,
                    'url' => route('admin.paiements.show', $paiement),
                ];
            });
        
        // Nouveaux séminaires (inter-écoles, donc visible pour tous)
        $seminaires = Seminaire::orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($seminaire) {
                return [
                    'type' => 'seminaire',
                    'titre' => "Nouveau séminaire: {$seminaire->titre}",
                    'date' => $seminaire->created_at,
                    'url' => route('admin.seminaires.show', $seminaire),
                ];
            });
        
        return $activites->merge($inscriptions)
            ->merge($paiements)
            ->merge($seminaires)
            ->sortByDesc('date')
            ->take(10);
    }
}
