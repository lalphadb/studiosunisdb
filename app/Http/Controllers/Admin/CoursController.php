<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Ecole;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin', 'ecole.restriction']);
    }

    public function index()
    {
        $cours = Cours::with(['ecole', 'horaires'])
            ->when(!auth()->user()->hasRole('super-admin'), function ($query) {
                $query->where('ecole_id', session('ecole_id'));
            })
            ->orderBy('nom')
            ->paginate(20);

        return view('admin.cours.index', compact('cours'));
    }

    public function create()
    {
        $ecoles = auth()->user()->hasRole('super-admin') 
            ? Ecole::all() 
            : Ecole::where('id', session('ecole_id'))->get();
            
        return view('admin.cours.create', compact('ecoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|text',
            'ecole_id' => 'required|exists:ecoles,id',
            'type' => 'required|in:regulier,special,seminaire',
            'prix_mensuel' => 'nullable|numeric|min:0',
            'prix_seance' => 'nullable|numeric|min:0',
            'duree_minutes' => 'required|integer|min:30',
            'actif' => 'boolean',
        ]);

        $cours = Cours::create($validated);
        
        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours créé avec succès.');
    }

    public function show(Cours $cour)
    {
        $this->authorizeAccess($cour);
        $cour->load(['ecole', 'horaires', 'inscriptions.user']);
        
        return view('admin.cours.show', compact('cour'));
    }

    public function edit(Cours $cour)
    {
        $this->authorizeAccess($cour);
        
        $ecoles = auth()->user()->hasRole('super-admin') 
            ? Ecole::all() 
            : Ecole::where('id', session('ecole_id'))->get();
            
        return view('admin.cours.edit', compact('cour', 'ecoles'));
    }

    public function update(Request $request, Cours $cour)
    {
        $this->authorizeAccess($cour);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|text',
            'ecole_id' => 'required|exists:ecoles,id',
            'type' => 'required|in:regulier,special,seminaire',
            'prix_mensuel' => 'nullable|numeric|min:0',
            'prix_seance' => 'nullable|numeric|min:0',
            'duree_minutes' => 'required|integer|min:30',
            'actif' => 'boolean',
        ]);

        $cour->update($validated);
        
        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours mis à jour avec succès.');
    }

    public function destroy(Cours $cour)
    {
        $this->authorizeAccess($cour);
        
        if ($cour->inscriptions()->exists()) {
            return back()->with('error', 'Impossible de supprimer un cours avec des inscriptions.');
        }

        $cour->delete();
        
        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours supprimé avec succès.');
    }

    private function authorizeAccess(Cours $cours)
    {
        if (!auth()->user()->hasRole('super-admin') && $cours->ecole_id !== session('ecole_id')) {
            abort(403, 'Accès non autorisé à ce cours.');
        }
    }
}
