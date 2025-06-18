<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use App\Models\Membre;
use App\Models\Cours;
use App\Models\Presence;
use App\Models\Ceinture;
use App\Models\Seminaire;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('superadmin')) {
            return $this->superadminDashboard();
        } elseif ($user->hasRole('admin')) {
            return $this->adminDashboard($user);
        } elseif ($user->hasRole('instructeur')) {
            return $this->instructeurDashboard($user);
        } else {
            return $this->membreDashboard($user);
        }
    }

    private function superadminDashboard()
    {
        $stats = [
            // Statistiques globales
            'total_ecoles' => Ecole::where('active', true)->count(),
            'total_membres' => Membre::where('active', true)->count(),
            'total_cours' => Cours::where('active', true)->count(),
            'total_instructeurs' => User::role('instructeur')->count(),
            
            // Activité récente
            'nouveaux_membres_mois' => Membre::whereMonth('created_at', now()->month)->count(),
            'presences_semaine' => Presence::whereBetween('date_cours', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'paiements_mois' => Paiement::whereMonth('created_at', now()->month)->where('statut', 'valide')->sum('montant'),
            'seminaires_a_venir' => Seminaire::where('date_debut', '>', now())->count(),
            
            // Top écoles
            'top_ecoles' => Ecole::withCount('membres')->orderBy('membres_count', 'desc')->take(5)->get(),
            
            // Revenus par mois (6 derniers mois)
            'revenus_mensuels' => Paiement::select(
                DB::raw('MONTH(created_at) as mois'),
                DB::raw('YEAR(created_at) as annee'),
                DB::raw('SUM(montant) as total')
            )
            ->where('statut', 'valide')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'desc')
            ->orderBy('mois', 'desc')
            ->get(),
        ];

        return view('admin.dashboard.superadmin', compact('stats'));
    }

    private function adminDashboard($user)
    {
        $ecole = $user->ecole;
        
        $stats = [
            // Statistiques école
            'total_membres' => $ecole->membres()->where('active', true)->count(),
            'total_cours' => $ecole->cours()->where('active', true)->count(),
            'total_instructeurs' => User::role('instructeur')->where('ecole_id', $ecole->id)->count(),
            
            // Activité
            'nouveaux_membres_mois' => $ecole->membres()->whereMonth('created_at', now()->month)->count(),
            'presences_semaine' => Presence::whereHas('membre', function($q) use ($ecole) {
                $q->where('ecole_id', $ecole->id);
            })->whereBetween('date_cours', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            
            'paiements_mois' => Paiement::where('ecole_id', $ecole->id)
                ->whereMonth('created_at', now()->month)
                ->where('statut', 'valide')
                ->sum('montant'),
                
            'seminaires_a_venir' => $ecole->seminaires()->where('date_debut', '>', now())->count(),
            
            // Cours populaires
            'cours_populaires' => $ecole->cours()
                ->withCount('inscriptions')
                ->orderBy('inscriptions_count', 'desc')
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard.admin', compact('stats', 'ecole'));
    }

    private function instructeurDashboard($user)
    {
        $ecole = $user->ecole;
        
        $stats = [
            'mes_cours' => $ecole->cours()->where('active', true)->count(),
            'mes_eleves' => $ecole->membres()->where('active', true)->count(),
            'presences_semaine' => Presence::whereHas('membre', function($q) use ($ecole) {
                $q->where('ecole_id', $ecole->id);
            })->whereBetween('date_cours', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            
            'prochains_seminaires' => $ecole->seminaires()
                ->where('date_debut', '>', now())
                ->orderBy('date_debut')
                ->take(3)
                ->get(),
        ];

        return view('admin.dashboard.instructeur', compact('stats', 'ecole'));
    }

    private function membreDashboard($user)
    {
        // Dashboard simple pour les membres
        return view('admin.dashboard.membre', compact('user'));
    }
}
