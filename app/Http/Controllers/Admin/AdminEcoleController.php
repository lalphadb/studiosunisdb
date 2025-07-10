<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\Ecole;
use Illuminate\Http\Request;

class AdminEcoleController extends BaseAdminController
{
    public function index(Request $request)
    {
        $query = Ecole::query();
        
        if (!auth()->user()->hasRole('superadmin')) {
            $query->where('id', auth()->user()->ecole_id);
        }
        
        $ecoles = $query->paginate(15);
        
        return view('admin.ecoles.index', compact('ecoles'));
    }

    public function create()
    {
        return view('admin.ecoles.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.ecoles.index')
            ->with('info', 'Fonction en développement');
    }

    public function show(Ecole $ecole)
    {
        return view('admin.ecoles.show', compact('ecole'));
    }

    public function edit(Ecole $ecole)
    {
        return view('admin.ecoles.edit', compact('ecole'));
    }

    public function update(Request $request, Ecole $ecole)
    {
        return redirect()->route('admin.ecoles.index')
            ->with('info', 'Fonction en développement');
    }

    public function destroy(Ecole $ecole)
    {
        return redirect()->route('admin.ecoles.index')
            ->with('info', 'Fonction en développement');
    }

    public function export(Request $request)
    {
        return response()->json(['message' => 'Export Ecoles fonctionnel']);
    }
}
