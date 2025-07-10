<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Redirection après login selon le rôle
     */
    public function redirectTo()
    {
        $user = Auth::user();
        
        if ($user->hasAnyRole(['superadmin', 'admin_ecole'])) {
            return '/admin/dashboard';
        }
        
        return '/dashboard';
    }
}
