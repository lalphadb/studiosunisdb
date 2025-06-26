<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeminaireRequest;
use App\Models\Seminaire;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SeminaireController extends Controller implements HasMiddleware
{
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
        ];
    }

    public function index(Request $request)
    {
        $query = Seminaire::orderBy('date_debut', 'desc');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('instructeur', 'like', "%{$search}%")
                  ->orWhere('lieu', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $seminaires = $query->paginate(15)->withQueryString();
        
        return view('admin.seminaires.index', compact('seminaires'));
    }

    public function create()
    {
        return view('admin.seminaires.create');
    }

    public function store(SeminaireRequest $request)
    {
        try {
            $validated = $request->validated();
            $seminaire = Seminaire::create($validated);
            
            return redirect()->route('admin.seminaires.index')
                ->with('success', 'Séminaire créé avec succès.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function show(Seminaire $seminaire)
    {
        $seminaire->load(['inscriptions.user']);
        return view('admin.seminaires.show', compact('seminaire'));
    }

    public function edit(Seminaire $seminaire)
    {
        return view('admin.seminaires.edit', compact('seminaire'));
    }

    public function update(SeminaireRequest $request, Seminaire $seminaire)
    {
        try {
            $validated = $request->validated();
            $seminaire->update($validated);
            
            return redirect()->route('admin.seminaires.index')
                ->with('success', 'Séminaire mis à jour avec succès.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function destroy(Seminaire $seminaire)
    {
        try {
            $seminaire->delete();
            
            return redirect()->route('admin.seminaires.index')
                ->with('success', 'Séminaire supprimé avec succès.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function inscrire(Request $request, Seminaire $seminaire)
    {
        // Méthode pour inscription future
        return view('admin.seminaires.inscrire', compact('seminaire'));
    }
}
