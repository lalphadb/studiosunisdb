<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PresenceRequest;
use App\Models\Presence;
use App\Models\Cours;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PresenceController extends Controller implements HasMiddleware
{
    /**
     * Middleware Laravel 12.19 avec autorisation selon les Policies
     */
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:viewAny,App\Models\Presence', only: ['index']),
            new Middleware('can:view,presence', only: ['show']),
            new Middleware('can:create,App\Models\Presence', only: ['create', 'store']),
            new Middleware('can:update,presence', only: ['edit', 'update']),
            new Middleware('can:delete,presence', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Presence::with(['user', 'cours.ecole'])->orderBy('date_cours', 'desc');
        
        // Filtre par école pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->whereHas('cours', function($q) {
                $q->where('ecole_id', auth()->user()->ecole_id);
            });
        }
        
        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })->orWhereHas('cours', function($cq) use ($search) {
                    $cq->where('nom', 'like', "%{$search}%");
                });
            });
        }
        
        // Filtre par cours
        if ($request->filled('cours_id')) {
            $query->where('cours_id', $request->cours_id);
        }
        
        // Filtre par date
        if ($request->filled('date_cours')) {
            $query->where('date_cours', $request->date_cours);
        }
        
        // Filtre par présence
        if ($request->has('present')) {
            $query->where('present', $request->boolean('present'));
        }
        
        $presences = $query->paginate(15)->withQueryString();
        $cours = $this->getCoursForUser(auth()->user());
        
        return view('admin.presences.index', compact('presences', 'cours'));
    }

    public function create()
    {
        $cours = $this->getCoursForUser(auth()->user());
        $users = $this->getUsersForPresence();
        
        return view('admin.presences.create', compact('cours', 'users'));
    }

    public function store(PresenceRequest $request)
    {
        $presence = Presence::create($request->validated());
        
        return redirect()->route('admin.presences.index')
            ->with('success', 'Présence enregistrée avec succès.');
    }

    public function show(Presence $presence)
    {
        $presence->load(['user', 'cours.ecole']);
        
        return view('admin.presences.show', compact('presence'));
    }

    public function edit(Presence $presence)
    {
        $cours = $this->getCoursForUser(auth()->user());
        $users = $this->getUsersForPresence();
        
        return view('admin.presences.edit', compact('presence', 'cours', 'users'));
    }

    public function update(PresenceRequest $request, Presence $presence)
    {
        $presence->update($request->validated());
        
        return redirect()->route('admin.presences.index')
            ->with('success', 'Présence mise à jour avec succès.');
    }

    public function destroy(Presence $presence)
    {
        $presence->delete();
        
        return redirect()->route('admin.presences.index')
            ->with('success', 'Présence supprimée avec succès.');
    }

    /**
     * Obtenir les cours selon les permissions
     */
    private function getCoursForUser($user)
    {
        $query = Cours::with('ecole')->orderBy('nom');
        
        if ($user->hasRole('admin_ecole')) {
            $query->where('ecole_id', $user->ecole_id);
        }
        
        return $query->get();
    }

    /**
     * Obtenir les utilisateurs selon les permissions
     */
    private function getUsersForPresence()
    {
        $query = User::with('ecole')->orderBy('name');
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
        
        return $query->get();
    }
}
