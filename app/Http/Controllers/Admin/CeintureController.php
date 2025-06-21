<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ceinture;
use App\Models\User;
use App\Models\MembreCeinture;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CeintureController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Query de base avec relations
        $query = MembreCeinture::with(['user.ecole', 'ceinture']);
        
        // Restriction par école sauf superadmin
        if (!$user->hasRole('superadmin')) {
            $query->whereHas('membre', function($q) use ($user) {
                $q->where('ecole_id', $user->ecole_id);
            });
        }
        
        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('membre', function($mq) use ($search) {
                    $mq->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhereRaw("CONCAT(prenom, ' ', nom) LIKE ?", ["%{$search}%"]);
                });
            });
        }

        if ($request->filled('ecole_id')) {
            $query->whereHas('membre', function($q) use ($request) {
                $q->where('ecole_id', $request->ecole_id);
            });
        }
        
        $progressions = $query->orderBy('date_obtention', 'desc')->paginate(20);
        
        // Écoles pour le filtre
        $ecoles = $user->hasRole('superadmin') ? Ecole::orderBy('nom')->get() : collect([$user->ecole]);
        
        return view('admin.ceintures.index', compact('progressions', 'ecoles'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        
        // CORRECTION: Récupérer les membres actifs de l'école
        $membresQuery = User::where('active', true);
        
        if (!$user->hasRole('superadmin')) {
            $membresQuery->where('ecole_id', $user->ecole_id);
        }
        
        $membres = $membresQuery->orderBy('nom')->orderBy('prenom')->get();
        
        // Membre pré-sélectionné si ID passé en paramètre
        $membreSelectionne = null;
        if ($request->filled('user_id')) {
            $membreSelectionne = $membres->find($request->user_id);
        }
        
        // Toutes les ceintures ordonnées
        $ceintures = Ceinture::orderBy('ordre')->get();
        
        return view('admin.ceintures.create', compact('membres', 'ceintures', 'membreSelectionne'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date|before_or_equal:today',
            'examinateur' => 'nullable|string|max:255',
            'commentaires' => 'nullable|string'
        ]);
        
        $user = Auth::user();
        $membre = User::findOrFail($request->user_id);
        
        // Vérification permissions
        if (!$user->hasRole('superadmin') && $membre->ecole_id !== $user->ecole_id) {
            abort(403, 'Vous ne pouvez attribuer des ceintures qu\'aux membres de votre école.');
        }
        
        $progression = MembreCeinture::create([
            'user_id' => $request->user_id,
            'ceinture_id' => $request->ceinture_id,
            'date_obtention' => $request->date_obtention,
            'examinateur' => $request->examinateur ?? $user->name,
            'commentaires' => $request->commentaires,
            'valide' => true // Auto-validé
        ]);
        
        // Log activity
        activity()
            ->performedOn($progression)
            ->causedBy($user)
            ->log('Ceinture attribuée');
        
        return redirect()
            ->route('admin.ceintures.index')
            ->with('success', 'Ceinture attribuée avec succès à ' . $membre->nom_complet . '.');
    }

    public function show(MembreCeinture $ceinture)
    {
        $user = Auth::user();
        
        // Vérification permissions
        if (!$user->hasRole('superadmin') && $ceinture->user->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        $ceinture->load(['user.ecole', 'ceinture']);
        
        // Historique des progressions pour ce membre
        $historique = MembreCeinture::where('user_id', $ceinture->user_id)
            ->with('ceinture')
            ->orderBy('date_obtention', 'desc')
            ->get();
        
        return view('admin.ceintures.show', compact('ceinture', 'historique'))->with('progression', $ceinture);
    }

    public function edit(MembreCeinture $ceinture)
    {
        $user = Auth::user();
        
        // Vérification permissions
        if (!$user->hasRole('superadmin') && $ceinture->user->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        $$membres = User::whereHas('roles', function($q) { $q->whereIn('name', ['membre', 'instructeur']); })->where('ecole_id', $ceinture->user->ecole_id)
                         ->where('active', true)
                         ->orderBy('nom')
                         ->orderBy('prenom')
                         ->get();
                         
        $ceintures = Ceinture::orderBy('ordre')->get();
        
        return view('admin.ceintures.edit', compact('ceinture', 'membres', 'ceintures'))->with('progression', $ceinture);
    }

    public function update(Request $request, MembreCeinture $ceinture)
    {
        $user = Auth::user();
        
        // Vérifications
        if (!$user->hasRole('superadmin') && $ceinture->user->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        $request->validate([
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date|before_or_equal:today',
            'examinateur' => 'nullable|string|max:255',
            'commentaires' => 'nullable|string'
        ]);
        
        $ceinture->update([
            'ceinture_id' => $request->ceinture_id,
            'date_obtention' => $request->date_obtention,
            'examinateur' => $request->examinateur,
            'commentaires' => $request->commentaires
        ]);
        
        // Log activity
        activity()
            ->performedOn($ceinture)
            ->causedBy($user)
            ->log('Progression ceinture modifiée');
        
        return redirect()
            ->route('admin.ceintures.show', $ceinture)
            ->with('success', 'Progression mise à jour avec succès.');
    }

    public function destroy(MembreCeinture $ceinture)
    {
        $user = Auth::user();
        
        // Vérification permissions
        if (!$user->hasRole('superadmin')) {
            abort(403, 'Seuls les super-administrateurs peuvent supprimer des progressions.');
        }
        
        $nom = $ceinture->user->nom_complet;
        $ceintureNom = $ceinture->ceinture->nom;
        
        $ceinture->delete();
        
        // Log activity
        activity()
            ->causedBy($user)
            ->log("Progression ceinture supprimée: {$nom} - {$ceintureNom}");
        
        return redirect()
            ->route('admin.ceintures.index')
            ->with('success', 'Progression supprimée avec succès.');
    }
}
