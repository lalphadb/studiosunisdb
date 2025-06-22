<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Middleware\Middleware;  // ✅ CORRECTION IMPORT
use Illuminate\Routing\Controllers\HasMiddleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth', 'verified',
            new Middleware('can:view-users', only: ['index', 'show']),
            new Middleware('can:create-user', only: ['create', 'store']),
            new Middleware('can:edit-user', only: ['edit', 'update']),
            new Middleware('can:delete-user', only: ['destroy']),
        ];
    }

    // ... reste du code identique
