<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Ecole, Cours, Paiement, MembreCeinture};
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
        $ecoleId = $user->ecole_id;
        
        // Statistiques selon le rôle et école
        $stats = [
            'total_users' => $ecoleId 
                ? User::where('ecole_id', $ecoleId)->where('active', true)->count()
                : User::where('active', true)->count(),
                
            'total_ecoles' => $user->hasRole('superadmin') 
                ? Ecole::where('active', true)->count() 
                : ($ecoleId ? 1 : 0),
                
            'total_cours' => $ecoleId
                ? Cours::where('ecole_id', $ecoleId)->where('active', true)->count()
                : Cours::where('active', true)->count(),
                
            'paiements_mois' => $ecoleId
                ? Paiement::where('ecole_id', $ecoleId)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->where('statut', 'valide')
                    ->sum('montant')
                : Paiement::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->where('statut', 'valide')
                    ->sum('montant'),
                    
            'ceintures_mois' => $ecoleId
                ? MembreCeinture::whereHas('user', fn($q) => $q->where('ecole_id', $ecoleId))
                    ->whereMonth('created_at', now()->month)
                    ->count()
                : MembreCeinture::whereMonth('created_at', now()->month)->count(),
                    
            'nouveaux_membres_mois' => $ecoleId
                ? User::where('ecole_id', $ecoleId)
                    ->whereMonth('created_at', now()->month)
                    ->count()
                : User::whereMonth('created_at', now()->month)->count(),
        ];

        // Informations utilisateur connecté
        $userInfo = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->roles->first()?->name ?? 'membre',
            'ecole' => $user->ecole?->nom ?? 'Global',
        ];

        // Log de l'accès dashboard
        activity()
            ->causedBy($user)
            ->log('Accès dashboard');

        return view('admin.dashboard.index', compact('stats', 'userInfo'));
    }
}
