<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class StudiosDBAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.studiosdb-login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        
        Log::info('StudiosDB - Tentative de connexion', [
            'email' => $credentials['email'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        // Vérifier si l'utilisateur existe
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            Log::warning('StudiosDB - Utilisateur non trouvé', [
                'email' => $credentials['email'],
                'ip' => $request->ip()
            ]);
            
            return redirect()->back()
                ->withErrors(['email' => 'Ces identifiants ne correspondent pas à nos enregistrements.'])
                ->withInput($request->only('email'));
        }

        // Log des détails de l'utilisateur
        Log::info('StudiosDB - Utilisateur trouvé', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'ecole_id' => $user->ecole_id,
            'email_verified' => $user->email_verified_at ? true : false,
            'has_password' => $user->password ? true : false,
            'roles' => $user->getRoleNames()->toArray()
        ]);

        // Tentative d'authentification
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            Log::info('StudiosDB - Connexion réussie', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'user_name' => Auth::user()->name,
                'ecole_id' => Auth::user()->ecole_id,
                'roles' => Auth::user()->getRoleNames()->toArray(),
                'permissions' => Auth::user()->getAllPermissions()->pluck('name')->toArray(),
                'session_id' => session()->getId()
            ]);

            // Redirection basée sur le rôle
            $redirectUrl = $this->getRedirectUrl();
            
            return redirect()->intended($redirectUrl);
        }

        Log::warning('StudiosDB - Échec de connexion', [
            'email' => $credentials['email'],
            'user_exists' => true,
            'password_check' => \Hash::check($credentials['password'], $user->password),
            'ip' => $request->ip()
        ]);

        return redirect()->back()
            ->withErrors(['email' => 'Ces identifiants ne correspondent pas à nos enregistrements.'])
            ->withInput($request->only('email'));
    }

    private function getRedirectUrl()
    {
        $user = Auth::user();
        
        if ($user->hasRole('superadmin')) {
            return '/admin/dashboard';
        } elseif ($user->hasRole('admin_ecole')) {
            return '/admin/dashboard';
        } elseif ($user->hasRole('instructeur')) {
            return '/instructeur/dashboard';
        } else {
            return '/dashboard';
        }
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        $userEmail = Auth::user()->email;
        
        Log::info('StudiosDB - Déconnexion', [
            'user_id' => $userId,
            'user_email' => $userEmail,
            'session_id' => session()->getId()
        ]);
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('message', 'Vous avez été déconnecté avec succès.');
    }
}
