<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EcoleRequest;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EcoleController extends Controller implements HasMiddleware
{
    /**
     * Middleware Laravel 12.19 avec autorisation selon les Policies
     */
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:viewAny,App\Models\Ecole', only: ['index']),
            new Middleware('can:view,ecole', only: ['show']),
            new Middleware('can:create,App\Models\Ecole', only: ['create', 'store']),
            new Middleware('can:update,ecole', only: ['edit', 'update']),
            new Middleware('can:delete,ecole', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Ecole::withCount(['users'])->orderBy('nom');
        
        // Filtre par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('ville', 'like', "%{$search}%");
            });
        }
        
        // Filtre par province
        if ($request->filled('province')) {
            $query->where('province', $request->province);
        }
        
        // Filtre par statut
        if ($request->has('active')) {
            $query->where('active', $request->boolean('active'));
        }
        
        $ecoles = $query->paginate(15)->withQueryString();
        
        return view('admin.ecoles.index', compact('ecoles'));
    }

    public function create()
    {
        return view('admin.ecoles.create');
    }

    public function store(EcoleRequest $request)
    {
        $ecole = Ecole::create($request->validated());
        
        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École créée avec succès.');
    }

    public function show(Ecole $ecole)
    {
        $ecole->load(['users.roles', 'cours']);
        
        return view('admin.ecoles.show', compact('ecole'));
    }

    public function edit(Ecole $ecole)
    {
        return view('admin.ecoles.edit', compact('ecole'));
    }

    public function update(EcoleRequest $request, Ecole $ecole)
    {
        $ecole->update($request->validated());
        
        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École mise à jour avec succès.');
    }

    public function destroy(Ecole $ecole)
    {
        $ecole->delete();
        
        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École supprimée with succès.');
    }
}
