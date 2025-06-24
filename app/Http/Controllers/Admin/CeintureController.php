<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CeintureRequest;
use App\Models\Ceinture;
use App\Models\User;
use App\Models\UtilisateurCeinture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CeintureController extends Controller
{
    public static function middleware(): array
    {
        return [
            'can:viewAny,App\Models\Ceinture' => ['only' => ['index']],
            'can:view,ceinture' => ['only' => ['show']],
            'can:create,App\Models\Ceinture' => ['only' => ['create', 'store']],
            'can:update,ceinture' => ['only' => ['edit', 'update']],
            'can:delete,ceinture' => ['only' => ['destroy']],
        ];
    }

    public function index()
    {
        $ceintures = Ceinture::orderBy('ordre')->paginate(15);
        
        $progressions = UtilisateurCeinture::with(['user', 'ceinture', 'user.ecole'])
            ->when(!Auth::user()->hasRole('super-admin'), function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('ecole_id', Auth::user()->ecole_id);
                });
            })
            ->latest('date_obtention')
            ->take(10)
            ->get();

        return view('admin.ceintures.index', compact('ceintures', 'progressions'));
    }

    public function create()
    {
        return view('admin.ceintures.create');
    }

    public function store(CeintureRequest $request)
    {
        Ceinture::create($request->validated());

        return redirect()
            ->route('admin.ceintures.index')
            ->with('success', 'Ceinture créée avec succès.');
    }

    public function show(Ceinture $ceinture)
    {
        $utilisateurs = UtilisateurCeinture::where('ceinture_id', $ceinture->id)
            ->with(['user', 'user.ecole'])
            ->when(!Auth::user()->hasRole('super-admin'), function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('ecole_id', Auth::user()->ecole_id);
                });
            })
            ->latest('date_obtention')
            ->paginate(15);

        return view('admin.ceintures.show', compact('ceinture', 'utilisateurs'));
    }

    public function edit(Ceinture $ceinture)
    {
        return view('admin.ceintures.edit', compact('ceinture'));
    }

    public function update(CeintureRequest $request, Ceinture $ceinture)
    {
        $ceinture->update($request->validated());

        return redirect()
            ->route('admin.ceintures.show', $ceinture)
            ->with('success', 'Ceinture mise à jour avec succès.');
    }

    public function destroy(Ceinture $ceinture)
    {
        if ($ceinture->utilisateurCeintures()->exists()) {
            return redirect()
                ->route('admin.ceintures.index')
                ->with('error', 'Impossible de supprimer cette ceinture car elle est attribuée à des utilisateurs.');
        }

        $ceinture->delete();

        return redirect()
            ->route('admin.ceintures.index')
            ->with('success', 'Ceinture supprimée avec succès.');
    }

    public function attribuer(Request $request, Ceinture $ceinture)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date_obtention' => 'required|date',
            'examinateur' => 'nullable|string|max:255',
            'commentaires' => 'nullable|string',
        ]);

        // Vérifier si l'utilisateur a déjà cette ceinture
        $existeDeja = UtilisateurCeinture::where('user_id', $request->user_id)
            ->where('ceinture_id', $ceinture->id)
            ->exists();

        if ($existeDeja) {
            return back()->with('error', 'Cet utilisateur possède déjà cette ceinture.');
        }

        UtilisateurCeinture::create([
            'user_id' => $request->user_id,
            'ceinture_id' => $ceinture->id,
            'date_obtention' => $request->date_obtention,
            'examinateur' => $request->examinateur,
            'commentaires' => $request->commentaires,
            'valide' => true,
        ]);

        return back()->with('success', 'Ceinture attribuée avec succès.');
    }
}
