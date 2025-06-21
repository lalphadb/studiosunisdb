<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CoursController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['auth', 'verified'];
    }

    public function index()
    {
        $cours = Cours::with('ecole')
            ->when(auth()->user()->ecole_id, fn($q, $ecole_id) => $q->where('ecole_id', $ecole_id))
            ->paginate(15);
            
        return view('admin.cours.index', compact('cours'));
    }

    public function show(Cours $cours)
    {
        return view('admin.cours.show', compact('cours'));
    }

    public function create()
    {
        return view('admin.cours.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.cours.index');
    }

    public function edit(Cours $cours)
    {
        return view('admin.cours.edit', compact('cours'));
    }

    public function update(Request $request, Cours $cours)
    {
        return redirect()->route('admin.cours.show', $cours);
    }

    public function destroy(Cours $cours)
    {
        return redirect()->route('admin.cours.index');
    }
}
