<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoursRequest;
use App\Models\Cours;
use App\Models\Ecole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CoursController extends Controller implements HasMiddleware
{
    /**
     * Middleware Laravel 12.19 avec autorisation selon les Policies
     */
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:viewAny,App\Models\Cours', only: ['index']),
            new Middleware('can:view,cours', only: ['show']),
            new Middleware('can:create,App\Models\Cours', only: ['create', 'store']),
            new Middleware('can:update,cours', only: ['edit', 'update']),
            new Middleware('can:delete,cours', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Cours::with(['ecole'])->orderBy('created_at', 'desc');
        
        // Filtre par école pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('instructeur', 'like', "%{$search}%");
            });
        }
        
        // Filtre par école
        if ($request->filled('ecole_id')) {
            $query->where('ecole_id', $request->ecole_id);
        }
        
        // Filtre par niveau
        if ($request->filled('niveau')) {
            $query->where('niveau', $request->niveau);
        }
        
        // Filtre par statut
        if ($request->has('active')) {
            $query->where('active', $request->boolean('active'));
        }
        
        $cours = $query->paginate(15)->withQueryString();
        $ecoles = $this->getEcolesForUser(auth()->user());
        
        // AJOUT DES NIVEAUX POUR LES FILTRES
        $niveaux = $this->getNiveauxDisponibles();
        
        return view('admin.cours.index', compact('cours', 'ecoles', 'niveaux'));
    }

    public function create()
    {
        $ecoles = $this->getEcolesForUser(auth()->user());
        
        // FIX: AJOUT DES VARIABLES MANQUANTES
        $niveaux = $this->getNiveauxDisponibles();
        $instructeurs = $this->getInstructeursDisponibles();
        
        return view('admin.cours.create', compact('ecoles', 'niveaux', 'instructeurs'));
    }

    public function store(CoursRequest $request)
    {
        $validated = $request->validated();
        
        // Assigner l'école pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }
        
        $cours = Cours::create($validated);
        
        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours créé avec succès.');
    }

    public function show(Cours $cours)
    {
        $cours->load(['ecole', 'horaires', 'inscriptions.user']);
        
        return view('admin.cours.show', compact('cours'));
    }

    public function edit(Cours $cours)
    {
        $ecoles = $this->getEcolesForUser(auth()->user());
        
        // FIX: AJOUT DES VARIABLES MANQUANTES
        $niveaux = $this->getNiveauxDisponibles();
        $instructeurs = $this->getInstructeursDisponibles();
        
        return view('admin.cours.edit', compact('cours', 'ecoles', 'niveaux', 'instructeurs'));
    }

    public function update(CoursRequest $request, Cours $cours)
    {
        $validated = $request->validated();
        
        // Assigner l'école pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }
        
        $cours->update($validated);
        
        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours mis à jour avec succès.');
    }

    public function destroy(Cours $cours)
    {
        $cours->delete();
        
        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours supprimé avec succès.');
    }

    /**
     * Obtenir les écoles selon les permissions
     */
    private function getEcolesForUser($user)
    {
        if ($user->hasRole('superadmin')) {
            return Ecole::orderBy('nom')->get();
        } elseif ($user->hasRole('admin_ecole')) {
            return Ecole::where('id', $user->ecole_id)->get();
        }
        
        return Ecole::orderBy('nom')->get();
    }

    /**
     * FIX: Méthode pour obtenir les niveaux disponibles
     */
    private function getNiveauxDisponibles()
    {
        return [
            'debutant' => 'Débutant',
            'intermediaire' => 'Intermédiaire',
            'avance' => 'Avancé',
            'tous_niveaux' => 'Tous Niveaux'
        ];
    }

    /**
     * FIX: Méthode pour obtenir les instructeurs disponibles
     */
    private function getInstructeursDisponibles()
    {
        $query = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['instructeur', 'admin_ecole', 'superadmin']);
        })->where('active', true)->orderBy('name');
        
        // Multi-tenant: Admin d'école voit ses instructeurs
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        return $query->get();
    }
}
