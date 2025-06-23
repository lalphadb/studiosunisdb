<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CoursRequest;
use App\Models\{Cours, Ecole};
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CoursController extends Controller implements HasMiddleware
{
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
        $cours = Cours::with('ecole')->latest()->paginate(15);
        return view('admin.cours.index', compact('cours'));
    }

    public function create()
    {
        $ecoles = Ecole::where('active', true)->orderBy('nom')->get();
        return view('admin.cours.create', compact('ecoles'));
    }

    public function store(CoursRequest $request)
    {
        $cours = Cours::create($request->validated());
        
        return redirect()
            ->route('admin.cours.index')
            ->with('success', 'Cours créé avec succès!');
    }

    public function show(Cours $cours)
    {
        $cours->load('ecole');
        return view('admin.cours.show', compact('cours'));
    }

    public function edit(Cours $cours)
    {
        $ecoles = Ecole::where('active', true)->orderBy('nom')->get();
        return view('admin.cours.edit', compact('cours', 'ecoles'));
    }

    public function update(CoursRequest $request, Cours $cours)
    {
        $cours->update($request->validated());
        
        return redirect()
            ->route('admin.cours.index')
            ->with('success', 'Cours modifié avec succès!');
    }

    public function destroy(Cours $cours)
    {
        $cours->delete();
        
        return redirect()
            ->route('admin.cours.index')
            ->with('success', 'Cours supprimé avec succès!');
    }
}
