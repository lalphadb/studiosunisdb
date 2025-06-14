<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Ecole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CoursController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:view-cours', only: ['index', 'show']),
            new Middleware('can:create-cours', only: ['create', 'store']),
            new Middleware('can:edit-cours', only: ['edit', 'update']),
            new Middleware('can:delete-cours', only: ['destroy']),
        ];
    }

    public function index()
    {
        $user = auth()->user();
        
        if ($user->hasRole('superadmin')) {
            $cours = Cours::with(['ecole', 'instructeur'])
                ->withCount('inscriptions')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $cours = Cours::with(['ecole', 'instructeur'])
                ->withCount('inscriptions')
                ->where('ecole_id', $user->ecole_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('admin.cours.index', compact('cours'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->hasRole('superadmin')) {
            $ecoles = Ecole::where('statut', 'actif')->orderBy('nom')->get();
        } else {
            $ecoles = Ecole::where('id', $user->ecole_id)
                ->where('statut', 'actif')
                ->get();
        }
        
        if ($user->hasRole('superadmin')) {
            $instructeurs = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['instructeur', 'admin', 'superadmin']);
            })->orderBy('name')->get();
        } else {
            $instructeurs = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['instructeur', 'admin']);
            })
            ->where('ecole_id', $user->ecole_id)
            ->orderBy('name')
            ->get();
        }

        return view('admin.cours.create', compact('ecoles', 'instructeurs'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ecole_id' => 'required|exists:ecoles,id',
            'instructeur_id' => 'nullable|exists:users,id',
            'capacite_max' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,inactif,complet',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ]);

        if (!$user->hasRole('superadmin') && $validated['ecole_id'] != $user->ecole_id) {
            abort(403, 'Vous ne pouvez créer des cours que pour votre école.');
        }

        Cours::create($validated);

        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours créé avec succès !');
    }

    public function show(Cours $cours)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('superadmin') && $cours->ecole_id != $user->ecole_id) {
            abort(403, 'Accès non autorisé.');
        }
        
        $cours->load(['ecole', 'instructeur', 'inscriptions.membre']);
        
        return view('admin.cours.show', compact('cours'));
    }

    public function edit(Cours $cours)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('superadmin') && $cours->ecole_id != $user->ecole_id) {
            abort(403, 'Accès non autorisé.');
        }
        
        if ($user->hasRole('superadmin')) {
            $ecoles = Ecole::where('statut', 'actif')->orderBy('nom')->get();
            $instructeurs = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['instructeur', 'admin', 'superadmin']);
            })->orderBy('name')->get();
        } else {
            $ecoles = Ecole::where('id', $user->ecole_id)->where('statut', 'actif')->get();
            $instructeurs = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['instructeur', 'admin']);
            })->where('ecole_id', $user->ecole_id)->orderBy('name')->get();
        }

        return view('admin.cours.edit', compact('cours', 'ecoles', 'instructeurs'));
    }

    public function update(Request $request, Cours $cours)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('superadmin') && $cours->ecole_id != $user->ecole_id) {
            abort(403, 'Accès non autorisé.');
        }
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ecole_id' => 'required|exists:ecoles,id',
            'instructeur_id' => 'nullable|exists:users,id',
            'capacite_max' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
            'statut' => 'required|in:actif,inactif,complet',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ]);

        if (!$user->hasRole('superadmin') && $validated['ecole_id'] != $user->ecole_id) {
            abort(403, 'Vous ne pouvez modifier que les cours de votre école.');
        }

        $cours->update($validated);

        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours modifié avec succès !');
    }

    public function destroy(Cours $cours)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('superadmin') && $cours->ecole_id != $user->ecole_id) {
            abort(403, 'Accès non autorisé.');
        }
        
        $cours->delete();

        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours supprimé avec succès !');
    }
}
