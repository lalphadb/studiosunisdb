<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Ecole;
use App\Http\Requests\CoursRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class CoursController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:viewAny,App\Models\Cours', only: ['index']),
            new Middleware('can:view,cour', only: ['show']),
            new Middleware('can:create,App\Models\Cours', only: ['create', 'store', 'showCloneForm', 'clone']),
            new Middleware('can:update,cour', only: ['edit', 'update']),
            new Middleware('can:delete,cour', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Cours::with(['ecole']);

        // CORRECTION: Filtrage multi-tenant strict selon les rôles
        $user = auth()->user();
        if ($user->hasRole('admin_ecole')) {
            $query->where('ecole_id', $user->ecole_id);
        } elseif ($user->hasRole('instructeur')) {
            // Instructeur ne voit QUE les cours qu'il enseigne
            $query->where('ecole_id', $user->ecole_id)
                  ->where('instructeur', $user->name);
        } elseif ($user->hasRole('membre')) {
            // Membre ne voit QUE les cours de son école ET actifs
            $query->where('ecole_id', $user->ecole_id)
                  ->where('active', true);
        }
        // Superadmin voit tout (pas de filtre)

        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('instructeur', 'like', "%{$search}%");
            });
        }

        if ($request->filled('ecole_id') && $user->hasRole('superadmin')) {
            $query->where('ecole_id', $request->ecole_id);
        }

        if ($request->filled('niveau')) {
            $query->where('niveau', $request->niveau);
        }

        $cours = $query->orderBy('nom')->paginate(15);
        
        $ecoles = [];
        if ($user->hasRole('superadmin')) {
            $ecoles = Ecole::where('active', true)->orderBy('nom')->get();
        }

        return view('admin.cours.index', compact('cours', 'ecoles'));
    }

    public function show(Cours $cour)
    {
        $cour->load(['ecole']);
        return view('admin.cours.show', ['cours' => $cour]);
    }

    public function create()
    {
        $ecoles = $this->getAvailableEcoles();
        return view('admin.cours.create', compact('ecoles'));
    }

    public function store(CoursRequest $request)
    {
        $validated = $request->validated();
        
        // Auto-assignation ecole_id pour admin_ecole
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
        return view('admin.cours.edit', ['cours' => $cour, 'ecoles' => $ecoles]);
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

    /**
     * Afficher le formulaire de duplication
     */
    public function showCloneForm(Cours $cour)
    {
        return view('admin.cours.clone', ['cours' => $cour]);
    }

    /**
     * CORRECTION: Dupliquer avec transaction DB pour éviter états incohérents
     */
    public function clone(Request $request, Cours $cour)
    {
        $validated = $request->validate([
            'nombre_copies' => 'required|integer|min:1|max:10',
            'suffixe' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            $coursClones = [];
            $suffixe = $validated['suffixe'] ?: 'Copie';

            for ($i = 1; $i <= $validated['nombre_copies']; $i++) {
                $nouveauCours = $cour->replicate();
                $nouveauCours->nom = $cour->nom . " - " . $suffixe . " " . $i;
                $nouveauCours->save();
                $coursClones[] = $nouveauCours;
            }

            DB::commit();

            return redirect()->route('admin.cours.index')
                ->with('success', count($coursClones) . ' cours dupliqués avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.cours.index')
                ->with('error', 'Erreur lors de la duplication : ' . $e->getMessage());
        }
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
