<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MembresExport;
use App\Http\Controllers\Controller;
use App\Models\Ecole;
use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;

class MembreController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:view-membres', only: ['index', 'show']),
            new Middleware('can:create-membre', only: ['create', 'store']),
            new Middleware('can:edit-membre', only: ['edit', 'update']),
            new Middleware('can:delete-membre', only: ['destroy']),
            new Middleware('can:export-membres', only: ['export']),
        ];
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('superadmin')) {
            $membres = Membre::with(['ecole'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            $membres = Membre::with(['ecole'])
                ->where('ecole_id', $user->ecole_id)
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('admin.membres.index', compact('membres'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->hasRole('superadmin')) {
            $ecoles = Ecole::where('active', true)->orderBy('nom')->get();  // CORRIGÉ
        } else {
            $ecoles = Ecole::where('id', $user->ecole_id)
                ->where('active', true)  // CORRIGÉ
                ->orderBy('nom')
                ->get();
        }

        return view('admin.membres.create', compact('ecoles'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nom' => 'required|string|max:191',
            'prenom' => 'required|string|max:191',
            'email' => 'nullable|email|unique:membres,email',
            'telephone' => 'nullable|string|max:191',
            'date_naissance' => 'nullable|date',
            'sexe' => 'nullable|in:M,F,Autre',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:191',
            'code_postal' => 'nullable|string|max:191',
            'contact_urgence_nom' => 'nullable|string|max:191',
            'contact_urgence_telephone' => 'nullable|string|max:191',
            'ecole_id' => 'required|exists:ecoles,id',
            'active' => 'boolean',  // CORRIGÉ: selon vraie structure
            'notes' => 'nullable|string',
        ]);

        if (!$user->hasRole('superadmin') && $validated['ecole_id'] != $user->ecole_id) {
            abort(403, 'Vous ne pouvez créer des membres que pour votre école.');
        }

        // Valeurs par défaut
        $validated['date_inscription'] = now()->toDateString();
        $validated['active'] = $validated['active'] ?? true;

        Membre::create($validated);

        return redirect()->route('admin.membres.index')
            ->with('success', 'Membre créé avec succès !');
    }

    public function show(Membre $membre)
    {
        $user = auth()->user();

        if (!$user->hasRole('superadmin') && $membre->ecole_id != $user->ecole_id) {
            abort(403, 'Vous ne pouvez voir que les membres de votre école.');
        }

        $membre->load(['ecole']);

        return view('admin.membres.show', compact('membre'));
    }

    public function edit(Membre $membre)
    {
        $user = auth()->user();

        if (!$user->hasRole('superadmin') && $membre->ecole_id != $user->ecole_id) {
            abort(403, 'Vous ne pouvez modifier que les membres de votre école.');
        }

        if ($user->hasRole('superadmin')) {
            $ecoles = Ecole::where('active', true)->orderBy('nom')->get();  // CORRIGÉ
        } else {
            $ecoles = Ecole::where('id', $user->ecole_id)
                ->where('active', true)  // CORRIGÉ
                ->orderBy('nom')
                ->get();
        }

        return view('admin.membres.edit', compact('membre', 'ecoles'));
    }

    public function update(Request $request, Membre $membre)
    {
        $user = auth()->user();

        if (!$user->hasRole('superadmin') && $membre->ecole_id != $user->ecole_id) {
            abort(403, 'Vous ne pouvez modifier que les membres de votre école.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:191',
            'prenom' => 'required|string|max:191',
            'email' => 'nullable|email|unique:membres,email,'.$membre->id,
            'telephone' => 'nullable|string|max:191',
            'date_naissance' => 'nullable|date',
            'sexe' => 'nullable|in:M,F,Autre',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:191',
            'code_postal' => 'nullable|string|max:191',
            'contact_urgence_nom' => 'nullable|string|max:191',
            'contact_urgence_telephone' => 'nullable|string|max:191',
            'ecole_id' => 'required|exists:ecoles,id',
            'active' => 'boolean',  // CORRIGÉ
            'notes' => 'nullable|string',
        ]);

        if (!$user->hasRole('superadmin') && $validated['ecole_id'] != $user->ecole_id) {
            abort(403, 'Vous ne pouvez assigner des membres qu\'à votre école.');
        }

        $membre->update($validated);

        return redirect()->route('admin.membres.index')
            ->with('success', 'Membre modifié avec succès !');
    }

    public function destroy(Membre $membre)
    {
        $user = auth()->user();

        if (!$user->hasRole('superadmin') && $membre->ecole_id != $user->ecole_id) {
            abort(403, 'Vous ne pouvez supprimer que les membres de votre école.');
        }

        $membre->delete();

        return redirect()->route('admin.membres.index')
            ->with('success', 'Membre supprimé avec succès !');
    }

    public function export()
    {
        $user = auth()->user();

        if ($user->hasRole('superadmin')) {
            $membres = Membre::with('ecole')->get();
            $filename = 'membres_tous_'.date('Y-m-d').'.xlsx';
        } else {
            $membres = Membre::with('ecole')
                ->where('ecole_id', $user->ecole_id)
                ->get();
            $ecole = Ecole::find($user->ecole_id);
            $filename = 'membres_'.str_replace(' ', '_', $ecole->nom).'_'.date('Y-m-d').'.xlsx';
        }

        return Excel::download(new MembresExport($membres), $filename);
    }
}
