<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user()->load('roles');
        $role = $user->roles->pluck('name')->first();

        $widgets = match ($role) {
            'superadmin'    => ['kpi_membres','kpi_finances','alertes','system'],
            'admin'         => ['kpi_membres','cours_jour','paiements_en_retard','alertes'],
            'instructeur'   => ['cours_jour','taux_presence','examens_a_planifier'],
            default         => ['mon_horaire','mes_paiements','ma_progression'],
        };

        return Inertia::render('Dashboard', [
            'role' => $role,
            'widgets' => $widgets,
        ]);
    }
}
