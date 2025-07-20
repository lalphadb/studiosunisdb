<?php

namespace App\\Http\\Controllers;

use Illuminate\\Http\\Request;
use Inertia\\Inertia;
use Inertia\\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Dashboard', [
            'user' => $request->user(),
            'stats' => [
                'total_membres' => 0,
                'nouveaux_mois' => 0,
                'cours_actifs' => 0,
                'presences_semaine' => 0
            ]
        ]);
    }

    public function metriquesTempsReel(Request $request)
    {
        return response()->json([
            'membres_actifs' => 0,
            'cours_aujourdhui' => 0,
            'revenus_mois' => 0
        ]);
    }
}
