<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeminaireRequest;
use App\Models\Seminaire;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SeminaireController extends Controller implements HasMiddleware
{
    /**
     * Middleware Laravel 12.19 avec autorisation selon les Policies
     */
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:viewAny,App\Models\Seminaire', only: ['index']),
            new Middleware('can:view,seminaire', only: ['show']),
            new Middleware('can:create,App\Models\Seminaire', only: ['create', 'store']),
            new Middleware('can:update,seminaire', only: ['edit', 'update']),
            new Middleware('can:delete,seminaire', only: ['destroy']),
            new Middleware('can:inscrire,seminaire', only: ['inscrire']),
        ];
    }

    public function index(Request $request)
    {
        $query = Seminaire::with(['ecole'])->orderBy('date_debut', 'desc');
        
        // Filtre par école pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('instructeur', 'like', "%{$search}%");
            });
        }
        
        // Filtre par école
        if ($request->filled('ecole_id')) {
            $query->where('ecole_id', $request->ecole_id);
        }
        
        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $seminaires = $query->paginate(15)->withQueryString();
        $ecoles = $this->getEcolesForUser(auth()->user());
        
        return view('admin.seminaires.index', compact('seminaires', 'ecoles'));
    }

    public function create()
    {
        $ecoles = $this->getEcolesForUser(auth()->user());
        
        return view('admin.seminaires.create', compact('ecoles'));
    }

    public function store(SeminaireRequest $request)
    {
        $validated = $request->validated();
        
        // Assigner l'école pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }
        
        $seminaire = Seminaire::create($validated);
        
        return redirect()->route('admin.seminaires.index')
            ->with('success', 'Séminaire créé avec succès.');
    }

    public function show(Seminaire $seminaire)
    {
        $seminaire->load(['ecole', 'inscriptions.user']);
        
        return view('admin.seminaires.show', compact('seminaire'));
    }

    public function edit(Seminaire $seminaire)
    {
        $ecoles = $this->getEcolesForUser(auth()->user());
        
        return view('admin.seminaires.edit', compact('seminaire', 'ecoles'));
    }

    public function update(SeminaireRequest $request, Seminaire $seminaire)
    {
        $validated = $request->validated();
        
        // Assigner l'école pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }
        
        $seminaire->update($validated);
        
        return redirect()->route('admin.seminaires.index')
            ->with('success', 'Séminaire mis à jour avec succès.');
    }

    public function destroy(Seminaire $seminaire)
    {
        $seminaire->delete();
        
        return redirect()->route('admin.seminaires.index')
            ->with('success', 'Séminaire supprimé avec succès.');
    }

    public function inscrire(Request $request, Seminaire $seminaire)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'notes' => 'nullable|string'
            ]);
            
            $seminaire->inscriptions()->create([
                'user_id' => $request->user_id,
                'date_inscription' => now(),
                'statut' => 'inscrit',
                'notes' => $request->notes
            ]);
            
            return redirect()->route('admin.seminaires.show', $seminaire)
                ->with('success', 'Inscription effectuée avec succès.');
        }
        
        // Récupérer les utilisateurs selon les permissions
        $users = $this->getUsersForSeminaire();
        
        return view('admin.seminaires.inscrire', compact('seminaire', 'users'));
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
     * Obtenir les utilisateurs selon les permissions
     */
    private function getUsersForSeminaire()
    {
        $query = User::with('ecole')->orderBy('name');
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        return $query->get();
    }
}
