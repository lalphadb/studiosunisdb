<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Ecole;
use App\Http\Requests\CoursRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CoursController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:viewAny,App\Models\Cours', only: ['index']),
            new Middleware('can:view,cour', only: ['show']), // Changé ici
            new Middleware('can:create,App\Models\Cours', only: ['create', 'store']),
            new Middleware('can:update,cour', only: ['edit', 'update']), // Changé ici
            new Middleware('can:delete,cour', only: ['destroy']), // Changé ici
        ];
    }

    public function index(Request $request)
    {
        $query = Cours::with(['ecole']);

        // Multi-tenant automatique
        $user = auth()->user();
        if ($user->hasRole('admin_ecole')) {
            $query->where('ecole_id', $user->ecole_id);
        } elseif ($user->hasRole('instructeur')) {
            $query->where('ecole_id', $user->ecole_id);
        } elseif ($user->hasRole('membre')) {
            $query->where('ecole_id', $user->ecole_id);
        }

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('ecole_id') && $user->hasRole('superadmin')) {
            $query->where('ecole_id', $request->ecole_id);
        }

        $cours = $query->orderBy('nom')->paginate(15);
        
        $ecoles = [];
        if ($user->hasRole('superadmin')) {
            $ecoles = Ecole::where('active', true)->orderBy('nom')->get();
        }

        return view('admin.cours.index', compact('cours', 'ecoles'));
    }

    // Utiliser 'cour' comme paramètre (singulier)
    public function show(Cours $cour)
    {
        $cour->load(['ecole']);
        return view('admin.cours.show', compact('cours' => $cour));
    }

    public function create()
    {
        $ecoles = $this->getAvailableEcoles();
        return view('admin.cours.create', compact('ecoles'));
    }

    public function store(CoursRequest $request)
    {
        $validated = $request->validated();
        
        // Multi-tenant automatique
        if (auth()->user()->hasRole('admin_ecole')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }
        
        $cours = Cours::create($validated);
        
        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours créé avec succès.');
    }

    public function edit(Cours $cour)
    {
        $ecoles = $this->getAvailableEcoles();
        return view('admin.cours.edit', compact('cours' => $cour, 'ecoles'));
    }

    public function update(CoursRequest $request, Cours $cour)
    {
        $validated = $request->validated();
        
        // Multi-tenant - admin école ne peut pas changer l'école
        if (auth()->user()->hasRole('admin_ecole')) {
            unset($validated['ecole_id']);
        }
        
        $cour->update($validated);
        
        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours modifié avec succès.');
    }

    public function destroy(Cours $cour)
    {
        $cour->delete();
        
        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours supprimé avec succès.');
    }

    private function getAvailableEcoles()
    {
        $user = auth()->user();
        
        if ($user->hasRole('superadmin')) {
            return Ecole::where('active', true)->orderBy('nom')->get();
        }
        
        if ($user->hasRole('admin_ecole')) {
            return Ecole::where('id', $user->ecole_id)->get();
        }
        
        return collect();
    }
}
