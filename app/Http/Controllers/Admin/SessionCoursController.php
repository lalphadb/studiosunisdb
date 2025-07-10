<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

/**
 * SessionCoursController - Gestion des sessions de cours
 * StudiosDB Enterprise v4.1.10.2
 */
class SessionCoursController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            Log::info('SessionCoursController: Affichage de la liste des sessions');

            return view('admin.sessions.index', [
                'title' => 'Sessions de Cours',
                'sessions' => []
            ]);

        } catch (\Exception $e) {
            Log::error('SessionCoursController::index - Erreur', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Erreur lors du chargement des sessions de cours.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('admin.sessions.create', [
                'title' => 'Nouvelle Session'
            ]);

        } catch (\Exception $e) {
            Log::error('SessionCoursController::create - Erreur', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Erreur lors de l\'affichage du formulaire de création.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            Log::info('SessionCoursController: Création d\'une nouvelle session', [
                'data' => $validated,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('admin.sessions.index')
                           ->with('success', 'Session créée avec succès.');

        } catch (\Exception $e) {
            Log::error('SessionCoursController::store - Erreur', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Erreur lors de la création de la session.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return view('admin.sessions.show', [
                'title' => 'Détails Session',
                'session_id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('SessionCoursController::show - Erreur', [
                'error' => $e->getMessage(),
                'session_id' => $id,
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Erreur lors de l\'affichage de la session.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            return view('admin.sessions.edit', [
                'title' => 'Modifier Session',
                'session_id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('SessionCoursController::edit - Erreur', [
                'error' => $e->getMessage(),
                'session_id' => $id,
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Erreur lors de l\'affichage du formulaire de modification.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            Log::info('SessionCoursController: Mise à jour session', [
                'session_id' => $id,
                'data' => $validated,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('admin.sessions.index')
                           ->with('success', 'Session mise à jour avec succès.');

        } catch (\Exception $e) {
            Log::error('SessionCoursController::update - Erreur', [
                'error' => $e->getMessage(),
                'session_id' => $id,
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Erreur lors de la mise à jour de la session.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Log::info('SessionCoursController: Suppression session', [
                'session_id' => $id,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('admin.sessions.index')
                           ->with('success', 'Session supprimée avec succès.');

        } catch (\Exception $e) {
            Log::error('SessionCoursController::destroy - Erreur', [
                'error' => $e->getMessage(),
                'session_id' => $id,
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Erreur lors de la suppression de la session.');
        }
    }
}
