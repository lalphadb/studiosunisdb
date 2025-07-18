<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('can:viewAny,App\Policies\LogPolicy', only: ['index']),
            new Middleware('can:clear,App\Policies\LogPolicy', only: ['clear']),
        ];
    }

    public function index(Request $request)
    {
        // Logique d'affichage des logs pour SuperAdmin uniquement
        $logs = [];
        
        // Ici tu peux ajouter la logique pour lire les logs Laravel
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $logs = array_slice(file($logFile), -50); // 50 dernières lignes
        }
        
        return view('admin.logs.index', compact('logs'));
    }

    public function clear(Request $request)
    {
        // Logique de nettoyage des logs
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
        
        return redirect()->route('admin.logs.index')
            ->with('success', 'Logs nettoyés avec succès.');
    }
}
