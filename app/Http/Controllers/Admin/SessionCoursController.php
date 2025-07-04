<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreSessionCoursRequest;
use App\Http\Requests\Admin\UpdateSessionCoursRequest;
use App\Models\SessionCours;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Contrôleur de gestion des sessions de cours
 * 
 * Migré vers BaseAdminController avec standards Laravel 12
 */
class SessionCoursController extends BaseAdminController
{
    /**
     * Initialise le contrôleur avec permissions
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware('can:viewAny,App\Models\SessionCours')->only(['index']);
        $this->middleware('can:view,sessionCours')->only(['show']);
        $this->middleware('can:create,App\Models\SessionCours')->only(['create', 'store']);
        $this->middleware('can:update,sessionCours')->only(['edit', 'update']);
        $this->middleware('can:delete,sessionCours')->only(['destroy']);
    }

    /**
     * Display a listing of sessions
     */
    public function index(Request $request): View
    {
        return $this->executeWithExceptionHandling(function() use ($request) {
            $query = SessionCours::with('ecole');

            // Multi-tenant filter
            if (!auth()->user()->hasRole('superadmin')) {
                $query->where('ecole_id', auth()->user()->ecole_id);
            }

            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where('nom', 'like', "%{$search}%");
            }

            $sessions = $this->paginateWithParams($query->orderBy('date_debut', 'desc'), $request);

            $this->logBusinessAction('Consultation index sessions', 'info', [
                'total_sessions' => $sessions->total(),
                'filters' => $request->only(['search'])
            ]);

            return view('admin.sessions.index', compact('sessions'));
            
        }, 'consultation sessions');
    }

    /**
     * Show the form for creating a new session
     */
    public function create(): View
    {
        return view('admin.sessions.create');
    }

    /**
     * Store a newly created session
     */
    public function store(StoreSessionCoursRequest $request): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($request) {
            $validated = $request->validated();
            $validated['ecole_id'] = auth()->user()->ecole_id;

            $session = SessionCours::create($validated);

            $this->logCreate('Session', $session->id, $validated);

            return $this->redirectWithSuccess(
                'admin.sessions.index',
                $this->trans('admin.success.created', ['item' => "Session \"{$session->nom}\""])
            );
            
        }, 'création session', ['form_data' => $request->validated()]);
    }

    /**
     * Display the specified session
     */
    public function show(SessionCours $sessionCours): View
    {
        $sessionCours->load(['ecole', 'coursHoraires']);
        return view('admin.sessions.show', compact('sessionCours'));
    }

    /**
     * Show the form for editing the specified session
     */
    public function edit(SessionCours $sessionCours): View
    {
        return view('admin.sessions.edit', compact('sessionCours'));
    }

    /**
     * Update the specified session
     */
    public function update(UpdateSessionCoursRequest $request, SessionCours $sessionCours): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($request, $sessionCours) {
            $validated = $request->validated();
            $oldData = $sessionCours->toArray();

            $sessionCours->update($validated);

            $this->logUpdate('Session', $sessionCours->id, $oldData, $validated);

            return $this->redirectWithSuccess(
                'admin.sessions.index',
                $this->trans('admin.success.updated', ['item' => "Session \"{$sessionCours->nom}\""])
            );
            
        }, 'modification session', ['session_id' => $sessionCours->id]);
    }

    /**
     * Remove the specified session
     */
    public function destroy(SessionCours $sessionCours): RedirectResponse
    {
        return $this->executeWithExceptionHandling(function() use ($sessionCours) {
            $sessionData = [
                'nom' => $sessionCours->nom,
                'ecole_id' => $sessionCours->ecole_id
            ];

            $this->logDelete('Session', $sessionCours->id, $sessionData);

            $sessionCours->delete();

            return $this->redirectWithSuccess(
                'admin.sessions.index',
                $this->trans('admin.success.deleted', ['item' => "Session \"{$sessionData['nom']}\""])
            );
            
        }, 'suppression session', ['session_id' => $sessionCours->id]);
    }
}
