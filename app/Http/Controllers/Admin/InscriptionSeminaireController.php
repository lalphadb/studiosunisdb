<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InscriptionSeminaireRequest;
use App\Models\InscriptionSeminaire;
use App\Models\Seminaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class InscriptionSeminaireController extends Controller implements HasMiddleware
{
    /**
     * Middleware Laravel 12.19 avec autorisation selon les Policies
     */
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:viewAny,App\Models\InscriptionSeminaire', only: ['index']),
            new Middleware('can:delete,inscription', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = InscriptionSeminaire::with(['user', 'seminaire.ecole'])->orderBy('created_at', 'desc');
        
        // Filtre par école pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->whereHas('seminaire', function($q) {
                $q->where('ecole_id', auth()->user()->ecole_id);
            });
        }
        
        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })->orWhereHas('seminaire', function($sq) use ($search) {
                    $sq->where('titre', 'like', "%{$search}%");
                });
            });
        }
        
        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        // Filtre par séminaire
        if ($request->filled('seminaire_id')) {
            $query->where('seminaire_id', $request->seminaire_id);
        }
        
        $inscriptions = $query->paginate(15)->withQueryString();
        $seminaires = $this->getSeminairesForUser(auth()->user());
        
        return view('admin.inscriptions-seminaires.index', compact('inscriptions', 'seminaires'));
    }

    public function destroy(InscriptionSeminaire $inscription)
    {
        $inscription->delete();
        
        return redirect()->route('admin.inscriptions-seminaires.index')
            ->with('success', 'Inscription supprimée avec succès.');
    }

    /**
     * Obtenir les séminaires selon les permissions
     */
    private function getSeminairesForUser($user)
    {
        $query = Seminaire::with('ecole')->orderBy('titre');
        
        if ($user->hasRole('admin_ecole')) {
            $query->where('ecole_id', $user->ecole_id);
        }
        
        return $query->get();
    }
}
