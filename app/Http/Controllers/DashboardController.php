<?php
namespace App\Http\Controllers;
use App\Models\{Membre, Cours, Presence, Paiement, User};
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) return redirect()->route('login');
        
        $stats = [
            'total_membres' => Membre::count(),
            'membres_actifs' => Membre::where('statut', 'actif')->count(),
            'total_cours' => Cours::count(),
            'cours_actifs' => Cours::where('actif', true)->count(),
            'presences_aujourd_hui' => 0,
            'revenus_mois' => 0,
            'evolution_revenus' => 15,
            'evolution_membres' => 8,
            'paiements_en_retard' => 0,
            'taux_presence' => 85,
            'objectif_membres' => 300,
            'objectif_revenus' => 7000,
            'satisfaction_moyenne' => 92
        ];
        
        return Inertia::render('DashboardUltraPro', [
            'stats' => $stats,
            'user' => $user
        ]);
    }
    
    public function metriquesTempsReel(Request $request)
    {
        return response()->json(['success' => true, 'data' => []]);
    }
}
