<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Ecole, Cours, Paiement};
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
        // Multi-tenant: stats selon rôle utilisateur
        $user = auth()->user();
        $ecoleId = $user->ecole_id;
        
        $stats = [
            'total_users' => $ecoleId 
                ? User::where('ecole_id', $ecoleId)->count()
                : User::count(),
                
            'total_ecoles' => $user->hasRole('superadmin') 
                ? Ecole::count() 
                : ($ecoleId ? 1 : 0),
                
            'total_cours' => $ecoleId
                ? Cours::where('ecole_id', $ecoleId)->count()
                : Cours::count(),
                
            'paiements_mois' => $ecoleId
                ? Paiement::where('ecole_id', $ecoleId)->whereMonth('created_at', now()->month)->sum('montant')
                : Paiement::whereMonth('created_at', now()->month)->sum('montant'),
        ];

        // Informations utilisateur connecté
        $userInfo = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->roles->first()?->name ?? 'aucun',
            'ecole' => $user->ecole?->nom ?? 'Global',
        ];

        return view('admin.dashboard.index', compact('stats', 'userInfo'));
    }
}
