<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Cours;
use App\Models\Presence;
use App\Models\Paiement;
use App\Models\Ceinture;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_membres'     => Membre::count(),
            'total_cours'       => Cours::count(),
            'total_presences'   => Presence::whereMonth('created_at', now()->month)->count(),
            'total_paiements'   => Paiement::where('statut', 'payé')->sum('montant'),
            'progression_ceintures' => Ceinture::withCount('membres')->orderBy('ordre')->get()->map(fn ($c) => [
                'ceinture' => $c->nom,
                'count'    => $c->membres_count
            ]),
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats
        ]);
    }
}
