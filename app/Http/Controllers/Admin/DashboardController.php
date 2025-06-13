<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Ecole;
use App\Models\Membre;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Statistiques complètes
        $stats = [
            'total_ecoles' => Ecole::count(),
            'total_membres' => Membre::count(),
            'total_cours' => 0,
            'membres_actifs' => Membre::where('statut', 'actif')->count(),
            'presences_mois' => 0
        ];

        // Top 5 écoles par nombre de membres
        $top_ecoles = Ecole::withCount('membres')
            ->orderBy('membres_count', 'desc')
            ->take(5)
            ->get();

        // Activité récente (exemple simple)
        $activite_recente = [
            [
                'type' => 'membre',
                'titre' => 'Nouveau membre inscrit',
                'description' => 'Jean Dubois - Studios Unis Québec',
                'date' => 'Il y a 2 heures'
            ],
            [
                'type' => 'cours',
                'titre' => 'Cours créé',
                'description' => 'Karaté Débutant - Lundi 19h',
                'date' => 'Il y a 1 jour'
            ]
        ];
        
        return view('admin.dashboard.index', compact('stats', 'user', 'top_ecoles', 'activite_recente'));
    }
}
