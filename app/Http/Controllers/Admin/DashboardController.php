<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use App\Models\User;
use App\Models\Cours;
use App\Models\Presence;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Statistiques selon le rôle
        if ($user->hasRole('superadmin')) {
            $stats = $this->superadminStats();
        } elseif ($user->hasRole('admin')) {
            $stats = $this->adminStats($user);
        } elseif ($user->hasRole('instructeur')) {
            $stats = $this->instructeurStats($user);
        } else {
            $stats = $this->membreStats($user);
        }

        return view('admin.dashboard', compact('stats'));
    }

    private function superadminStats(): array
    {
        try {
            return [
                'total' => User::count(),
                'nouveaux' => User::whereMonth('created_at', now()->month)->count(),
                'instructeurs' => User::whereHas('roles', fn($q) => $q->where('name', 'instructeur'))->count(),
                'presence_rate' => 85.5,
                'total_ecoles' => Ecole::count(),
                'total_cours' => Cours::count(),
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0,
                'nouveaux' => 0,
                'instructeurs' => 0,
                'presence_rate' => 0,
                'total_ecoles' => 22,
                'total_cours' => 0,
            ];
        }
    }

    private function adminStats($user): array
    {
        try {
            $ecoleId = $user->ecole_id;
            return [
                'total' => User::where('ecole_id', $ecoleId)->count(),
                'nouveaux' => User::where('ecole_id', $ecoleId)->whereMonth('created_at', now()->month)->count(),
                'instructeurs' => User::where('ecole_id', $ecoleId)->whereHas('roles', fn($q) => $q->where('name', 'instructeur'))->count(),
                'presence_rate' => 78.2,
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0,
                'nouveaux' => 0,
                'instructeurs' => 0,
                'presence_rate' => 0,
            ];
        }
    }

    private function instructeurStats($user): array
    {
        return [
            'total' => 0,
            'nouveaux' => 0,
            'instructeurs' => 0,
            'presence_rate' => 0,
        ];
    }

    private function membreStats($user): array
    {
        return [
            'total' => 0,
            'nouveaux' => 0,
            'instructeurs' => 0,
            'presence_rate' => 0,
        ];
    }
}
