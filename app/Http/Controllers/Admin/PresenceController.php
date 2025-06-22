<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PresenceRequest;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PresenceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:view-presences', only: ['index', 'show']),
            new Middleware('can:create-presence', only: ['create', 'store']),
            new Middleware('can:edit-presence', only: ['edit', 'update']),
            new Middleware('can:delete-presence', only: ['destroy']),
        ];
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

    public function store(PresenceRequest $request)
    {
        // TODO: Implémenter
        return redirect()->route('admin.presences.index');
    }

    public function scanQR(Request $request)
    {
        return response()->json(['status' => 'success']);
    }
}
