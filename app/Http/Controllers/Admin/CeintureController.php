<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CeintureRequest;
use App\Models\Ceinture;
use App\Models\User;
use App\Models\UserCeinture;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CeintureController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:superadmin|admin_ecole|admin-ecole|admin|instructeur', only: ['index', 'show']),
            new Middleware('role:superadmin|admin_ecole|admin-ecole', only: ['create', 'store', 'edit', 'update', 'destroy']),
            new Middleware('role:superadmin|admin_ecole|admin-ecole|admin', only: ['attribuer']),
        ];
    }

    public function index()
    {
        $ceintures = Ceinture::orderBy('ordre')->paginate(15);
        
        // Statistiques des progressions avec les bonnes relations
        $progressions = UserCeinture::with(['user.ecole', 'ceinture'])
            ->where('valide', true)
            ->when(auth()->user()->hasAnyRole(['admin_ecole', 'admin-ecole']), function ($query) {
                return $query->whereHas('user', function ($q) {
                    $q->where('ecole_id', auth()->user()->ecole_id);
                });
            })
            ->get()
            ->groupBy('ceinture_id');

        // Statistiques pour les métriques
        $totalCeintures = Ceinture::count();
        $totalProgressions = UserCeinture::where('valide', true)->count();
        $totalUtilisateursAvecCeinture = UserCeinture::where('valide', true)->distinct('user_id')->count();
        $ceinturesActives = Ceinture::whereHas('userCeintures', function($q) {
            $q->where('valide', true);
        })->count();

        return view('admin.ceintures.index', compact(
            'ceintures', 
            'progressions',
            'totalCeintures',
            'totalProgressions', 
            'totalUtilisateursAvecCeinture',
            'ceinturesActives'
        ));
    }

    public function create()
    {
        return view('admin.ceintures.create');
    }

    public function store(CeintureRequest $request)
    {
        $validated = $request->validated();
        $ceinture = Ceinture::create($validated);

        return redirect()->route('admin.ceintures.index')
            ->with('success', 'Ceinture créée avec succès.');
    }

    public function show(Ceinture $ceinture)
    {
        $progressions = UserCeinture::with(['user.ecole'])
            ->where('ceinture_id', $ceinture->id)
            ->when(auth()->user()->hasAnyRole(['admin_ecole', 'admin-ecole']), function ($query) {
                return $query->whereHas('user', function ($q) {
                    $q->where('ecole_id', auth()->user()->ecole_id);
                });
            })
            ->orderByDesc('date_obtention')
            ->paginate(20);

        return view('admin.ceintures.show', compact('ceinture', 'progressions'));
    }

    public function edit(Ceinture $ceinture)
    {
        return view('admin.ceintures.edit', compact('ceinture'));
    }

    public function update(CeintureRequest $request, Ceinture $ceinture)
    {
        $validated = $request->validated();
        $ceinture->update($validated);

        return redirect()->route('admin.ceintures.index')
            ->with('success', 'Ceinture modifiée avec succès.');
    }

    public function destroy(Ceinture $ceinture)
    {
        if (UserCeinture::where('ceinture_id', $ceinture->id)->exists()) {
            return redirect()->route('admin.ceintures.index')
                ->with('error', 'Cette ceinture ne peut pas être supprimée car elle est attribuée à des utilisateurs.');
        }

        $ceinture->delete();

        return redirect()->route('admin.ceintures.index')
            ->with('success', 'Ceinture supprimée avec succès.');
    }

    public function attribuer(Request $request, Ceinture $ceinture)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'date_obtention' => 'required|date',
                'examinateur' => 'nullable|string|max:255',
                'commentaires' => 'nullable|string',
                'valide' => 'boolean',
            ]);

            $existing = UserCeinture::where('user_id', $request->user_id)
                ->where('ceinture_id', $ceinture->id)
                ->exists();

            if ($existing) {
                return back()->with('error', 'Cet utilisateur possède déjà cette ceinture.');
            }

            UserCeinture::create([
                'user_id' => $request->user_id,
                'ceinture_id' => $ceinture->id,
                'date_obtention' => $request->date_obtention,
                'examinateur' => $request->examinateur,
                'commentaires' => $request->commentaires,
                'valide' => $request->boolean('valide', false),
            ]);

            return redirect()->route('admin.ceintures.show', $ceinture)
                ->with('success', 'Ceinture attribuée avec succès.');
        }

        $users = User::where('active', true)
            ->when(auth()->user()->hasAnyRole(['admin_ecole', 'admin-ecole']), function ($query) {
                return $query->where('ecole_id', auth()->user()->ecole_id);
            })
            ->orderBy('name')
            ->get();

        return view('admin.ceintures.attribuer', compact('ceinture', 'users'));
    }

    public function types()
    {
        $types = [
            'kyu' => 'Ceintures Kyu (Grades inférieurs)',
            'dan' => 'Ceintures Dan (Grades supérieurs)',
        ];

        return view('admin.ceintures.types', compact('types'));
    }
}
