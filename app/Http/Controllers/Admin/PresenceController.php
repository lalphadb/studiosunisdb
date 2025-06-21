<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PresenceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['auth', 'verified'];
    }

    public function index()
    {
        $presences = Presence::with(['user', 'cours'])
            ->when(auth()->user()->ecole_id, function($q, $ecole_id) {
                return $q->whereHas('user', fn($q) => $q->where('ecole_id', $ecole_id));
            })
            ->paginate(15);
            
        return view('admin.presences.index', compact('presences'));
    }

    public function show(Presence $presence)
    {
        return view('admin.presences.show', compact('presence'));
    }

    public function create()
    {
        return view('admin.presences.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.presences.index');
    }

    public function scanQR(Request $request)
    {
        return response()->json(['status' => 'success']);
    }
}
