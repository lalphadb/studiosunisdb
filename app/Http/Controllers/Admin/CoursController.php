<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            new Middleware('can:view,cour', only: ['show']),
            new Middleware('can:create,App\Models\Cours', only: ['create', 'store', 'clone']),
            new Middleware('can:update,cour', only: ['edit', 'update']),
            new Middleware('can:delete,cour', only: ['destroy']),
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
     * Dupliquer un cours avec modifications
     */
    public function clone(Request $request, Cours $cour)
    {
        $request->validate([
            'nombre_copies' => 'required|integer|min:1|max:10',
            'suffixes' => 'required|array',
            'suffixes.*' => 'required|string|max:100',
            'jours_semaine' => 'nullable|array',
            'jours_semaine.*' => 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'modifier_horaires' => 'boolean',
            'nouvelles_heures' => 'nullable|array',
            'nouvelles_heures.*' => 'nullable|string|max:50',
        ]);

        $coursClones = [];
        $suffixes = $request->suffixes;
        $joursSeaine = $request->jours_semaine ?? [];
        $nouvellesHeures = $request->nouvelles_heures ?? [];

        for ($i = 0; $i < $request->nombre_copies; $i++) {
            // Dupliquer le cours
            $nouveauCours = $cour->replicate();
            
            // Modifier le nom avec le suffixe
            $suffixe = $suffixes[$i] ?? "Copie " . ($i + 1);
            $nouveauCours->nom = $cour->nom . " - " . $suffixe;
            
            // Ajouter jour de la semaine si spécifié
            if (isset($joursSeaine[$i])) {
                $nouveauCours->nom .= " (" . ucfirst($joursSeaine[$i]) . ")";
            }
            
            // Modifier les horaires si demandé
            if ($request->modifier_horaires && isset($nouvellesHeures[$i])) {
                $nouveauCours->description = ($nouveauCours->description ?? '') . 
                    "\nHoraires: " . $nouvellesHeures[$i];
            }
            
            $nouveauCours->save();
            $coursClones[] = $nouveauCours;
        }

        return redirect()->route('admin.cours.index')
            ->with('success', count($coursClones) . ' cours dupliqués avec succès.');
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
