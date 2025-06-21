<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Ceinture, MembreCeinture, User, Ecole};
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CeintureController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:view-ceintures', only: ['index', 'show']),
            new Middleware('can:create-ceinture', only: ['create', 'store']),
            new Middleware('can:edit-ceinture', only: ['edit', 'update']),
            new Middleware('can:delete-ceinture', only: ['destroy']),
            new Middleware('can:assign-ceintures', only: ['attribuer']),
        ];
    }

    public function index(Request $request)
    {
        $query = MembreCeinture::with(['user.ecole', 'ceinture'])
            ->when(auth()->user()->ecole_id, function($q, $ecole_id) {
                return $q->whereHas('user', fn($q) => $q->where('ecole_id', $ecole_id));
            });

        // Filtres
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('ecole_id')) {
            $query->whereHas('user', fn($q) => $q->where('ecole_id', $request->ecole_id));
        }

        $progressions = $query->latest('date_obtention')->paginate(15)->withQueryString();
        
        $ecoles = auth()->user()->hasRole('superadmin') 
            ? Ecole::active()->get() 
            : collect();

        return view('admin.ceintures.index', compact('progressions', 'ecoles'));
    }

    public function show(MembreCeinture $ceinture)
    {
        $ceinture->load(['user.ecole', 'ceinture']);
        
        $historique = MembreCeinture::where('user_id', $ceinture->user_id)
            ->with('ceinture')
            ->orderBy('date_obtention', 'desc')
            ->get();

        return view('admin.ceintures.show', [
            'progression' => $ceinture,
            'historique' => $historique
        ]);
    }

    public function create(Request $request)
    {
        $users = User::with('ecole')
            ->when(auth()->user()->ecole_id, fn($q, $ecole_id) => $q->where('ecole_id', $ecole_id))
            ->active()
            ->orderBy('name')
            ->get();
            
        $ceintures = Ceinture::orderBy('ordre')->get();
        
        $userSelectionne = $request->filled('user_id') 
            ? User::find($request->user_id) 
            : null;

        return view('admin.ceintures.create', compact('users', 'ceintures', 'userSelectionne'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date|before_or_equal:today',
            'examinateur' => 'nullable|string|max:191',
            'commentaires' => 'nullable|string|max:1000',
            'valide' => 'boolean',
        ]);

        // Multi-tenant: vérifier que l'user appartient à l'école de l'admin
        $user = User::findOrFail($request->user_id);
        if (auth()->user()->ecole_id && $user->ecole_id !== auth()->user()->ecole_id) {
            abort(403, 'Vous ne pouvez attribuer des ceintures qu\'aux membres de votre école.');
        }

        $data = $request->all();
        $data['valide'] = $request->boolean('valide');

        MembreCeinture::create($data);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log("Ceinture attribuée: " . Ceinture::find($request->ceinture_id)->nom);

        return redirect()->route('admin.ceintures.index')
            ->with('success', "Ceinture attribuée avec succès à {$user->name}");
    }

    public function edit(MembreCeinture $ceinture)
    {
        $ceinture->load(['user.ecole', 'ceinture']);
        
        // Multi-tenant check
        if (auth()->user()->ecole_id && $ceinture->user->ecole_id !== auth()->user()->ecole_id) {
            abort(403, 'Accès refusé');
        }
        
        $ceintures = Ceinture::orderBy('ordre')->get();

        return view('admin.ceintures.edit', [
            'progression' => $ceinture,
            'ceintures' => $ceintures
        ]);
    }

    public function update(Request $request, MembreCeinture $ceinture)
    {
        $request->validate([
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date|before_or_equal:today',
            'examinateur' => 'nullable|string|max:191',
            'commentaires' => 'nullable|string|max:1000',
            'valide' => 'boolean',
        ]);

        // Multi-tenant check
        if (auth()->user()->ecole_id && $ceinture->user->ecole_id !== auth()->user()->ecole_id) {
            abort(403, 'Accès refusé');
        }

        $data = $request->all();
        $data['valide'] = $request->boolean('valide');
        
        $ceinture->update($data);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($ceinture->user)
            ->log("Attribution ceinture modifiée");

        return redirect()->route('admin.ceintures.show', $ceinture)
            ->with('success', 'Attribution mise à jour avec succès');
    }

    public function destroy(MembreCeinture $ceinture)
    {
        // Multi-tenant + permission check
        if (auth()->user()->ecole_id && $ceinture->user->ecole_id !== auth()->user()->ecole_id) {
            abort(403, 'Accès refusé');
        }

        $userName = $ceinture->user->name;
        $ceintureName = $ceinture->ceinture->nom;
        
        activity()
            ->causedBy(auth()->user())
            ->performedOn($ceinture->user)
            ->log("Attribution ceinture supprimée: {$ceintureName}");
            
        $ceinture->delete();

        return redirect()->route('admin.ceintures.index')
            ->with('success', "Attribution de {$ceintureName} pour {$userName} supprimée");
    }

    public function attribuer(Request $request)
    {
        // Redirection vers create avec paramètres
        return redirect()->route('admin.ceintures.create', $request->all());
    }
}
