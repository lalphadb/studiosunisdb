<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

/**
 * Dashboard Ultra-Simple pour identifier le problème page blanche
 */
class DashboardSimpleController extends Controller
{
    /**
     * Version minimale sans aucune complexité
     */
    public function simple(Request $request)
    {
        $user = Auth::user();
        
        return Inertia::render('DashboardSimple', [
            'message' => 'Dashboard Simple Fonctionne !',
            'user_name' => $user ? $user->name : 'Invité',
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);
    }
    
    /**
     * Test JSON pur (sans Inertia)
     */
    public function json(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Controller Dashboard fonctionne !',
            'user' => Auth::user() ? Auth::user()->only(['id', 'name', 'email']) : null,
            'timestamp' => now()->toISOString(),
        ]);
    }
    
    /**
     * Test HTML pur (sans Vue.js)
     */
    public function html(Request $request)
    {
        $user = Auth::user();
        
        return response(view('dashboard-simple', [
            'user' => $user,
            'timestamp' => now(),
        ]));
    }
}
