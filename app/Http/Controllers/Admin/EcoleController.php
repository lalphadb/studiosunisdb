<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EcoleRequest;
use App\Models\Ecole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EcoleController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;

    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
            new Middleware('can:view-ecoles', only: ['index', 'show']),
            new Middleware('can:create-ecole', only: ['create', 'store']),
            new Middleware('can:edit-ecole', only: ['edit', 'update']),
            new Middleware('can:delete-ecole', only: ['destroy']),
        ];
    }

    public function index()
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            $ecoles = Ecole::withCount(['users', 'cours'])
                ->orderBy('nom')
                ->get();
                
            $metrics = [
                'total_ecoles' => Ecole::count(),
                'total_users' => User::count(),
                'total_admins' => User::whereHas('roles', function($q) {
                    $q->whereIn('name', ['admin', 'superadmin']);
                })->count(),
                'ecoles_actives' => Ecole::where('active', true)->count(),
            ];
        } else {
            $ecoles = Ecole::where('id', $user->ecole_id)
                ->withCount(['users', 'cours'])
                ->get();
                
            $userEcole = $user->ecole;
            $metrics = [
                'users_ecole' => $userEcole ? $userEcole->users()->count() : 0,
                'cours_actifs' => $userEcole ? $userEcole->cours()->where('active', true)->count() : 0,
                'instructeurs' => $userEcole ? $userEcole->users()->whereHas('roles', function($q) {
                    $q->where('name', 'instructeur');
                })->count() : 0,
                'membres_actifs' => $userEcole ? $userEcole->users()->whereHas('roles', function($q) {
                    $q->where('name', 'membre');
                })->where('active', true)->count() : 0,
            ];
        }

        return view('admin.ecoles.index', compact('ecoles', 'metrics'));
    }

    public function create()
    {
        return view('admin.ecoles.create');
    }

    public function store(EcoleRequest $request)
    {
        try {
            $data = $request->validated();
            
            if (isset($data['active'])) {
                $data['statut'] = $data['active'] ? 'actif' : 'inactif';
            }
            
            $ecole = Ecole::create($data);
            
            return redirect()
                ->route('admin.ecoles.show', $ecole)
                ->with('success', 'École créée avec succès.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function show(Ecole $ecole)
    {
        $this->authorize('view', $ecole);
        
        $ecole->load(['users.roles', 'cours']);
        
        return view('admin.ecoles.show', compact('ecole'));
    }

    public function edit(Ecole $ecole)
    {
        $this->authorize('update', $ecole);
        
        return view('admin.ecoles.edit', compact('ecole'));
    }

    public function update(EcoleRequest $request, Ecole $ecole)
    {
        $this->authorize('update', $ecole);
        
        try {
            $data = $request->validated();
            
            if (isset($data['active'])) {
                $data['statut'] = $data['active'] ? 'actif' : 'inactif';
            }
            
            $ecole->update($data);
            
            return redirect()
                ->route('admin.ecoles.show', $ecole)
                ->with('success', 'École mise à jour avec succès.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function destroy(Ecole $ecole)
    {
        $this->authorize('delete', $ecole);
        
        try {
            if ($ecole->users()->exists()) {
                return back()->with('error', 'Impossible de supprimer cette école car elle contient des utilisateurs.');
            }
            
            $ecole->delete();
            
            return redirect()
                ->route('admin.ecoles.index')
                ->with('success', 'École supprimée avec succès.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
