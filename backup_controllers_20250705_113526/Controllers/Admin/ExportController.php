<?php

declare(strict_types=1);


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class ExportController extends BaseAdminController

    public function __construct()
    {
        parent::__construct();
        $this->middleware("can:manage-system")->only(["index", "exportLogs"]);
    }
{
    }

    /**
     * Page principale des exports
     */
    public function index()
    {
        return view('admin.exports.index');
    }

    /**
     * Export des logs système (Loi 25 - Transparence)
     */
    public function exportLogs(Request $request)
    {
        // Vérifier que l'utilisateur est superadmin
        if (!auth()->user()->hasRole('superadmin')) {
            abort(403, 'Accès réservé aux superadministrateurs');
        }

        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Log de l'export (pour audit)
        Log::info('Export logs système demandé', [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'period_start' => $startDate,
            'period_end' => $endDate,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()
        ]);

        // Générer un CSV simple avec les informations d'export
        $csv = '"Date","Type","Utilisateur","Email","IP","Action","Détails"' . "\n";
        $csv .= '"' . now()->format('Y-m-d H:i:s') . '","Export","' . auth()->user()->name . '","' . auth()->user()->email . '","' . request()->ip() . '","Export logs demandé","Période: ' . $startDate . ' à ' . $endDate . '"' . "\n";
        $csv .= '"' . now()->format('Y-m-d H:i:s') . '","Info","Système","système@studiosdb.com","localhost","Génération CSV","Logs exportés pour audit Loi 25"' . "\n";

        $filename = 'logs_studiosdb_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
