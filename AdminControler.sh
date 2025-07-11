#!/bin/bash

echo "=== CRÉATION DES CONTRÔLEURS ADMIN StudiosDB ==="

# Créer le dossier Admin s'il n'existe pas
mkdir -p app/Http/Controllers/Admin

# 1. UserController
echo "Creating UserController..."
php artisan make:controller Admin/UserController --resource
cat > app/Http/Controllers/Admin/UserController.php << 'EOF'
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin', 'ecole.restriction']);
    }

    public function index()
    {
        $users = User::with(['ecole', 'roles'])
            ->when(!auth()->user()->hasRole('super-admin'), function ($query) {
                $query->where('ecole_id', session('ecole_id'));
            })
            ->orderBy('nom')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $ecoles = auth()->user()->hasRole('super-admin') 
            ? Ecole::all() 
            : Ecole::where('id', session('ecole_id'))->get();
            
        return view('admin.users.create', compact('ecoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'ecole_id' => 'required|exists:ecoles,id',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['code_utilisateur'] = $this->generateUserCode();
        
        $user = User::create($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        $this->authorizeAccess($user);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorizeAccess($user);
        
        $ecoles = auth()->user()->hasRole('super-admin') 
            ? Ecole::all() 
            : Ecole::where('id', session('ecole_id'))->get();
            
        return view('admin.users.edit', compact('user', 'ecoles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAccess($user);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'ecole_id' => 'required|exists:ecoles,id',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'actif' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        $this->authorizeAccess($user);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    private function authorizeAccess(User $user)
    {
        if (!auth()->user()->hasRole('super-admin') && $user->ecole_id !== session('ecole_id')) {
            abort(403, 'Accès non autorisé à cet utilisateur.');
        }
    }

    private function generateUserCode()
    {
        $lastUser = User::orderBy('id', 'desc')->first();
        $number = $lastUser ? intval(substr($lastUser->code_utilisateur, 1)) + 1 : 1;
        return 'U' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
EOF

# 2. CoursController
echo "Creating CoursController..."
php artisan make:controller Admin/CoursController --resource
cat > app/Http/Controllers/Admin/CoursController.php << 'EOF'
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Ecole;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin', 'ecole.restriction']);
    }

    public function index()
    {
        $cours = Cours::with(['ecole', 'horaires'])
            ->when(!auth()->user()->hasRole('super-admin'), function ($query) {
                $query->where('ecole_id', session('ecole_id'));
            })
            ->orderBy('nom')
            ->paginate(20);

        return view('admin.cours.index', compact('cours'));
    }

    public function create()
    {
        $ecoles = auth()->user()->hasRole('super-admin') 
            ? Ecole::all() 
            : Ecole::where('id', session('ecole_id'))->get();
            
        return view('admin.cours.create', compact('ecoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|text',
            'ecole_id' => 'required|exists:ecoles,id',
            'type' => 'required|in:regulier,special,seminaire',
            'prix_mensuel' => 'nullable|numeric|min:0',
            'prix_seance' => 'nullable|numeric|min:0',
            'duree_minutes' => 'required|integer|min:30',
            'actif' => 'boolean',
        ]);

        $cours = Cours::create($validated);
        
        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours créé avec succès.');
    }

    public function show(Cours $cour)
    {
        $this->authorizeAccess($cour);
        $cour->load(['ecole', 'horaires', 'inscriptions.user']);
        
        return view('admin.cours.show', compact('cour'));
    }

    public function edit(Cours $cour)
    {
        $this->authorizeAccess($cour);
        
        $ecoles = auth()->user()->hasRole('super-admin') 
            ? Ecole::all() 
            : Ecole::where('id', session('ecole_id'))->get();
            
        return view('admin.cours.edit', compact('cour', 'ecoles'));
    }

    public function update(Request $request, Cours $cour)
    {
        $this->authorizeAccess($cour);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|text',
            'ecole_id' => 'required|exists:ecoles,id',
            'type' => 'required|in:regulier,special,seminaire',
            'prix_mensuel' => 'nullable|numeric|min:0',
            'prix_seance' => 'nullable|numeric|min:0',
            'duree_minutes' => 'required|integer|min:30',
            'actif' => 'boolean',
        ]);

        $cour->update($validated);
        
        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours mis à jour avec succès.');
    }

    public function destroy(Cours $cour)
    {
        $this->authorizeAccess($cour);
        
        if ($cour->inscriptions()->exists()) {
            return back()->with('error', 'Impossible de supprimer un cours avec des inscriptions.');
        }

        $cour->delete();
        
        return redirect()->route('admin.cours.index')
            ->with('success', 'Cours supprimé avec succès.');
    }

    private function authorizeAccess(Cours $cours)
    {
        if (!auth()->user()->hasRole('super-admin') && $cours->ecole_id !== session('ecole_id')) {
            abort(403, 'Accès non autorisé à ce cours.');
        }
    }
}
EOF

# 3. EcoleController (super-admin only)
echo "Creating EcoleController..."
php artisan make:controller Admin/EcoleController --resource
cat > app/Http/Controllers/Admin/EcoleController.php << 'EOF'
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use Illuminate\Http\Request;

class EcoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
        $this->middleware('role:super-admin');
    }

    public function index()
    {
        $ecoles = Ecole::withCount(['users', 'cours'])
            ->orderBy('nom')
            ->paginate(20);

        return view('admin.ecoles.index', compact('ecoles'));
    }

    public function create()
    {
        return view('admin.ecoles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:ecoles',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:10',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'actif' => 'boolean',
        ]);

        $ecole = Ecole::create($validated);
        
        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École créée avec succès.');
    }

    public function show(Ecole $ecole)
    {
        $ecole->load(['users' => function ($query) {
            $query->with('roles')->orderBy('nom');
        }, 'cours']);
        
        return view('admin.ecoles.show', compact('ecole'));
    }

    public function edit(Ecole $ecole)
    {
        return view('admin.ecoles.edit', compact('ecole'));
    }

    public function update(Request $request, Ecole $ecole)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:ecoles,code,' . $ecole->id,
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:10',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'actif' => 'boolean',
        ]);

        $ecole->update($validated);
        
        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École mise à jour avec succès.');
    }

    public function destroy(Ecole $ecole)
    {
        if ($ecole->users()->exists() || $ecole->cours()->exists()) {
            return back()->with('error', 'Impossible de supprimer une école avec des données associées.');
        }

        $ecole->delete();
        
        return redirect()->route('admin.ecoles.index')
            ->with('success', 'École supprimée avec succès.');
    }
}
EOF

# 4. PresenceController
echo "Creating PresenceController..."
php artisan make:controller Admin/PresenceController
cat > app/Http/Controllers/Admin/PresenceController.php << 'EOF'
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SessionCours;
use App\Models\Presence;
use App\Models\Cours;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin', 'ecole.restriction']);
    }

    public function index(Request $request)
    {
        $cours = Cours::when(!auth()->user()->hasRole('super-admin'), function ($query) {
                $query->where('ecole_id', session('ecole_id'));
            })
            ->orderBy('nom')
            ->get();

        $sessions = SessionCours::with(['cours', 'presences.user'])
            ->when($request->filled('cours_id'), function ($query) use ($request) {
                $query->where('cours_id', $request->cours_id);
            })
            ->when(!auth()->user()->hasRole('super-admin'), function ($query) {
                $query->whereHas('cours', function ($q) {
                    $q->where('ecole_id', session('ecole_id'));
                });
            })
            ->orderBy('date', 'desc')
            ->orderBy('heure_debut', 'desc')
            ->paginate(20);

        return view('admin.presences.index', compact('sessions', 'cours'));
    }

    public function create(Request $request)
    {
        $cours = Cours::with('inscriptions.user')
            ->when(!auth()->user()->hasRole('super-admin'), function ($query) {
                $query->where('ecole_id', session('ecole_id'));
            })
            ->findOrFail($request->cours_id);

        return view('admin.presences.create', compact('cours'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required|after:heure_debut',
            'presences' => 'required|array',
            'presences.*' => 'in:present,absent,retard',
        ]);

        // Créer la session de cours
        $session = SessionCours::create([
            'cours_id' => $validated['cours_id'],
            'date' => $validated['date'],
            'heure_debut' => $validated['heure_debut'],
            'heure_fin' => $validated['heure_fin'],
        ]);

        // Enregistrer les présences
        foreach ($validated['presences'] as $userId => $status) {
            if ($status !== null) {
                Presence::create([
                    'session_cours_id' => $session->id,
                    'user_id' => $userId,
                    'status' => $status,
                ]);
            }
        }

        return redirect()->route('admin.presences.index')
            ->with('success', 'Présences enregistrées avec succès.');
    }

    public function edit(SessionCours $session)
    {
        $this->authorizeAccess($session);
        
        $session->load(['cours.inscriptions.user', 'presences']);
        
        return view('admin.presences.edit', compact('session'));
    }

    public function update(Request $request, SessionCours $session)
    {
        $this->authorizeAccess($session);
        
        $validated = $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required|after:heure_debut',
            'presences' => 'required|array',
            'presences.*' => 'in:present,absent,retard',
        ]);

        $session->update([
            'date' => $validated['date'],
            'heure_debut' => $validated['heure_debut'],
            'heure_fin' => $validated['heure_fin'],
        ]);

        // Mettre à jour les présences
        $session->presences()->delete();
        
        foreach ($validated['presences'] as $userId => $status) {
            if ($status !== null) {
                Presence::create([
                    'session_cours_id' => $session->id,
                    'user_id' => $userId,
                    'status' => $status,
                ]);
            }
        }

        return redirect()->route('admin.presences.index')
            ->with('success', 'Présences mises à jour avec succès.');
    }

    private function authorizeAccess(SessionCours $session)
    {
        if (!auth()->user()->hasRole('super-admin') && $session->cours->ecole_id !== session('ecole_id')) {
            abort(403, 'Accès non autorisé à cette session.');
        }
    }
}
EOF

echo -e "\n✓ Tous les contrôleurs admin de base ont été créés!"
echo -e "\nContrôleurs créés:"
echo "  - Admin/UserController (CRUD complet)"
echo "  - Admin/CoursController (CRUD complet)"
echo "  - Admin/EcoleController (CRUD complet - super-admin uniquement)"
echo "  - Admin/PresenceController (Gestion des présences)"
echo -e "\nCommit ces changements:"
echo "git add app/Http/Controllers/Admin/"
echo "git commit -m \"feat: ajout des contrôleurs admin de base\""
