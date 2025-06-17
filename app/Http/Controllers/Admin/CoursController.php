<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Ecole;
use App\Models\User;
use App\Models\Membre;
use App\Models\CoursHoraire;
use App\Models\InscriptionCours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CoursController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Cours::with(['ecole', 'instructeurPrincipal', 'horaires'])
                      ->withCount('inscriptions');

        // Restriction par école pour non-superadmin
        if (!$user->hasRole('superadmin')) {
            $query->where('ecole_id', $user->ecole_id);
        }

        // Filtres
        if ($request->filled('ecole_id') && $user->hasRole('superadmin')) {
            $query->where('ecole_id', $request->ecole_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type_cours')) {
            $query->where('type_cours', $request->type_cours);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $cours = $query->orderBy('nom')->paginate(15);

        // Données pour les filtres
        $ecoles = $user->hasRole('superadmin') ? 
                 Ecole::orderBy('nom')->get() : 
                 collect([]);

        return view('admin.cours.index', compact('cours', 'ecoles'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Écoles disponibles
        $ecoles = $user->hasRole('superadmin') ? 
                 Ecole::orderBy('nom')->get() : 
                 Ecole::where('id', $user->ecole_id)->get();

        // Instructeurs de l'école
        $instructeurs = User::role(['admin', 'instructeur'])
                           ->when(!$user->hasRole('superadmin'), function($query) use ($user) {
                               return $query->where('ecole_id', $user->ecole_id);
                           })
                           ->orderBy('name')
                           ->get();

        return view('admin.cours.create', compact('ecoles', 'instructeurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ecole_id' => 'required|exists:ecoles,id',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_cours' => 'required|in:regulier,specialise,competition,examen',
            'niveau_requis' => 'nullable|string|max:100',
            'age_min' => 'required|integer|min:3|max:99',
            'age_max' => 'required|integer|min:3|max:99|gte:age_min',
            'capacite_max' => 'required|integer|min:1|max:50',
            'duree_minutes' => 'required|integer|min:30|max:180',
            'prix_mensuel' => 'nullable|numeric|min:0',
            'prix_session' => 'nullable|numeric|min:0',
            'instructeur_principal_id' => 'nullable|exists:users,id',
            'instructeur_assistant_id' => 'nullable|exists:users,id|different:instructeur_principal_id',
            'status' => 'required|in:actif,inactif,complet,annule',
            'salle' => 'nullable|string|max:100',
            'materiel_requis' => 'nullable|string',
            'objectifs' => 'nullable|string',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ]);

        // Assigner l'école automatiquement si pas superadmin
        if (!Auth::user()->hasRole('superadmin')) {
            $validated['ecole_id'] = Auth::user()->ecole_id;
        }

        $cours = Cours::create($validated);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($cours)
            ->log('Cours créé');

        return redirect()
            ->route('admin.cours.index')
            ->with('success', 'Cours créé avec succès');
    }

    public function show(Cours $cours)
    {
        $cours->load([
            'ecole', 
            'instructeurPrincipal', 
            'instructeurAssistant',
            'horaires' => function($query) {
                $query->where('est_actif', true)->orderBy('jour_semaine');
            },
            'inscriptions.membre',
            'presences' => function($query) {
                $query->latest()->limit(10);
            }
        ]);

        // Statistiques
        $stats = [
            'total_inscriptions' => $cours->inscriptions()->where('status', 'active')->count(),
            'places_disponibles' => max(0, $cours->capacite_max - $cours->inscriptions()->where('status', 'active')->count()),
            'revenus_mensuels' => $cours->inscriptions()
                                       ->where('status', 'active')
                                       ->sum('montant_paye')
        ];

        return view('admin.cours.show', compact('cours', 'stats'));
    }

    public function edit(Cours $cours)
    {
        $user = Auth::user();
        
        $ecoles = $user->hasRole('superadmin') ? 
                 Ecole::orderBy('nom')->get() : 
                 Ecole::where('id', $user->ecole_id)->get();

        $instructeurs = User::role(['admin', 'instructeur'])
                           ->when(!$user->hasRole('superadmin'), function($query) use ($user) {
                               return $query->where('ecole_id', $user->ecole_id);
                           })
                           ->orderBy('name')
                           ->get();

        $cours->load('horaires');

        return view('admin.cours.edit', compact('cours', 'ecoles', 'instructeurs'));
    }

    public function update(Request $request, Cours $cours)
    {
        $validated = $request->validate([
            'ecole_id' => 'required|exists:ecoles,id',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_cours' => 'required|in:regulier,specialise,competition,examen',
            'niveau_requis' => 'nullable|string|max:100',
            'age_min' => 'required|integer|min:3|max:99',
            'age_max' => 'required|integer|min:3|max:99|gte:age_min',
            'capacite_max' => 'required|integer|min:1|max:50',
            'duree_minutes' => 'required|integer|min:30|max:180',
            'prix_mensuel' => 'nullable|numeric|min:0',
            'prix_session' => 'nullable|numeric|min:0',
            'instructeur_principal_id' => 'nullable|exists:users,id',
            'instructeur_assistant_id' => 'nullable|exists:users,id|different:instructeur_principal_id',
            'status' => 'required|in:actif,inactif,complet,annule',
            'salle' => 'nullable|string|max:100',
            'materiel_requis' => 'nullable|string',
            'objectifs' => 'nullable|string',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ]);

        // Assigner l'école automatiquement si pas superadmin
        if (!Auth::user()->hasRole('superadmin')) {
            $validated['ecole_id'] = Auth::user()->ecole_id;
        }

        $cours->update($validated);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($cours)
            ->log('Cours modifié');

        return redirect()
            ->route('admin.cours.index')
            ->with('success', 'Cours modifié avec succès');
    }

    public function destroy(Cours $cours)
    {
        // Vérifier s'il y a des inscriptions actives
        $inscriptionsActives = $cours->inscriptions()->where('status', 'active')->count();
        
        if ($inscriptionsActives > 0) {
            return redirect()
                ->back()
                ->with('error', 'Impossible de supprimer un cours avec des inscriptions actives');
        }

        activity()
            ->causedBy(Auth::user())
            ->performedOn($cours)
            ->log('Cours supprimé');

        $cours->delete();

        return redirect()
            ->route('admin.cours.index')
            ->with('success', 'Cours supprimé avec succès');
    }
}
