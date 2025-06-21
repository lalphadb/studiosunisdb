<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use App\Models\Ceinture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
            new \Illuminate\Routing\Middleware\Middleware('can:view-membres', only: ['index', 'show']),
            new \Illuminate\Routing\Middleware\Middleware('can:create-membre', only: ['create', 'store']),
            new \Illuminate\Routing\Middleware\Middleware('can:edit-membre', only: ['edit', 'update']),
            new \Illuminate\Routing\Middleware\Middleware('can:delete-membre', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        // Base query
        $query = User::with(['ecole', 'roles', 'ceinture_actuelle']);

        // Filtrage par école pour non-superadmin
        if (!auth()->user()->hasRole('superadmin') && auth()->user()->ecole_id) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }

        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('ecole_id')) {
            $query->where('ecole_id', $request->ecole_id);
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Pagination
        $membres = $query->orderBy('name')->paginate(15);

        // Statistiques
        $stats = $this->getStats();

        // Écoles pour le filtre (SuperAdmin uniquement)
        $ecoles = [];
        if (auth()->user()->hasRole('superadmin')) {
            $ecoles = Ecole::orderBy('nom')->get();
        }

        return view('admin.membres.index', compact('membres', 'stats', 'ecoles'));
    }

    public function create()
    {
        $ecoles = $this->getEcolesForUser();
        $ceintures = Ceinture::orderBy('ordre')->get();
        
        return view('admin.membres.create', compact('ecoles', 'ceintures'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date|before:today',
            'sexe' => 'nullable|in:M,F,Autre',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'province' => 'nullable|string|max:255',
            'ecole_id' => 'required|exists:ecoles,id',
            'ceinture_actuelle_id' => 'nullable|exists:ceintures,id',
            'role' => 'required|in:membre,instructeur,admin',
            'notes' => 'nullable|string'
        ]);

        // Vérification des permissions pour l'école
        if (!$this->canManageEcole($validated['ecole_id'])) {
            abort(403, 'Non autorisé à gérer cette école');
        }

        DB::beginTransaction();
        try {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'telephone' => $validated['telephone'],
                'date_naissance' => $validated['date_naissance'],
                'sexe' => $validated['sexe'],
                'adresse' => $validated['adresse'],
                'ville' => $validated['ville'],
                'code_postal' => $validated['code_postal'],
                'province' => $validated['province'],
                'ecole_id' => $validated['ecole_id'],
                'ceinture_actuelle_id' => $validated['ceinture_actuelle_id'] ?? 1, // Blanche par défaut
                'notes' => $validated['notes'],
                'email_verified_at' => now()
            ]);

            // Assigner le rôle
            $user->assignRole($validated['role']);

            // Si une ceinture est spécifiée, créer l'historique
            if ($validated['ceinture_actuelle_id']) {
                DB::table('membre_ceintures')->insert([
                    'user_id' => $user->id,
                    'ceinture_id' => $validated['ceinture_actuelle_id'],
                    'date_obtention' => now(),
                    'instructeur_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            
            return redirect()->route('admin.membres.index')
                           ->with('success', "Membre {$user->name} créé avec succès");
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->withErrors(['error' => 'Erreur lors de la création: ' . $e->getMessage()]);
        }
    }

    public function show(User $membre)
    {
        if (!$this->canManageEcole($membre->ecole_id)) {
            abort(403);
        }

        $membre->load([
            'ecole', 
            'roles', 
            'ceinture_actuelle',
            'membre_ceintures.ceinture',
            'membre_ceintures.instructeur',
            'presences.cours',
            'inscriptions_cours.cours',
            'paiements'
        ]);

        // Statistiques du membre
        $stats = [
            'total_presences' => $membre->presences()->count(),
            'presences_ce_mois' => $membre->presences()->whereMonth('date', now()->month)->count(),
            'cours_inscrits' => $membre->inscriptions_cours()->count(),
            'derniere_presence' => $membre->presences()->latest('date')->first(),
            'total_paiements' => $membre->paiements()->sum('montant')
        ];

        return view('admin.membres.show', compact('membre', 'stats'));
    }

    public function edit(User $membre)
    {
        if (!$this->canManageEcole($membre->ecole_id)) {
            abort(403);
        }

        $ecoles = $this->getEcolesForUser();
        $ceintures = Ceinture::orderBy('ordre')->get();
        
        return view('admin.membres.edit', compact('membre', 'ecoles', 'ceintures'));
    }

    public function update(Request $request, User $membre)
    {
        if (!$this->canManageEcole($membre->ecole_id)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $membre->id,
            'password' => 'nullable|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date|before:today',
            'sexe' => 'nullable|in:M,F,Autre',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'province' => 'nullable|string|max:255',
            'ecole_id' => 'required|exists:ecoles,id',
            'ceinture_actuelle_id' => 'nullable|exists:ceintures,id',
            'role' => 'required|in:membre,instructeur,admin',
            'notes' => 'nullable|string'
        ]);

        if (!$this->canManageEcole($validated['ecole_id'])) {
            abort(403, 'Non autorisé à gérer cette école');
        }

        DB::beginTransaction();
        try {
            // Mettre à jour les données
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'],
                'date_naissance' => $validated['date_naissance'],
                'sexe' => $validated['sexe'],
                'adresse' => $validated['adresse'],
                'ville' => $validated['ville'],
                'code_postal' => $validated['code_postal'],
                'province' => $validated['province'],
                'ecole_id' => $validated['ecole_id'],
                'notes' => $validated['notes']
            ];

            // Mot de passe optionnel
            if ($validated['password']) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            // Ceinture changée ?
            if ($validated['ceinture_actuelle_id'] && $validated['ceinture_actuelle_id'] != $membre->ceinture_actuelle_id) {
                $updateData['ceinture_actuelle_id'] = $validated['ceinture_actuelle_id'];
                
                // Ajouter à l'historique
                DB::table('membre_ceintures')->insert([
                    'user_id' => $membre->id,
                    'ceinture_id' => $validated['ceinture_actuelle_id'],
                    'date_obtention' => now(),
                    'instructeur_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            $membre->update($updateData);

            // Mettre à jour le rôle
            $membre->syncRoles([$validated['role']]);

            DB::commit();
            
            return redirect()->route('admin.membres.show', $membre)
                           ->with('success', "Membre {$membre->name} mis à jour avec succès");
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->withErrors(['error' => 'Erreur lors de la mise à jour: ' . $e->getMessage()]);
        }
    }

    public function destroy(User $membre)
    {
        if (!$this->canManageEcole($membre->ecole_id) || $membre->id === auth()->id()) {
            abort(403);
        }

        $nom = $membre->name;
        $membre->delete();

        return redirect()->route('admin.membres.index')
                        ->with('success', "Membre {$nom} supprimé avec succès");
    }

    public function export(Request $request)
    {
        // TODO: Implémenter l'export Excel
        return redirect()->route('admin.membres.index')
                        ->with('info', 'Fonctionnalité d\'export en cours de développement');
    }

    public function qrcode(User $membre)
    {
        if (!$this->canManageEcole($membre->ecole_id)) {
            abort(403);
        }

        // TODO: Générer QR Code
        return view('admin.membres.qrcode', compact('membre'));
    }

    /**
     * Obtenir les statistiques des membres
     */
    private function getStats()
    {
        $query = User::query();
        
        // Filtrer par école si pas superadmin
        if (!auth()->user()->hasRole('superadmin') && auth()->user()->ecole_id) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }

        $total = $query->count();
        $instructeurs = $query->whereHas('roles', fn($q) => $q->where('name', 'instructeur'))->count();
        $nouveaux = $query->whereMonth('created_at', now()->month)->count();
        
        // Taux de présence approximatif (à améliorer)
        $presence_rate = 75.5; // TODO: Calculer vraiment

        return compact('total', 'instructeurs', 'nouveaux', 'presence_rate');
    }

    /**
     * Obtenir les écoles que l'utilisateur peut gérer
     */
    private function getEcolesForUser()
    {
        if (auth()->user()->hasRole('superadmin')) {
            return Ecole::orderBy('nom')->get();
        }
        
        return Ecole::where('id', auth()->user()->ecole_id)->get();
    }

    /**
     * Vérifier si l'utilisateur peut gérer cette école
     */
    private function canManageEcole($ecole_id)
    {
        if (auth()->user()->hasRole('superadmin')) {
            return true;
        }
        
        return auth()->user()->ecole_id == $ecole_id;
    }
}
