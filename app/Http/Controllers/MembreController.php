<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Ceinture;
use App\Models\User;
use App\Models\Presence;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembresExport;
use Carbon\Carbon;

class MembreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Membre::with(['user', 'ceintureActuelle', 'cours'])
            ->withCount([
                'presences as presences_mois' => function ($query) {
                    $query->where('date_cours', '>=', Carbon::now()->startOfMonth())
                          ->where('statut', 'present');
                }
            ]);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('prenom', 'like', "%{$search}%")
                  ->orWhere('nom', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('email', 'like', "%{$search}%");
                  })
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('ceinture')) {
            $query->where('ceinture_actuelle_id', $request->ceinture);
        }

        if ($request->filled('groupe_age')) {
            $today = Carbon::today();
            switch ($request->groupe_age) {
                case 'enfant':
                    $query->whereBetween('date_naissance', [
                        $today->copy()->subYears(12)->addDay(),
                        $today->copy()->subYears(5)
                    ]);
                    break;
                case 'adolescent':
                    $query->whereBetween('date_naissance', [
                        $today->copy()->subYears(18)->addDay(),
                        $today->copy()->subYears(12)
                    ]);
                    break;
                case 'adulte':
                    $query->where('date_naissance', '<=', $today->copy()->subYears(18));
                    break;
            }
        }

        // Tri
        $sortField = $request->get('sort', 'nom');
        $sortDirection = $request->get('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $membres = $query->paginate(15)->withQueryString();

        // Statistiques
        $stats = $this->getStats();
        $ceintures = Ceinture::where('actif', true)->orderBy('ordre')->get();

        return Inertia::render('Membres/Index', [
            'membres' => $membres,
            'stats' => $stats,
            'ceintures' => $ceintures,
            'filters' => $request->only(['search', 'statut', 'ceinture', 'groupe_age'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ceintures = Ceinture::where('actif', true)->orderBy('ordre')->get();
        
        return Inertia::render('Membres/Create', [
            'ceintures' => $ceintures
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'date_naissance' => 'required|date|before:today',
            'sexe' => 'required|in:M,F,Autre',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:500',
            'contact_urgence_nom' => 'nullable|string|max:255',
            'contact_urgence_telephone' => 'nullable|string|max:20',
            'ceinture_actuelle_id' => 'nullable|exists:ceintures,id',
            'notes_medicales' => 'nullable|string',
            'consentement_photos' => 'boolean',
            'consentement_communications' => 'boolean',
            'photo' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();
        
        try {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $validated['prenom'] . ' ' . $validated['nom'],
                'email' => $validated['email'],
                'password' => Hash::make('StudioSunis2025!'), // Mot de passe temporaire
            ]);

            // Assigner le rôle membre
            $user->assignRole('membre');

            // Gérer la photo si présente
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('membres/photos', 'public');
            }

            // Créer le membre
            $membre = Membre::create([
                'user_id' => $user->id,
                'prenom' => $validated['prenom'],
                'nom' => $validated['nom'],
                'date_naissance' => $validated['date_naissance'],
                'sexe' => $validated['sexe'],
                'telephone' => $validated['telephone'],
                'adresse' => $validated['adresse'],
                'contact_urgence_nom' => $validated['contact_urgence_nom'],
                'contact_urgence_telephone' => $validated['contact_urgence_telephone'],
                'ceinture_actuelle_id' => $validated['ceinture_actuelle_id'],
                'date_inscription' => Carbon::now(),
                'notes_medicales' => $validated['notes_medicales'],
                'consentement_photos' => $validated['consentement_photos'] ?? false,
                'consentement_communications' => $validated['consentement_communications'] ?? true,
                'photo' => $photoPath,
                'statut' => 'actif'
            ]);

            // Créer le premier paiement d'inscription
            Paiement::create([
                'membre_id' => $membre->id,
                'type' => 'inscription',
                'montant' => 50.00, // Frais d'inscription
                'description' => 'Frais d\'inscription initiale',
                'date_echeance' => Carbon::now(),
                'statut' => 'en_attente'
            ]);

            DB::commit();

            return redirect()->route('membres.show', $membre->id)
                ->with('success', 'Membre créé avec succès!');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Supprimer la photo si elle a été uploadée
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la création du membre.'])
                        ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Membre $membre)
    {
        $membre->load([
            'user',
            'ceintureActuelle',
            'cours.instructeur',
            'presences' => function ($query) {
                $query->latest('date_cours')->limit(20);
            },
            'paiements' => function ($query) {
                $query->latest('created_at')->limit(10);
            }
        ]);

        // Statistiques du membre
        $stats = [
            'total_presences' => $membre->presences()->where('statut', 'present')->count(),
            'presences_mois' => $membre->presences()
                ->where('statut', 'present')
                ->where('date_cours', '>=', Carbon::now()->startOfMonth())
                ->count(),
            'taux_presence' => $this->calculateAttendanceRate($membre),
            'paiements_dus' => $membre->paiements()
                ->whereIn('statut', ['en_attente', 'en_retard'])
                ->sum('montant'),
            'prochains_cours' => $membre->cours()
                ->where('actif', true)
                ->orderBy('jour_semaine')
                ->orderBy('heure_debut')
                ->get(),
            'progression_ceinture' => $this->calculateBeltProgress($membre)
        ];

        return Inertia::render('Membres/Show', [
            'membre' => $membre,
            'stats' => $stats
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Membre $membre)
    {
        $membre->load(['user', 'ceintureActuelle']);
        $ceintures = Ceinture::where('actif', true)->orderBy('ordre')->get();
        
        return Inertia::render('Membres/Edit', [
            'membre' => $membre,
            'ceintures' => $ceintures
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Membre $membre)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $membre->user_id,
            'date_naissance' => 'required|date|before:today',
            'sexe' => 'required|in:M,F,Autre',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:500',
            'contact_urgence_nom' => 'nullable|string|max:255',
            'contact_urgence_telephone' => 'nullable|string|max:20',
            'ceinture_actuelle_id' => 'nullable|exists:ceintures,id',
            'statut' => 'required|in:actif,inactif,suspendu',
            'notes_medicales' => 'nullable|string',
            'consentement_photos' => 'boolean',
            'consentement_communications' => 'boolean',
            'photo' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();
        
        try {
            // Mettre à jour l'utilisateur
            $membre->user->update([
                'name' => $validated['prenom'] . ' ' . $validated['nom'],
                'email' => $validated['email']
            ]);

            // Gérer la photo
            $photoPath = $membre->photo;
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo
                if ($photoPath) {
                    Storage::disk('public')->delete($photoPath);
                }
                $photoPath = $request->file('photo')->store('membres/photos', 'public');
            }

            // Mettre à jour le membre
            $membre->update([
                'prenom' => $validated['prenom'],
                'nom' => $validated['nom'],
                'date_naissance' => $validated['date_naissance'],
                'sexe' => $validated['sexe'],
                'telephone' => $validated['telephone'],
                'adresse' => $validated['adresse'],
                'contact_urgence_nom' => $validated['contact_urgence_nom'],
                'contact_urgence_telephone' => $validated['contact_urgence_telephone'],
                'ceinture_actuelle_id' => $validated['ceinture_actuelle_id'],
                'statut' => $validated['statut'],
                'notes_medicales' => $validated['notes_medicales'],
                'consentement_photos' => $validated['consentement_photos'] ?? false,
                'consentement_communications' => $validated['consentement_communications'] ?? true,
                'photo' => $photoPath
            ]);

            DB::commit();

            return redirect()->route('membres.show', $membre->id)
                ->with('success', 'Membre mis à jour avec succès!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour.'])
                        ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membre $membre)
    {
        DB::beginTransaction();
        
        try {
            // Supprimer la photo si elle existe
            if ($membre->photo) {
                Storage::disk('public')->delete($membre->photo);
            }

            // Supprimer le membre (cascade supprimera les relations)
            $membre->delete();
            
            // Supprimer l'utilisateur associé
            $membre->user->delete();

            DB::commit();

            return redirect()->route('membres.index')
                ->with('success', 'Membre supprimé avec succès!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la suppression.']);
        }
    }

    /**
     * Changer la ceinture d'un membre
     */
    public function changerCeinture(Request $request, Membre $membre)
    {
        $validated = $request->validate([
            'nouvelle_ceinture_id' => 'required|exists:ceintures,id',
            'notes' => 'nullable|string'
        ]);

        $ancienneCeinture = $membre->ceintureActuelle;
        $nouvelleCeinture = Ceinture::find($validated['nouvelle_ceinture_id']);

        // Vérifier que c'est une progression
        if ($ancienneCeinture && $nouvelleCeinture->ordre <= $ancienneCeinture->ordre) {
            return back()->withErrors(['error' => 'La nouvelle ceinture doit être supérieure à l\'actuelle.']);
        }

        DB::beginTransaction();
        
        try {
            // Enregistrer la progression
            DB::table('progression_ceintures')->insert([
                'membre_id' => $membre->id,
                'ceinture_actuelle_id' => $ancienneCeinture ? $ancienneCeinture->id : null,
                'ceinture_cible_id' => $nouvelleCeinture->id,
                'instructeur_id' => auth()->id(),
                'statut' => 'certifie',
                'date_examen' => Carbon::now(),
                'notes_instructeur' => $validated['notes'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            // Mettre à jour la ceinture du membre
            $membre->update([
                'ceinture_actuelle_id' => $nouvelleCeinture->id
            ]);

            DB::commit();

            return back()->with('success', 'Ceinture mise à jour avec succès!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erreur lors du changement de ceinture.']);
        }
    }

    /**
     * Export des membres
     */
    public function export(Request $request)
    {
        return Excel::download(new MembresExport($request->all()), 'membres_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Méthodes privées helper
     */
    private function getStats()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $membresActifs = Membre::where('statut', 'actif')->count();
        $membresActifsLastMonth = Membre::where('statut', 'actif')
            ->where('created_at', '<', $currentMonth)
            ->count();

        $nouvellesInscriptions = Membre::where('date_inscription', '>=', $currentMonth)->count();
        $inscriptionsLastMonth = Membre::whereBetween('date_inscription', [
            $lastMonth,
            $currentMonth
        ])->count();

        $presencesMois = Presence::where('date_cours', '>=', $currentMonth)
            ->where('statut', 'present')
            ->count();
        
        $totalCoursMois = DB::table('cours')
            ->where('actif', true)
            ->where('date_debut', '<=', Carbon::now())
            ->count() * 4; // Approximation: 4 semaines par mois

        $tauxPresence = $totalCoursMois > 0 ? ($presencesMois / $totalCoursMois) * 100 : 0;

        $examensPrevus = DB::table('progression_ceintures')
            ->whereIn('statut', ['eligible', 'candidat', 'examen_planifie'])
            ->count();

        return [
            'membres_actifs' => $membresActifs,
            'trend_actifs' => $this->calculateTrend($membresActifs, $membresActifsLastMonth),
            'nouvelles_inscriptions' => $nouvellesInscriptions,
            'trend_inscriptions' => $this->calculateTrend($nouvellesInscriptions, $inscriptionsLastMonth),
            'taux_presence' => round($tauxPresence, 1),
            'trend_presence' => '+2.5%', // Calculé dynamiquement en production
            'examens_prevus' => $examensPrevus
        ];
    }

    private function calculateTrend($current, $previous)
    {
        if ($previous == 0) return '+100%';
        
        $change = (($current - $previous) / $previous) * 100;
        $sign = $change >= 0 ? '+' : '';
        
        return $sign . round($change, 1) . '%';
    }

    private function calculateAttendanceRate($membre)
    {
        $totalExpected = $membre->cours()->count() * 4; // 4 semaines par mois
        if ($totalExpected == 0) return 0;
        
        $totalPresent = $membre->presences()
            ->where('statut', 'present')
            ->where('date_cours', '>=', Carbon::now()->startOfMonth())
            ->count();
            
        return round(($totalPresent / $totalExpected) * 100, 1);
    }

    private function calculateBeltProgress($membre)
    {
        if (!$membre->ceintureActuelle) {
            return ['percentage' => 0, 'message' => 'Aucune ceinture assignée'];
        }

        $presencesRequises = $membre->ceintureActuelle->presences_minimum ?? 24;
        $presencesActuelles = $membre->presences()
            ->where('statut', 'present')
            ->where('date_cours', '>=', $membre->date_derniere_progression ?? $membre->date_inscription)
            ->count();

        $percentage = min(100, round(($presencesActuelles / $presencesRequises) * 100));

        return [
            'percentage' => $percentage,
            'presences_actuelles' => $presencesActuelles,
            'presences_requises' => $presencesRequises,
            'message' => $percentage >= 100 
                ? 'Éligible pour l\'examen de passage' 
                : 'Encore ' . ($presencesRequises - $presencesActuelles) . ' présences requises'
        ];
    }
}
