<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin', 'ecole.restriction']);
    }

    public function index()
    {
        $users = User::with(['ecole', 'roles'])
            ->when(!auth()->user()->hasRole('super-admin'), function ($query) {
                $query->where('ecole_id', session('ecole_id'));
            })
            ->orderBy('nom')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $ecoles = auth()->user()->hasRole('super-admin') 
            ? Ecole::all() 
            : Ecole::where('id', session('ecole_id'))->get();
            
        return view('admin.users.create', compact('ecoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'ecole_id' => 'required|exists:ecoles,id',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['code_utilisateur'] = $this->generateUserCode();
        
        $user = User::create($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        $this->authorizeAccess($user);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorizeAccess($user);
        
        $ecoles = auth()->user()->hasRole('super-admin') 
            ? Ecole::all() 
            : Ecole::where('id', session('ecole_id'))->get();
            
        return view('admin.users.edit', compact('user', 'ecoles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAccess($user);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'ecole_id' => 'required|exists:ecoles,id',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'actif' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        $this->authorizeAccess($user);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    private function authorizeAccess(User $user)
    {
        if (!auth()->user()->hasRole('super-admin') && $user->ecole_id !== session('ecole_id')) {
            abort(403, 'Accès non autorisé à cet utilisateur.');
        }
    }

    private function generateUserCode()
    {
        $lastUser = User::orderBy('id', 'desc')->first();
        $number = $lastUser ? intval(substr($lastUser->code_utilisateur, 1)) + 1 : 1;
        return 'U' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
