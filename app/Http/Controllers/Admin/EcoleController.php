<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EcoleController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:view-ecoles', only: ['index', 'show']),
            new Middleware('can:create-ecole', only: ['create', 'store']),
            new Middleware('can:edit-ecole', only: ['edit', 'update']),
            new Middleware('can:delete-ecole', only: ['destroy']),
        ];
    }

    public function index()
    {
        $ecoles = Ecole::withCount('membres')
            ->orderBy('nom')
            ->paginate(10);

        return view('admin.ecoles.index', compact('ecoles'));
    }

    public function create()
    {
        return view('admin.ecoles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'province' => 'required|string|max:50',
            'code_postal' => 'required|string|max:10',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'directeur' => 'required|string|max:255',
            'capacite_max' => 'required|integer|min:10|max:500',
            'statut' => 'required|in:actif,inactif',
        ]);

        $ecole = Ecole::create($validated);

        return redirect()->route('admin.ecoles.show', $ecole)
            ->with('success', 'École créée avec succès !');
    }

    public function show(Ecole $ecole)
    {
        $ecole->load('membres');

        $stats = [
            'membres_actifs' => $ecole->membres()->where('statut', 'actif')->count(),
            'cours_actifs' => 0,
            'revenus_mois' => 0,
            'taux_presence' => 85,
        ];

        return view('admin.ecoles.show', compact('ecole', 'stats'));
    }

    public function edit(Ecole $ecole)
    {
        return view('admin.ecoles.edit', compact('ecole'));
    }

    public function update(Request $request, Ecole $ecole)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'province' => 'required|string|max:50',
            'code_postal' => 'required|string|max:10',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'directeur' => 'required|string|max:255',
            'capacite_max' => 'required|integer|min:10|max:500',
            'statut' => 'required|in:actif,inactif',
        ]);

        $ecole->update($validated);

        return redirect()->route('admin.ecoles.show', $ecole)
            ->with('success', 'École mise à jour avec succès !');
    }

    public function destroy(Ecole $ecole)
    {
        if ($ecole->membres()->count() > 0) {
            return redirect()->route('admin.ecoles.index')
                ->with('error', 'Impossible de supprimer une école qui a des membres.');
        }

        $ecole->delete();

        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École supprimée avec succès !');
    }

    public function export()
    {
        $ecoles = Ecole::withCount('membres')->get();

        $filename = 'ecoles_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($ecoles) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nom', 'Ville', 'Directeur', 'Téléphone', 'Email', 'Membres', 'Statut']);

            foreach ($ecoles as $ecole) {
                fputcsv($file, [
                    $ecole->nom,
                    $ecole->ville,
                    $ecole->directeur,
                    $ecole->telephone,
                    $ecole->email,
                    $ecole->membres_count,
                    $ecole->statut,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
