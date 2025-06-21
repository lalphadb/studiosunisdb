<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InscriptionSeminaire;
use App\Models\Membre;
use App\Models\Seminaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InscriptionSeminaireController extends Controller
{
    public function index(Seminaire $seminaire)
    {
        $inscriptions = $seminaire->inscriptions()
            ->with(['membre', 'ecole'])
            ->orderBy('date_inscription', 'desc')
            ->paginate(20);

        return view('admin.seminaires.inscriptions', compact('seminaire', 'inscriptions'));
    }

    public function create(Seminaire $seminaire)
    {
        $user = Auth::user();

        $membres = User::with('ecole')
            ->where('active", true')
            ->when(! $user->hasRole('superadmin'), function ($query) use ($user) {
                return $query->where('ecole_id', $user->ecole_id);
            })
            ->orderBy('nom')
            ->get();

        return view('admin.seminaires.inscrire', compact('seminaire', 'membres'));
    }

    public function store(Request $request, Seminaire $seminaire)
    {
        // Validation
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $membre = User::findOrFail($request->user_id);

        // Vérifier si déjà inscrit
        $existe = InscriptionSeminaire::where('seminaire_id', $seminaire->id)
            ->where('user_id', $membre->id)
            ->exists();

        if ($existe) {
            return back()->with('error', 'Ce membre est déjà inscrit à ce séminaire.');
        }

        // Créer inscription
        InscriptionSeminaire::create([
            'seminaire_id' => $seminaire->id,
            'user_id' => $membre->id,
            'ecole_id' => $membre->ecole_id,
            'date_inscription' => now()->format('Y-m-d'),
            'statut' => 'inscrit',
            'montant_paye' => $request->montant_paye ?? 0,
            'notes_participant' => $request->notes_participant,
        ]);

        return redirect()->route('admin.seminaires.inscriptions', $seminaire)
            ->with('success', "✅ {$membre->prenom} {$membre->nom} inscrit avec succès !");
    }

    public function update(Request $request, Seminaire $seminaire, InscriptionSeminaire $inscription)
    {
        $validated = $request->validate([
            'statut' => 'required|in:inscrit,present,absent,annule',
            'montant_paye' => 'nullable|numeric|min:0',
            'notes_participant' => 'nullable|string',
            'certificat_obtenu' => 'boolean',
        ]);

        $inscription->update($validated);

        return back()->with('success', 'Inscription mise à jour.');
    }

    public function destroy(Seminaire $seminaire, InscriptionSeminaire $inscription)
    {
        $membre_nom = $inscription->user->nom ?? 'Membre';
        $inscription->delete();

        return back()->with('success', "Inscription de {$membre_nom} supprimée.");
    }
}
