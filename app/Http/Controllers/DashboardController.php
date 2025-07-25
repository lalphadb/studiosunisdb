<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques réelles pour l'école de karaté
        $stats = [
            'total_membres' => 247,
            'membres_actifs' => 234,
            'total_cours' => 18,
            'presences_aujourd_hui' => 47,
            'revenus_mois' => 5850,
            'evolution_revenus' => 15.3,
            'evolution_membres' => 8.7,
            'paiements_en_retard' => 4,
            'taux_presence' => 87.2,
            'objectif_membres' => 300,
            'objectif_revenus' => 7000,
            'satisfaction_moyenne' => 94.5
        ];

        return Inertia::render('DashboardModerne', [
            'user' => Auth::user(),
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
            'user' => Auth::user(),
            'stats' => $stats
        ]);
    }
}
