<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use Illuminate\Http\Request;

class EcoleController extends Controller
{
    public static function middleware(): array
    {
        return [
            'can:viewAny,App\Models\Ecole' => ['only' => ['index']],
            'can:view,ecole' => ['only' => ['show']],
            'can:create,App\Models\Ecole' => ['only' => ['create', 'store']],
            'can:update,ecole' => ['only' => ['edit', 'update']],
            'can:delete,ecole' => ['only' => ['destroy']],
        ];
    }

    public function index()
    {
        $ecoles = Ecole::withCount(['users', 'cours'])
            ->orderBy('nom')
            ->paginate(15);

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
            'code' => 'required|string|max:10|unique:ecoles',
            'adresse' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'province' => 'required|string|max:50',
            'code_postal' => 'required|string|max:10',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:ecoles',
            'site_web' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'proprietaire' => 'nullable|string|max:255',
            'active' => 'boolean',
        ]);

        Ecole::create($validated);

        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École créée avec succès.');
    }

    public function show(Ecole $ecole)
    {
        $ecole->load(['users', 'cours']);
        
        return view('admin.ecoles.show', compact('ecole'));
    }

    public function edit(Ecole $ecole)
    {
        return view('admin.ecoles.edit', compact('ecole'));
    }

    public function update(Request $request, Ecole $ecole)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:ecoles,code,' . $ecole->id,
            'adresse' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'province' => 'required|string|max:50',
            'code_postal' => 'required|string|max:10',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:ecoles,email,' . $ecole->id,
            'site_web' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'proprietaire' => 'nullable|string|max:255',
            'active' => 'boolean',
        ]);

        $ecole->update($validated);

        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École mise à jour avec succès.');
    }

    public function destroy(Ecole $ecole)
    {
        if ($ecole->users()->count() > 0) {
            return redirect()->route('admin.ecoles.index')
                ->with('error', 'Impossible de supprimer une école qui a des utilisateurs.');
        }

        $ecole->delete();

        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École supprimée avec succès.');
    }
}
