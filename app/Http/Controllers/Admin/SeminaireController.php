<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SeminaireRequest;
use App\Models\Seminaire;
use App\Models\Ecole;
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
            new Middleware('can:view-seminaires', only: ['index', 'show']),
            new Middleware('can:create-seminaire', only: ['create', 'store']),
            new Middleware('can:edit-seminaire', only: ['edit', 'update']),
            new Middleware('can:delete-seminaire', only: ['destroy']),
        ];
    }

    public function index()
    {
        $seminaires = Seminaire::with('ecole')
            ->when(auth()->user()->ecole_id, fn($q, $ecole_id) => $q->where('ecole_id', $ecole_id))
            ->paginate(15);
        
        // Ajouter les variables manquantes pour la vue
        $ecoles = collect();
        if (auth()->user()->hasRole('super-admin')) {
            $ecoles = Ecole::orderBy('nom')->get();
        }
            
        return view('admin.seminaires.index', compact('seminaires', 'ecoles'));
    }

    public function show(Seminaire $seminaire)
    {
        return view('admin.seminaires.show', compact('seminaire'));
    }

    public function create()
    {
        $ecoles = auth()->user()->hasRole('super-admin') 
            ? Ecole::all() 
            : collect([auth()->user()->ecole]);
            
        return view('admin.seminaires.create', compact('ecoles'));
    }

    public function store(SeminaireRequest $request)
    {
        // TODO: Implémenter
        return redirect()->route('admin.seminaires.index');
    }

    public function edit(Seminaire $seminaire)
    {
        $ecoles = auth()->user()->hasRole('super-admin') 
            ? Ecole::all() 
            : collect([auth()->user()->ecole]);
            
        return view('admin.seminaires.edit', compact('seminaire', 'ecoles'));
    }

    public function update(SeminaireRequest $request, Seminaire $seminaire)
    {
        // TODO: Implémenter
        return redirect()->route('admin.seminaires.show', $seminaire);
    }

    public function destroy(Seminaire $seminaire)
    {
        // TODO: Implémenter
        return redirect()->route('admin.seminaires.index');
    }
}
