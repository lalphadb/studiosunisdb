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

    public function index(Request $request)
    {
        $query = Paiement::with('user');

        // Filtrage multi-tenant
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_interne', 'like', "%{$search}%")
                  ->orWhere('reference_externe', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $paiements = $query->latest()->paginate(15);

        // Statistiques pour le dashboard
        $stats = [
            'en_attente' => Paiement::where('statut', 'en_attente')->count(),
            'paye' => Paiement::where('statut', 'paye')->count(),
            'total_en_attente' => Paiement::where('statut', 'en_attente')->sum('montant'),
        ];

        return view('admin.paiements.index', compact('paiements', 'stats'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        
        if (auth()->user()->hasRole('admin_ecole')) {
            $users = User::where('ecole_id', auth()->user()->ecole_id)->orderBy('name')->get();
        }

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
            'statut' => 'en_attente', // Toujours en attente au début
            'methode_paiement' => $request->methode_paiement ?: 'virement',
            'notes' => $request->notes,
            'reference_interne' => 'PAY-' . date('Ymd') . '-' . rand(1000, 9999),
            'ecole_id' => $user->ecole_id,
            'frais' => 0,
        ]);

        return redirect()->route('admin.paiements.index')
                        ->with('success', 'Paiement créé ! Instructions envoyées par email au membre.');
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
            'statut' => $request->statut ?: 'en_attente',
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

    /**
     * Marquer un paiement comme reçu (avec référence virement)
     */
    public function marquerRecu(Request $request, Paiement $paiement)
    {
        $request->validate([
            'reference_externe' => 'required|string|max:255',
        ]);

        $paiement->update([
            'statut' => 'paye',
            'reference_externe' => $request->reference_externe,
            'date_paiement' => now(),
        ]);

        return redirect()->back()->with('success', 'Paiement marqué comme reçu !');
    }

    /**
     * Actions de masse - Afficher la page
     */
    public function actionsMasse()
    {
        $paiementsEnAttente = Paiement::with('user')
            ->where('statut', 'en_attente')
            ->latest()
            ->get();

        return view('admin.paiements.actions-masse', compact('paiementsEnAttente'));
    }

    /**
     * Actions de masse - Traitement
     */
    public function traiterActionsMasse(Request $request)
    {
        $request->validate([
            'action' => 'required|in:marquer_paye,marquer_annule',
            'paiements' => 'required|array',
            'paiements.*' => 'exists:paiements,id',
        ]);

        $count = 0;
        
        foreach ($request->paiements as $paiementId) {
            $paiement = Paiement::find($paiementId);
            
            if ($paiement && $paiement->statut === 'en_attente') {
                $updates = [];
                
                if ($request->action === 'marquer_paye') {
                    $updates = [
                        'statut' => 'paye',
                        'date_paiement' => now(),
                    ];
                    
                    // Si une référence est fournie pour ce paiement
                    $refField = 'reference_' . $paiementId;
                    if ($request->filled($refField)) {
                        $updates['reference_externe'] = $request->input($refField);
                    }
                } elseif ($request->action === 'marquer_annule') {
                    $updates = [
                        'statut' => 'annule',
                    ];
                }
                
                $paiement->update($updates);
                $count++;
            }
        }

        $actionText = $request->action === 'marquer_paye' ? 'marqués comme payés' : 'annulés';
        
        return redirect()->route('admin.paiements.index')
                        ->with('success', "{$count} paiements {$actionText} avec succès !");
    }

    /**
     * Page de validation rapide (pour traitement de lots)
     */
    public function validationRapide()
    {
        $paiementsEnAttente = Paiement::with('user')
            ->where('statut', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($paiement) {
                return $paiement->created_at->format('Y-m-d');
            });

        return view('admin.paiements.validation-rapide', compact('paiementsEnAttente'));
    }
}
