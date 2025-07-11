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
