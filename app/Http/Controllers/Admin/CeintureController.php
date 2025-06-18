<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ceinture;
use App\Models\Membre;
use App\Models\MembreCeinture;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class CeintureController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Restriction par école sauf superadmin
        $query = MembreCeinture::with(['membre', 'ceinture', 'membre.ecole']);
        
        if (!$user->hasRole('superadmin')) {
            $query->whereHas('membre', function($q) use ($user) {
                $q->where('ecole_id', $user->ecole_id);
            });
        }
        
        // Filtres
        if ($request->filled('recherche')) {
            $recherche = $request->recherche;
            $query->where(function($q) use ($recherche) {
                $q->whereHas('membre', function($mq) use ($recherche) {
                    $mq->where('nom', 'like', "%{$recherche}%")
                      ->orWhere('prenom', 'like', "%{$recherche}%");
                })->orWhereHas('ceinture', function($cq) use ($recherche) {
                    $cq->where('nom', 'like', "%{$recherche}%");
                });
            });
        }
        
        $progressions = $query->orderBy('date_obtention', 'desc')->paginate(20);
        
        // Récupérer toutes les ceintures ordonnées par ordre
        $ceintures = Ceinture::orderBy('ordre')->get();
        
        $ecoles = $user->hasRole('superadmin') ? Ecole::orderBy('nom')->get() : collect([$user->ecole]);
        
        return view('admin.ceintures.index', compact('progressions', 'ceintures', 'ecoles'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Membres de l'école de l'utilisateur
        $membres = Membre::where('ecole_id', $user->ecole_id)
                         ->orderBy('nom')
                         ->orderBy('prenom')
                         ->get();
                         
        // Toutes les ceintures ordonnées
        $ceintures = Ceinture::orderBy('ordre')->get();
        
        return view('admin.ceintures.create', compact('membres', 'ceintures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'membre_id' => 'required|exists:membres,id',
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date',
            'examinateur' => 'nullable|string|max:255',
            'commentaires' => 'nullable|string'
        ]);
        
        $user = Auth::user();
        $membre = Membre::findOrFail($request->membre_id);
        
        // Vérification permissions
        if (!$user->hasRole('superadmin') && $membre->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        $progression = MembreCeinture::create([
            'membre_id' => $request->membre_id,
            'ceinture_id' => $request->ceinture_id,
            'date_obtention' => $request->date_obtention,
            'examinateur' => $request->examinateur,
            'commentaires' => $request->commentaires,
            'valide' => true // Auto-validé par défaut
        ]);
        
        // Log activity
        activity()
            ->performedOn($progression)
            ->causedBy($user)
            ->log('Progression ceinture créée');
        
        return redirect()
            ->route('admin.ceintures.index')
            ->with('success', 'Progression de ceinture enregistrée avec succès.');
    }

    public function show(MembreCeinture $ceinture)
    {
        $user = Auth::user();
        
        // Vérification permissions
        if (!$user->hasRole('superadmin') && $ceinture->membre->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        $ceinture->load(['membre', 'ceinture']);
        
        return view('admin.ceintures.show', compact('ceinture'));
    }

    public function edit(MembreCeinture $ceinture)
    {
        $user = Auth::user();
        
        // Vérification permissions
        if (!$user->hasRole('superadmin') && $ceinture->membre->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        $membres = Membre::where('ecole_id', $ceinture->membre->ecole_id)
                         ->orderBy('nom')
                         ->orderBy('prenom')
                         ->get();
                         
        $ceintures = Ceinture::orderBy('ordre')->get();
        
        return view('admin.ceintures.edit', compact('ceinture', 'membres', 'ceintures'));
    }

    public function update(Request $request, MembreCeinture $ceinture)
    {
        $user = Auth::user();
        
        // Vérifications
        if (!$user->hasRole('superadmin') && $ceinture->membre->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        $request->validate([
            'membre_id' => 'required|exists:membres,id',
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date',
            'examinateur' => 'nullable|string|max:255',
            'commentaires' => 'nullable|string',
            'valide' => 'boolean'
        ]);
        
        $ceinture->update($request->only([
            'membre_id', 'ceinture_id', 'date_obtention', 
            'examinateur', 'commentaires', 'valide'
        ]));
        
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
        if (!$user->hasRole('superadmin') && $ceinture->membre->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        $nom = $ceinture->membre->nom . ' ' . $ceinture->membre->prenom;
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

    public function dashboard()
    {
        $user = Auth::user();
        
        // Statistiques des progressions
        $query = MembreCeinture::with(['membre', 'ceinture']);
        
        if (!$user->hasRole('superadmin')) {
            $query->whereHas('membre', function($q) use ($user) {
                $q->where('ecole_id', $user->ecole_id);
            });
        }
        
        $stats = [
            'total_progressions' => $query->count(),
            'progressions_mois' => $query->clone()->whereMonth('date_obtention', now()->month)->count(),
            'en_attente_validation' => $query->clone()->where('valide', false)->count(),
            'validees_mois' => $query->clone()->where('valide', true)->whereMonth('date_obtention', now()->month)->count()
        ];
        
        // Répartition par ceinture
        $repartition = $query->clone()
            ->select('ceinture_id', \DB::raw('count(*) as total'))
            ->groupBy('ceinture_id')
            ->with('ceinture')
            ->get();
        
        return view('admin.ceintures.dashboard', compact('stats', 'repartition'));
    }

    public function certificat($id)
    {
        $progression = MembreCeinture::with(['membre', 'ceinture'])->findOrFail($id);
        $user = Auth::user();
        
        // Vérification permissions
        if (!$user->hasRole('superadmin') && $progression->membre->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        if (!$progression->valide) {
            return redirect()
                ->route('admin.ceintures.show', $progression)
                ->with('error', 'Seules les progressions validées peuvent générer un certificat.');
        }
        
        $pdf = PDF::loadView('admin.ceintures.certificat-pdf', compact('progression'));
        
        return $pdf->download('certificat-' . $progression->membre->nom . '-' . $progression->ceinture->nom . '.pdf');
    }

    public function approuver(MembreCeinture $ceinture)
    {
        $user = Auth::user();
        
        if (!$user->hasPermissionTo('manage-ceintures')) {
            abort(403);
        }
        
        if (!$user->hasRole('superadmin') && $ceinture->membre->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        $ceinture->valide = true;
        $ceinture->save();
        
        // Log activity
        activity()
            ->performedOn($ceinture)
            ->causedBy($user)
            ->log('Progression ceinture approuvée');
        
        return redirect()
            ->route('admin.ceintures.show', $ceinture)
            ->with('success', 'Progression approuvée avec succès.');
    }

    public function rejeter(MembreCeinture $ceinture)
    {
        $user = Auth::user();
        
        if (!$user->hasPermissionTo('manage-ceintures')) {
            abort(403);
        }
        
        if (!$user->hasRole('superadmin') && $ceinture->membre->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        $ceinture->valide = false;
        $ceinture->save();
        
        // Log activity
        activity()
            ->performedOn($ceinture)
            ->causedBy($user)
            ->log('Progression ceinture rejetée');
        
        return redirect()
            ->route('admin.ceintures.show', $ceinture)
            ->with('success', 'Progression rejetée.');
    }
}
