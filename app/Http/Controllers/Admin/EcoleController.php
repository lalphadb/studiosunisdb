<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EcoleController extends Controller implements HasMiddleware
{
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
            'nom' => 'required|string|max:191',
            'code' => 'required|string|max:10|unique:ecoles,code',
            'adresse' => 'required|string|max:191',
            'ville' => 'required|string|max:191',
            'province' => 'string|max:191',
            'code_postal' => 'required|string|max:191',
            'telephone' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'site_web' => 'nullable|string|max:191',
            'description' => 'nullable|string',
            'active' => 'boolean',  // CORRIGÉ: active au lieu de statut
        ]);

        // Valeur par défaut
        $validated['active'] = $validated['active'] ?? true;
        $validated['province'] = $validated['province'] ?? 'QC';

        $ecole = Ecole::create($validated);

        return redirect()->route('admin.ecoles.show', $ecole)
            ->with('success', 'École créée avec succès !');
    }

    public function show(Ecole $ecole)
    {
        $ecole->load('membres');

        $stats = [
            'membres_actifs' => $ecole->membres()->where('active', true)->count(),  // CORRIGÉ
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
            'nom' => 'required|string|max:191',
            'code' => 'required|string|max:10|unique:ecoles,code,' . $ecole->id,
            'adresse' => 'required|string|max:191',
            'ville' => 'required|string|max:191',
            'province' => 'string|max:191',
            'code_postal' => 'required|string|max:191',
            'telephone' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'site_web' => 'nullable|string|max:191',
            'description' => 'nullable|string',
            'active' => 'boolean',  // CORRIGÉ
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
            fputcsv($file, ['Nom', 'Code', 'Ville', 'Téléphone', 'Email', 'Membres', 'Actif']);

            foreach ($ecoles as $ecole) {
                fputcsv($file, [
                    $ecole->nom,
                    $ecole->code,
                    $ecole->ville,
                    $ecole->telephone,
                    $ecole->email,
                    $ecole->membres_count,
                    $ecole->active ? 'Oui' : 'Non',  // CORRIGÉ
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
