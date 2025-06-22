<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CeintureRequest;
use App\Models\Ceinture;
use App\Models\MembreCeinture;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CeintureController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:view-ceintures', only: ['index', 'show']),
            new Middleware('can:create-ceinture', only: ['create', 'store']),
            new Middleware('can:edit-ceinture', only: ['edit', 'update']),
            new Middleware('can:delete-ceinture', only: ['destroy']),
            new Middleware('can:assign-ceintures', only: ['attribuer']),
        ];
    }

    public function index(Request $request)
    {
        $query = MembreCeinture::with(['user', 'ceinture', 'user.ecole'])
            ->when(auth()->user()->ecole_id, function($q, $ecole_id) {
                return $q->whereHas('user', fn($query) => $query->where('ecole_id', $ecole_id));
            });

        // Recherche
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        $progressions = $query->latest('date_obtention')->paginate(15);
        
        // Pour les filtres
        $ecoles = collect();
        if (auth()->user()->hasRole('super-admin')) {
            $ecoles = \App\Models\Ecole::orderBy('nom')->get();
        }

        return view('admin.ceintures.index', compact('progressions', 'ecoles'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Utilisateurs disponibles
        $users = User::with(['ecole'])
            ->where('active', true)
            ->when(!$user->hasRole('super-admin'), function($q) use ($user) {
                return $q->where('ecole_id', $user->ecole_id);
            })
            ->orderBy('name')
            ->get();
        
        // Ceintures disponibles
        $ceintures = Ceinture::orderBy('ordre')->get();
        
        return view('admin.ceintures.create', compact('users', 'ceintures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date|before_or_equal:today',
            'examinateur' => 'nullable|string|max:255',
            'commentaires' => 'nullable|string|max:1000',
            'valide' => 'boolean'
        ]);

        // Vérifier si l'utilisateur a déjà cette ceinture
        $existeDeja = MembreCeinture::where('user_id', $request->user_id)
            ->where('ceinture_id', $request->ceinture_id)
            ->exists();

        if ($existeDeja) {
            return redirect()->back()
                ->with('error', 'Cet utilisateur possède déjà cette ceinture.');
        }

        MembreCeinture::create([
            'user_id' => $request->user_id,
            'ceinture_id' => $request->ceinture_id,
            'date_obtention' => $request->date_obtention,
            'examinateur' => $request->examinateur,
            'commentaires' => $request->commentaires,
            'valide' => $request->boolean('valide', false)
        ]);

        return redirect()->route('admin.ceintures.index')
            ->with('success', 'Ceinture attribuée avec succès !');
    }

    public function show(MembreCeinture $ceinture)
    {
        $progression = $ceinture;
        $progression->load(['user', 'ceinture', 'user.ecole']);
        
        // Historique des progressions de cet utilisateur
        $historique = MembreCeinture::where('user_id', $progression->user_id)
            ->with(['ceinture'])
            ->orderBy('date_obtention', 'desc')
            ->get();

        return view('admin.ceintures.show', compact('progression', 'historique'));
    }

    public function edit(MembreCeinture $ceinture)
    {
        $progression = $ceinture;
        $progression->load(['user', 'ceinture']);
        
        $ceintures = Ceinture::orderBy('ordre')->get();
        
        return view('admin.ceintures.edit', compact('progression', 'ceintures'));
    }

    public function update(Request $request, MembreCeinture $ceinture)
    {
        $progression = $ceinture;
        
        $request->validate([
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date|before_or_equal:today',
            'examinateur' => 'nullable|string|max:255',
            'commentaires' => 'nullable|string|max:1000',
            'valide' => 'boolean'
        ]);

        $progression->update([
            'ceinture_id' => $request->ceinture_id,
            'date_obtention' => $request->date_obtention,
            'examinateur' => $request->examinateur,
            'commentaires' => $request->commentaires,
            'valide' => $request->boolean('valide', false)
        ]);

        return redirect()->route('admin.ceintures.show', $progression)
            ->with('success', 'Attribution mise à jour avec succès !');
    }

    public function destroy(MembreCeinture $ceinture)
    {
        $progression = $ceinture;
        $userName = $progression->user->name;
        $ceintureNom = $progression->ceinture->nom;
        
        $progression->delete();

        return redirect()->route('admin.ceintures.index')
            ->with('success', "Attribution de la ceinture {$ceintureNom} pour {$userName} supprimée.");
    }
}
