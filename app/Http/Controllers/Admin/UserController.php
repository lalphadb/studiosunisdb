<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('can:view-users')->only(['index', 'show']);
        $this->middleware('can:create-user')->only(['create', 'store']);
        $this->middleware('can:edit-user')->only(['edit', 'update']);
        $this->middleware('can:delete-user')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = User::with(['ecole', 'roles']);
        
        if (!$user->isSuperAdmin()) {
            $query->where('ecole_id', $user->ecole_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('ecole_id') && $user->isSuperAdmin()) {
            $query->where('ecole_id', $request->ecole_id);
        }
        
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        $users = $query->paginate(15);
        
        $ecoles = collect();
        if ($user->isSuperAdmin()) {
            $ecoles = Ecole::where('active', true)->orderBy('nom')->get();
        }
        
        $roles = Role::all();
        $metrics = $this->getUserMetrics($user);
        
        return view('admin.users.index', compact('users', 'ecoles', 'roles', 'metrics'));
    }

    public function show(User $user)
    {
        $user->load(['ecole', 'roles']);
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        $user = auth()->user();
        
        $ecoles = collect();
        if ($user->isSuperAdmin()) {
            $ecoles = Ecole::where('active', true)->orderBy('nom')->get();
        } else {
            $ecoles = collect([$user->ecole]);
        }
        
        $roles = Role::all();
        
        return view('admin.users.create', compact('ecoles', 'roles'));
    }

    public function store(Request $request)
    {
        // Implémentation basique pour tester
        return redirect()->route('admin.users.index')->with('success', 'Fonctionnalité en cours de développement');
    }

    public function edit(User $user)
    {
        return redirect()->route('admin.users.show', $user)->with('info', 'Fonctionnalité en cours de développement');
    }

    public function update(Request $request, User $user)
    {
        return redirect()->route('admin.users.show', $user)->with('info', 'Fonctionnalité en cours de développement');
    }

    public function destroy(User $user)
    {
        return redirect()->route('admin.users.index')->with('info', 'Fonctionnalité en cours de développement');
    }

    private function getUserMetrics($user)
    {
        $metrics = [];
        
        if ($user->isSuperAdmin()) {
            $metrics['total_users'] = User::count();
            $metrics['admins'] = User::role('admin')->count();
            $metrics['instructeurs'] = User::role('instructeur')->count();
            $metrics['membres'] = User::role('membre')->count();
        } else {
            $ecoleUsers = User::where('ecole_id', $user->ecole_id);
            $metrics['admins'] = (clone $ecoleUsers)->role('admin')->count();
            $metrics['instructeurs'] = (clone $ecoleUsers)->role('instructeur')->count();
            $metrics['membres'] = (clone $ecoleUsers)->role('membre')->count();
        }
        
        return $metrics;
    }
}
