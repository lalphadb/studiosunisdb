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
    /**
     * Get the middleware that should be assigned to the controller.
     */
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
            $membres = Membre::with(['ecole', 'derniereCeinture.ceinture'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            $membres = Membre::with(['ecole', 'derniereCeinture.ceinture'])
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
            $ecoles = Ecole::where('statut', 'actif')->orderBy('nom')->get();
        } else {
            $ecoles = Ecole::where('id', $user->ecole_id)
                ->where('statut', 'actif')
                ->orderBy('nom')
                ->get();
        }

        return view('admin.membres.create', compact('ecoles'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'nullable|email|unique:membres,email',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'adresse' => 'nullable|string',
            'ecole_id' => 'required|exists:ecoles,id',
            'date_inscription' => 'required|date',
            'statut' => 'required|in:actif,inactif,suspendu',
            'contact_urgence' => 'nullable|string|max:255',
            'telephone_urgence' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        if (! $user->hasRole('superadmin') && $validated['ecole_id'] != $user->ecole_id) {
            abort(403, 'Vous ne pouvez créer des membres que pour votre école.');
        }

        Membre::create($validated);

        return redirect()->route('admin.membres.index')
            ->with('success', 'Membre créé avec succès !');
    }

    public function show(Membre $membre)
    {
        $user = auth()->user();

        if (! $user->hasRole('superadmin') && $membre->ecole_id != $user->ecole_id) {
            abort(403, 'Vous ne pouvez voir que les membres de votre école.');
        }

        // Charger les relations nécessaires
        $membre->load(['derniereCeinture.ceinture', 'progressionsCeintures.ceinture', 'ecole']);

        return view('admin.membres.show', compact('membre'));
    }

    public function edit(Membre $membre)
    {
        $user = auth()->user();

        if (! $user->hasRole('superadmin') && $membre->ecole_id != $user->ecole_id) {
            abort(403, 'Vous ne pouvez modifier que les membres de votre école.');
        }

        if ($user->hasRole('superadmin')) {
            $ecoles = Ecole::where('statut', 'actif')->orderBy('nom')->get();
        } else {
            $ecoles = Ecole::where('id', $user->ecole_id)
                ->where('statut', 'actif')
                ->orderBy('nom')
                ->get();
        }

        return view('admin.membres.edit', compact('membre', 'ecoles'));
    }

    public function update(Request $request, Membre $membre)
    {
        $user = auth()->user();

        if (! $user->hasRole('superadmin') && $membre->ecole_id != $user->ecole_id) {
            abort(403, 'Vous ne pouvez modifier que les membres de votre école.');
        }

        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'nullable|email|unique:membres,email,'.$membre->id,
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'adresse' => 'nullable|string',
            'ecole_id' => 'required|exists:ecoles,id',
            'date_inscription' => 'required|date',
            'statut' => 'required|in:actif,inactif,suspendu',
            'contact_urgence' => 'nullable|string|max:255',
            'telephone_urgence' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        if (! $user->hasRole('superadmin') && $validated['ecole_id'] != $user->ecole_id) {
            abort(403, 'Vous ne pouvez assigner des membres qu\'à votre école.');
        }

        $membre->update($validated);

        return redirect()->route('admin.membres.index')
            ->with('success', 'Membre modifié avec succès !');
    }

    public function destroy(Membre $membre)
    {
        $user = auth()->user();

        if (! $user->hasRole('superadmin') && $membre->ecole_id != $user->ecole_id) {
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
            $filename = 'membres_'.str_slug($ecole->nom).'_'.date('Y-m-d').'.xlsx';
        }

        return Excel::download(new MembresExport($membres), $filename);
    }
}
