<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use App\Models\Membre;
use App\Models\Cours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques principales
        $stats = [
            'total_ecoles' => Ecole::count(),
            'total_membres' => Membre::count(),
            'total_cours' => Cours::count(),
            'presences_mois' => 0, // Temporaire jusqu'à création table presences
        ];

        // Top 5 écoles par nombre de membres
        $top_ecoles = Ecole::withCount('membres')
                          ->orderBy('membres_count', 'desc')
                          ->take(5)
                          ->get();

        // Activité récente (simulation)
        $activite_recente = [
            [
                'type' => 'membre',
                'titre' => 'Nouveau membre inscrit',
                'description' => 'Marie Dubois - École Montréal Centre',
                'date' => '2h'
            ],
            [
                'type' => 'cours',
                'titre' => 'Cours Karaté Enfants',
                'description' => 'Session de 18h30 - 15 présents',
                'date' => '4h'
            ],
            [
                'type' => 'membre',
                'titre' => 'Examen ceinture jaune',
                'description' => 'Pierre Martin - Réussi',
                'date' => '1j'
            ],
            [
                'type' => 'cours',
                'titre' => 'Séminaire kumite',
                'description' => 'Ouverture inscriptions',
                'date' => '2j'
            ]
        ];

        return view('admin.dashboard', compact('stats', 'top_ecoles', 'activite_recente'));
    }
}
