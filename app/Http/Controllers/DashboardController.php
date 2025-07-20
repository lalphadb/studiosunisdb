<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_membres' => 250,
            'membres_actifs' => 235,
            'total_cours' => 18,
            'presences_aujourd_hui' => 43,
            'revenus_mois' => 5750,
            'evolution_revenus' => 12.5,
            'paiements_en_retard' => 3
        ];

        return Inertia::render('Dashboard', [
            'user' => auth()->user(),
            'stats' => $stats
        ]);
    }

    public function nouveau()
    {
        $stats = [
            'total_membres' => 250,
            'membres_actifs' => 235,
            'total_cours' => 18,
            'presences_aujourd_hui' => 43,
            'revenus_mois' => 5750,
            'evolution_revenus' => 12.5,
            'paiements_en_retard' => 3
        ];

        return Inertia::render('Dashboard/Index', [
            'user' => auth()->user(),
            'stats' => $stats
        ]);
    }
}
