<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaiementRequest;
use App\Models\Paiement;
use App\Models\Ecole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PaiementController extends Controller implements HasMiddleware
{
    /**
     * Middleware Laravel 12.19 avec autorisation selon les Policies
     */
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:viewAny,App\Models\Paiement', only: ['index']),
            new Middleware('can:view,paiement', only: ['show']),
            new Middleware('can:create,App\Models\Paiement', only: ['create', 'store']),
            new Middleware('can:update,paiement', only: ['edit', 'update']),
            new Middleware('can:delete,paiement', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Paiement::with(['user', 'ecole'])->orderBy('created_at', 'desc');
        
        // Filtre par école pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_interne', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        // Filtre par motif
        if ($request->filled('motif')) {
            $query->where('motif', $request->motif);
        }
        
        // Filtre par école
        if ($request->filled('ecole_id')) {
            $query->where('ecole_id', $request->ecole_id);
        }
        
        $paiements = $query->paginate(15)->withQueryString();
        $ecoles = $this->getEcolesForUser(auth()->user());
        
        return view('admin.paiements.index', compact('paiements', 'ecoles'));
    }

    public function create()
    {
        $ecoles = $this->getEcolesForUser(auth()->user());
        $users = $this->getUsersForPaiement();
        
        return view('admin.paiements.create', compact('ecoles', 'users'));
    }

    public function store(PaiementRequest $request)
    {
        $validated = $request->validated();
        
        // Assigner l'école pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }
        
        $validated['processed_by_user_id'] = auth()->id();
        $validated['reference_interne'] = 'PAY-' . strtoupper(uniqid());
        
        $paiement = Paiement::create($validated);
        
        return redirect()->route('admin.paiements.index')
            ->with('success', 'Paiement créé avec succès.');
    }

    public function show(Paiement $paiement)
    {
        $paiement->load(['user', 'ecole', 'processedBy']);
        
        return view('admin.paiements.show', compact('paiement'));
    }

    public function edit(Paiement $paiement)
    {
        $ecoles = $this->getEcolesForUser(auth()->user());
        $users = $this->getUsersForPaiement();
        
        return view('admin.paiements.edit', compact('paiement', 'ecoles', 'users'));
    }

    public function update(PaiementRequest $request, Paiement $paiement)
    {
        $validated = $request->validated();
        
        // Assigner l'école pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }
        
        $paiement->update($validated);
        
        return redirect()->route('admin.paiements.index')
            ->with('success', 'Paiement mis à jour avec succès.');
    }

    public function destroy(Paiement $paiement)
    {
        $paiement->delete();
        
        return redirect()->route('admin.paiements.index')
            ->with('success', 'Paiement supprimé avec succès.');
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
    private function getUsersForPaiement()
    {
        $query = User::with('ecole')->orderBy('name');
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        return $query->get();
    }
}
