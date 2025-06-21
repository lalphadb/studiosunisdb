<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\User;
use App\Models\Presence;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:view-presences', only: ['index', 'show']),
            new Middleware('can:create-presence', only: ['create', 'store']),
            new Middleware('can:edit-presence', only: ['edit', 'update']),
            new Middleware('can:delete-presence', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Presence::with(['cours', 'user']); // Changé membre -> user

        // Restriction par école selon le rôle
        if (! $user->hasRole('superadmin')) {
            $query->whereHas('cours', function ($q) use ($user) {
                $q->where('ecole_id', $user->ecole_id);
            });
        }

        // Filtres
        if ($request->filled('cours_id')) {
            $query->where('cours_id', $request->cours_id);
        }

        if ($request->filled('date_debut')) {
            $query->where('date_cours', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date_cours', '<=', $request->date_fin);
        }

        if ($request->filled('present')) {
            $query->where('present', $request->boolean('present'));
        }

        $presences = $query->orderBy('date_cours', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        // Données pour les filtres
        $coursQuery = Cours::query();
        if (! $user->hasRole('superadmin')) {
            $coursQuery->where('ecole_id', $user->ecole_id);
        }
        $cours = $coursQuery->get();

        return view('admin.presences.index', compact('presences', 'cours'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        // Cours disponibles selon l'école
        $coursQuery = Cours::with('ecole');
        if (! $user->hasRole('superadmin')) {
            $coursQuery->where('ecole_id', $user->ecole_id);
        }
        $cours = $coursQuery->get();

        // Membres (utilisateurs avec rôle membre) disponibles selon l'école
        $membresQuery = User::membres(); // Scope pour les membres
        if (! $user->hasRole('superadmin')) {
            $membresQuery->where('ecole_id', $user->ecole_id);
        }
        $membres = $membresQuery->get();

        $selectedCours = $request->get('cours_id');

        return view('admin.presences.create', compact('cours', 'membres', 'selectedCours'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'user_id' => 'required|exists:users,id', // Changé membre_id -> user_id
            'date_cours' => 'required|date',
            'present' => 'required|boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        // Vérification que le cours et le membre appartiennent à la même école
        $cours = Cours::findOrFail($request->cours_id);
        $membre = User::findOrFail($request->user_id);

        if ($cours->ecole_id !== $membre->ecole_id) {
            return back()->withErrors(['error' => 'Le cours et le membre doivent appartenir à la même école.']);
        }

        // Vérification des permissions d'école
        $user = Auth::user();
        if (! $user->hasRole('superadmin') && $cours->ecole_id !== $user->ecole_id) {
            abort(403, 'Accès non autorisé à cette école.');
        }

        // Vérification unicité (un membre ne peut avoir qu'une présence par cours par date)
        $existingPresence = Presence::where([
            'cours_id' => $request->cours_id,
            'user_id' => $request->user_id, // Changé membre_id -> user_id
            'date_cours' => $request->date_cours,
        ])->first();

        if ($existingPresence) {
            return back()->withErrors(['error' => 'Une présence existe déjà pour ce membre à ce cours à cette date.']);
        }

        Presence::create($request->all());

        return redirect()->route('admin.presences.index')
            ->with('success', 'Présence enregistrée avec succès.');
    }

    // Autres méthodes à adapter de la même façon...
    // Pour gagner du temps, je montre les corrections principales
}
