<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ceinture;
use App\Models\Membre;
use App\Models\MembreCeinture;
use App\Models\Ecole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Carbon\Carbon;

class CeintureController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            'permission:manage_ceintures',
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = MembreCeinture::with(['membre', 'ceinture', 'membre.ecole']);
        
        if (!$user->hasRole('superadmin')) {
            $query->whereHas('membre', function($q) use ($user) {
                $q->where('ecole_id', $user->ecole_id);
            });
        }

        if ($request->filled('ecole_id') && $user->hasRole('superadmin')) {
            $query->whereHas('membre', function($q) use ($request) {
                $q->where('ecole_id', $request->ecole_id);
            });
        }

        if ($request->filled('ceinture_id')) {
            $query->where('ceinture_id', $request->ceinture_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('membre', function($q) use ($search) {
                $q->where('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('nom', 'LIKE', "%{$search}%");
            });
        }

        $progressions = $query->orderBy('date_obtention', 'desc')->paginate(15);
        $ceintures = Ceinture::orderBy('niveau')->get();
        $ecoles = $user->hasRole('superadmin') ? Ecole::orderBy('nom')->get() : collect([$user->ecole]);

        return view('admin.ceintures.index', compact('progressions', 'ceintures', 'ecoles'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        
        $membresQuery = Membre::with(['ecole', 'derniereCeinture.ceinture']);
        
        if (!$user->hasRole('superadmin')) {
            $membresQuery->where('ecole_id', $user->ecole_id);
        }
        
        $membres = $membresQuery->where('statut', 'actif')->orderBy('nom')->get();
        $ceintures = Ceinture::orderBy('niveau')->get();
        
        $membreSelectionne = null;
        if ($request->filled('membre_id')) {
            $membreSelectionne = $membres->firstWhere('id', $request->membre_id);
        }

        return view('admin.ceintures.create', compact('membres', 'ceintures', 'membreSelectionne'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'membre_id' => 'required|exists:membres,id',
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        $membre = Membre::findOrFail($request->membre_id);

        if (!$user->hasRole('superadmin') && $membre->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé à ce membre.');
        }

        $derniereCeinture = $membre->derniereCeinture?->ceinture;
        $nouvelleCeinture = Ceinture::findOrFail($request->ceinture_id);
        
        if ($derniereCeinture && $nouvelleCeinture->niveau <= $derniereCeinture->niveau) {
            return back()->withErrors(['ceinture_id' => 'La nouvelle ceinture doit être de niveau supérieur à la ceinture actuelle.'])
                        ->withInput();
        }

        // Créer l'attribution en attente d'approbation
        MembreCeinture::create([
            'membre_id' => $request->membre_id,
            'ceinture_id' => $request->ceinture_id,
            'date_obtention' => $request->date_obtention,
            'notes' => $request->notes,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('admin.ceintures.index')
            ->with('success', 'Ceinture en attente d\'approbation pour ' . $membre->prenom . ' ' . $membre->nom);
    }

    public function show(string $id)
    {
        $progression = MembreCeinture::with(['membre.ecole', 'ceinture'])->findOrFail($id);
        $user = auth()->user();

        if (!$user->hasRole('superadmin') && $progression->membre->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé.');
        }

        $historique = MembreCeinture::where('membre_id', $progression->membre_id)
            ->with('ceinture')
            ->orderBy('date_obtention', 'desc')
            ->get();

        return view('admin.ceintures.show', compact('progression', 'historique'));
    }

    public function edit(string $id)
    {
        $progression = MembreCeinture::with(['membre.ecole', 'ceinture'])->findOrFail($id);
        $user = auth()->user();

        if (!$user->hasRole('superadmin') && $progression->membre->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé.');
        }

        $ceintures = Ceinture::orderBy('niveau')->get();

        return view('admin.ceintures.edit', compact('progression', 'ceintures'));
    }

    public function update(Request $request, string $id)
    {
        $progression = MembreCeinture::with('membre')->findOrFail($id);
        $user = auth()->user();

        if (!$user->hasRole('superadmin') && $progression->membre->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        $progression->update($request->only(['ceinture_id', 'date_obtention', 'notes']));

        return redirect()->route('admin.ceintures.show', $progression->id)
            ->with('success', 'Attribution mise à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $progression = MembreCeinture::with('membre')->findOrFail($id);
        $user = auth()->user();

        if (!$user->hasRole('superadmin') && $progression->membre->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé.');
        }

        if (!$user->hasRole('superadmin')) {
            abort(403, 'Seul le superadmin peut supprimer des attributions de ceintures.');
        }

        $membreNom = $progression->membre->prenom . ' ' . $progression->membre->nom;
        $ceinture = $progression->ceinture->nom;
        
        $progression->delete();

        return redirect()->route('admin.ceintures.index')
            ->with('success', "Attribution de {$ceinture} pour {$membreNom} supprimée.");
    }

    /**
     * Approuver une progression de ceinture (Admin d'école seulement)
     */
    public function approuver($id)
    {
        $progression = MembreCeinture::with('membre')->findOrFail($id);
        $user = auth()->user();

        // Vérification des permissions par école
        if (!$user->hasRole('superadmin') && $progression->membre->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé à ce membre.');
        }

        // Seuls admin et superadmin peuvent approuver
        if (!$user->hasAnyRole(['superadmin', 'admin'])) {
            abort(403, 'Seuls les administrateurs peuvent approuver les ceintures.');
        }

        // Approuver la progression
        $progression->update(['statut' => 'approuve']);

        return redirect()->route('admin.ceintures.index')
            ->with('success', 'Ceinture approuvée pour ' . $progression->membre->prenom . ' ' . $progression->membre->nom);
    }

    /**
     * Rejeter une progression de ceinture
     */
    public function rejeter(Request $request, $id)
    {
        $progression = MembreCeinture::with('membre')->findOrFail($id);
        $user = auth()->user();

        // Vérification des permissions par école
        if (!$user->hasRole('superadmin') && $progression->membre->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé à ce membre.');
        }

        // Seuls admin et superadmin peuvent rejeter
        if (!$user->hasAnyRole(['superadmin', 'admin'])) {
            abort(403, 'Seuls les administrateurs peuvent rejeter les ceintures.');
        }

        // Rejeter avec note optionnelle
        $progression->update([
            'statut' => 'rejete',
            'notes' => $progression->notes . ' | Rejeté: ' . ($request->raison_rejet ?? 'Aucune raison spécifiée')
        ]);

        return redirect()->route('admin.ceintures.index')
            ->with('success', 'Progression rejetée pour ' . $progression->membre->prenom . ' ' . $progression->membre->nom);
    }
}
