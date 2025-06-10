<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membre;
use App\Models\Ecole;
use App\Models\Ceinture;
use App\Models\Seminaire;
use App\Models\MembreCeinture;
use App\Models\InscriptionSeminaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MembreController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('viewAny', Membre::class)) {
            abort(403, 'Accès non autorisé');
        }

        $query = Membre::with(['ecole']);

        // Filtre automatique par école pour admin
        if (!Gate::allows('manage-system')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }

        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('prenom', 'like', "%{$search}%")
                  ->orWhere('nom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre école (seulement pour superadmin)
        if ($request->filled('ecole_id') && Gate::allows('manage-system')) {
            $query->where('ecole_id', $request->ecole_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $query->orderBy('created_at', 'desc');
        $membres = $query->paginate(20)->withQueryString();
        
        // Écoles pour filtre (selon les permissions)
        $ecoles = $this->getEcolesForUser();

        return view('admin.membres.index', compact('membres', 'ecoles'));
    }

    public function create()
    {
        if (!Gate::allows('create', Membre::class)) {
            abort(403, 'Accès non autorisé');
        }
        
        $ecoles = $this->getEcolesForUser();
        $ecole_defaut = !Gate::allows('manage-system') ? auth()->user()->ecole_id : null;
        
        return view('admin.membres.create', compact('ecoles', 'ecole_defaut'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('create', Membre::class)) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'ecole_id' => 'required|exists:ecoles,id',
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'date_naissance' => 'nullable|date',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string',
            'contact_urgence' => 'nullable|string|max:255',
            'telephone_urgence' => 'nullable|string|max:20',
            'date_inscription' => 'required|date',
            'statut' => 'required|in:actif,inactif,suspendu',
            'notes' => 'nullable|string',
        ]);

        // Forcer l'école pour les admins d'école
        if (!Gate::allows('manage-system')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }

        $membre = Membre::create($validated);

        return redirect()->route('admin.membres.show', $membre)
                        ->with('success', 'Membre créé avec succès !');
    }

    public function show(Membre $membre)
    {
        if (!Gate::allows('view', $membre)) {
            abort(403, 'Accès non autorisé');
        }
        
        $membre->load(['ecole']);
        
        // Charger données pour modals si tables existent
        $ceintures = collect();
        $seminaires = collect();
        
        try {
            $ceintures = Ceinture::orderBy('ordre_affichage')->get();
        } catch (\Exception $e) {
            // Table ceintures n'existe pas encore
        }
        
        try {
            $seminaires = Seminaire::where('statut', 'ouvert')->orderBy('date_debut')->get();
        } catch (\Exception $e) {
            // Table seminaires n'existe pas encore
        }
        
        return view('admin.membres.show', compact('membre', 'ceintures', 'seminaires'));
    }

    public function edit(Membre $membre)
    {
        if (!Gate::allows('update', $membre)) {
            abort(403, 'Accès non autorisé');
        }
        
        $ecoles = $this->getEcolesForUser();
        return view('admin.membres.edit', compact('membre', 'ecoles'));
    }

    public function update(Request $request, Membre $membre)
    {
        if (!Gate::allows('update', $membre)) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'ecole_id' => 'required|exists:ecoles,id',
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'date_naissance' => 'nullable|date',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string',
            'contact_urgence' => 'nullable|string|max:255',
            'telephone_urgence' => 'nullable|string|max:20',
            'date_inscription' => 'required|date',
            'statut' => 'required|in:actif,inactif,suspendu',
            'notes' => 'nullable|string',
        ]);

        // Forcer l'école pour les admins d'école
        if (!Gate::allows('manage-system')) {
            $validated['ecole_id'] = auth()->user()->ecole_id;
        }

        $membre->update($validated);

        return redirect()->route('admin.membres.show', $membre)
                        ->with('success', 'Membre mis à jour avec succès !');
    }

    public function destroy(Membre $membre)
    {
        if (!Gate::allows('delete', $membre)) {
            abort(403, 'Accès non autorisé');
        }

        $membre->delete();

        return redirect()->route('admin.membres.index')
                        ->with('success', 'Membre supprimé avec succès !');
    }

    public function export(Request $request)
    {
        if (!Gate::allows('viewAny', Membre::class)) {
            abort(403, 'Accès non autorisé');
        }

        $query = Membre::with(['ecole']);
        
        // Filtre automatique par école pour admin
        if (!Gate::allows('manage-system')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }

        $membres = $query->get();
        return $this->exportExcel($membres);
    }

    private function exportExcel($membres)
    {
        $filename = 'membres_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function() use ($membres) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Prénom', 'Nom', 'Email', 'Téléphone', 'École', 'Statut', 'Date inscription']);
            
            foreach ($membres as $membre) {
                fputcsv($file, [
                    $membre->prenom,
                    $membre->nom,
                    $membre->email,
                    $membre->telephone,
                    $membre->ecole->nom ?? '',
                    $membre->statut,
                    $membre->date_inscription ? $membre->date_inscription->format('d/m/Y') : ''
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getEcolesForUser()
    {
        if (Gate::allows('manage-system')) {
            return Ecole::where('statut', 'actif')->orderBy('nom')->get();
        } else {
            return Ecole::where('id', auth()->user()->ecole_id)->where('statut', 'actif')->get();
        }
    }

    // Méthodes futures pour ceintures et séminaires
    public function attribuerCeinture(Request $request, Membre $membre)
    {
        return redirect()->route('admin.membres.show', $membre)
                        ->with('info', 'Fonctionnalité en développement');
    }

    public function inscrireSeminaire(Request $request, Membre $membre)
    {
        return redirect()->route('admin.membres.show', $membre)
                        ->with('info', 'Fonctionnalité en développement');
    }
}
