<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminaire;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SeminaireController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['auth', 'verified'];
    }

    public function index()
    {
        $seminaires = Seminaire::with('ecole')
            ->when(auth()->user()->ecole_id, fn($q, $ecole_id) => $q->where('ecole_id', $ecole_id))
            ->paginate(15);
            
        return view('admin.seminaires.index', compact('seminaires'));
    }

    public function show(Seminaire $seminaire)
    {
        return view('admin.seminaires.show', compact('seminaire'));
    }

    public function create()
    {
        return view('admin.seminaires.create');
    }

    public function store(Request $request)
    {
        // TODO: Implémenter
        return redirect()->route('admin.seminaires.index');
    }

    public function edit(Seminaire $seminaire)
    {
        return view('admin.seminaires.edit', compact('seminaire'));
    }

    public function update(Request $request, Seminaire $seminaire)
    {
        // TODO: Implémenter
        return redirect()->route('admin.seminaires.show', $seminaire);
    }

    public function destroy(Seminaire $seminaire)
    {
        // TODO: Implémenter
        return redirect()->route('admin.seminaires.index');
    }
}
