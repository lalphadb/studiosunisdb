<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\User;
use Illuminate\Http\Request;

class CoursController extends BaseAdminController
{
    public function index()
    {
        $cours = Cours::where('ecole_id', auth()->user()->ecole_id)
            ->with('instructeur')
            ->paginate(15);
            
        return view('admin.cours.index', compact('cours'));
    }

    public function create()
    {
        $instructeurs = User::where('ecole_id', auth()->user()->ecole_id)
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['instructeur', 'admin_ecole', 'superadmin']);
            })
            ->get();
            
        return view('admin.cours.create', compact('instructeurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructeur_id' => 'required|exists:users,id',
            'capacite_max' => 'required|integer|min:1',
            'prix' => 'nullable|numeric|min:0'
        ]);

        $data = $request->all();
        $data['ecole_id'] = auth()->user()->ecole_id;
        $data['actif'] = true;

        Cours::create($data);

        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours créé avec succès !');
    }

    public function show(Cours $cour)
    {
        return view('admin.cours.show', compact('cour'));
    }

    public function edit(Cours $cour)
    {
        $instructeurs = User::where('ecole_id', auth()->user()->ecole_id)
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['instructeur', 'admin_ecole', 'superadmin']);
            })
            ->get();
            
        return view('admin.cours.edit', compact('cour', 'instructeurs'));
    }

    public function update(Request $request, Cours $cour)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructeur_id' => 'required|exists:users,id',
            'capacite_max' => 'required|integer|min:1',
            'prix' => 'nullable|numeric|min:0'
        ]);

        $cour->update($request->all());

        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours mis à jour !');
    }

    public function destroy(Cours $cour)
    {
        $cour->delete();
        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours supprimé !');
    }
}
