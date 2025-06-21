<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PaiementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['auth', 'verified'];
    }

    public function index()
    {
        $paiements = Paiement::with(['user', 'ecole'])
            ->when(auth()->user()->ecole_id, fn($q, $ecole_id) => $q->where('ecole_id', $ecole_id))
            ->paginate(15);
            
        return view('admin.paiements.index', compact('paiements'));
    }

    public function show(Paiement $paiement)
    {
        return view('admin.paiements.show', compact('paiement'));
    }

    public function create()
    {
        return view('admin.paiements.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.paiements.index');
    }
}
