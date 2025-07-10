<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        
        Log::info('Tentative de connexion', [
            'email' => $credentials['email'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Vérifier si l'utilisateur existe
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            Log::warning('Utilisateur non trouvé', ['email' => $credentials['email']]);
            throw ValidationException::withMessages([
                'email' => ['Ces identifiants ne correspondent pas à nos enregistrements.'],
            ]);
        }

        Log::info('Utilisateur trouvé', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'ecole_id' => $user->ecole_id,
            'email_verified' => $user->email_verified_at ? true : false
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            Log::info('Connexion réussie', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'roles' => Auth::user()->getRoleNames()->toArray()
            ]);

            return redirect()->intended('/admin/dashboard');
        }

        Log::warning('Échec de connexion', [
            'email' => $credentials['email'],
            'password_check' => \Hash::check($credentials['password'], $user->password)
        ]);

        throw ValidationException::withMessages([
            'email' => ['Ces identifiants ne correspondent pas à nos enregistrements.'],
        ]);
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        Log::info('Déconnexion', ['user_id' => $userId]);
        
        return redirect('/login');
    }
}
