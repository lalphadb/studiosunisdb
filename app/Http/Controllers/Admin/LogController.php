<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])
            ->latest();

        // Si admin d'école (pas superadmin), filtrer par son école
        if (!auth()->user()->hasRole('superadmin') && auth()->user()->ecole_id) {
            $query->where(function($q) {
                $q->whereHasMorph('subject', ['App\Models\Membre'], function($query) {
                    $query->where('ecole_id', auth()->user()->ecole_id);
                })
                ->orWhereHasMorph('subject', ['App\Models\Cours'], function($query) {
                    $query->where('ecole_id', auth()->user()->ecole_id);
                })
                ->orWhereHasMorph('subject', ['App\Models\Paiement'], function($query) {
                    $query->where('ecole_id', auth()->user()->ecole_id);
                })
                ->orWhere('causer_id', auth()->id());
            });
        }

        // Filtres optionnels
        if ($request->filled('type')) {
            $query->where('log_name', $request->type);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('user')) {
            $query->where('causer_id', $request->user);
        }

        $logs = $query->paginate(20);

        // Types de logs pour le filtre
        $logTypes = Activity::distinct()->pluck('log_name')->filter();

        // Utilisateurs pour le filtre (selon les permissions)
        $users = \App\Models\User::when(!auth()->user()->hasRole('superadmin'), function($q) {
            $q->where('ecole_id', auth()->user()->ecole_id);
        })->get();

        return view('admin.logs.index', compact('logs', 'logTypes', 'users'));
    }
}
