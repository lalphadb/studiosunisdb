<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EcoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth', 
            'verified',
            new Middleware('role:superadmin', except: ['show'])
        ];
    }

    public function index()
    {
        $ecoles = Ecole::withCount('users')->paginate(15);
        return view('admin.ecoles.index', compact('ecoles'));
    }

    public function show(Ecole $ecole)
    {
        return view('admin.ecoles.show', compact('ecole'));
    }

    public function create()
    {
        return view('admin.ecoles.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.ecoles.index');
    }
}
