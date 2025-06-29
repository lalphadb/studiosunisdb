<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PaiementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['auth'];
    }

    public function index()
    {
        $paiements = Paiement::with('user')->latest()->paginate(15);
        return view('admin.paiements.index', compact('paiements'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.paiements.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'montant' => 'required|numeric|min:0.01',
        ]);

        $user = User::find($request->user_id);
        
        Paiement::create([
            'user_id' => $request->user_id,
            'montant' => $request->montant,
            'montant_net' => $request->montant,
            'statut' => 'attente', // Valeur plus courte
            'methode_paiement' => $request->methode_paiement ?: 'virement',
            'notes' => $request->notes,
            'reference_interne' => 'PAY-' . date('Ymd') . '-' . rand(1000, 9999),
            'ecole_id' => $user->ecole_id,
            'frais' => 0,
        ]);

        return redirect()->route('admin.paiements.index')
                        ->with('success', 'Paiement créé avec succès !');
    }

    public function show(Paiement $paiement)
    {
        $paiement->load('user');
        return view('admin.paiements.show', compact('paiement'));
    }

    public function edit(Paiement $paiement)
    {
        $users = User::orderBy('name')->get();
        return view('admin.paiements.edit', compact('paiement', 'users'));
    }

    public function update(Request $request, Paiement $paiement)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'montant' => 'required|numeric|min:0.01',
        ]);

        $user = User::find($request->user_id);

        $paiement->update([
            'user_id' => $request->user_id,
            'montant' => $request->montant,
            'montant_net' => $request->montant,
            'statut' => $request->statut ?: 'attente',
            'methode_paiement' => $request->methode_paiement,
            'notes' => $request->notes,
            'reference_externe' => $request->reference_externe,
            'ecole_id' => $user->ecole_id,
        ]);

        return redirect()->route('admin.paiements.index')
                        ->with('success', 'Paiement mis à jour !');
    }

    public function destroy(Paiement $paiement)
    {
        $paiement->delete();
        return redirect()->route('admin.paiements.index')
                        ->with('success', 'Paiement supprimé !');
    }

    // =====================================
    // ACTIONS DE MASSE
    // =====================================

    public function actionsMasse()
    {
        return view('admin.paiements.actions-masse');
    }

    public function traiterActionsMasse(Request $request)
    {
        return redirect()->route('admin.paiements.index')
                        ->with('success', 'Actions de masse traitées !');
    }

    public function validationRapide()
    {
        return view('admin.paiements.validation-rapide');
    }

    /**
     * Marquer un paiement comme reçu
     */
    public function marquerRecu(Request $request, Paiement $paiement)
    {
        $request->validate([
            'reference_externe' => 'nullable|string|max:255',
        ]);

        // Utiliser des valeurs de statut plus courtes
        $paiement->update([
            'statut' => 'paye', // Valeur courte
            'reference_externe' => $request->reference_externe,
            'date_paiement' => now(),
        ]);

        return redirect()->back()->with('success', 'Paiement marqué comme reçu !');
    }
}
