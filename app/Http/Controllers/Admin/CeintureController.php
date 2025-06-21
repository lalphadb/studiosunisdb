<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ceinture;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CeintureController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['auth', 'verified'];
    }

    public function index()
    {
        $ceintures = Ceinture::orderBy('ordre')->paginate(15);
        return view('admin.ceintures.index', compact('ceintures'));
    }

    public function show(Ceinture $ceinture)
    {
        return view('admin.ceintures.show', compact('ceinture'));
    }

    public function create()
    {
        return view('admin.ceintures.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.ceintures.index');
    }

    public function edit(Ceinture $ceinture)
    {
        return view('admin.ceintures.edit', compact('ceinture'));
    }

    public function update(Request $request, Ceinture $ceinture)
    {
        return redirect()->route('admin.ceintures.show', $ceinture);
    }

    public function destroy(Ceinture $ceinture)
    {
        return redirect()->route('admin.ceintures.index');
    }

    public function attribuer(Request $request)
    {
        return redirect()->route('admin.ceintures.index');
    }
}
