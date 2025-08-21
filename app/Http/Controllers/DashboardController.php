<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        // Spatie: getRoleNames() évite la relation manuelle
        $role = $user->getRoleNames()->first() ?: 'membre';

        // Widgets fournis au front (optionnel, utile pour Dashboard.vue)
        $widgets = match ($role) {
            'superadmin'   => ['kpi_membres','kpi_finances','alertes','system'],
            'admin_ecole'  => ['kpi_membres','cours_jour','paiements_en_retard','alertes'],
            'instructeur'  => ['cours_jour','taux_presence','examens_a_planifier'],
            default        => ['mon_horaire','mes_paiements','ma_progression'],
        };

        // Router vers la page admin dédiée si rôle admin
        if (in_array($role, ['superadmin','admin_ecole'], true)) {
            return Inertia::render('Dashboard/Admin', [
                'role'    => $role,
                'widgets' => $widgets,
                'user'    => $user->only(['id','name','email']),
            ]);
        }

        return Inertia::render('Dashboard', [
            'role'    => $role,
            'widgets' => $widgets,
            'user'    => $user->only(['id','name','email']),
        ]);
    }
}
