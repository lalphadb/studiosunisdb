<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Afficher le formulaire d'inscription
     */
    public function create(): View
    {
        $ecoles = Ecole::where('active', true)->orderBy('nom')->get();
        return view('auth.register', compact('ecoles'));
    }

    /**
     * Traiter l'inscription
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'ecole_id' => ['required', 'exists:ecoles,id'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'date_naissance' => ['required', 'date', 'before:today'],
            'sexe' => ['required', 'in:M,F,Autre'],
            'adresse' => ['required', 'string', 'max:255'],
            'ville' => ['required', 'string', 'max:100'],
            'code_postal' => ['required', 'string', 'max:10'],
            'contact_urgence_nom' => ['required', 'string', 'max:255'],
            'contact_urgence_telephone' => ['required', 'string', 'max:20'],
            'accepte_loi25' => ['accepted'],
            'accepte_conditions' => ['accepted'],
        ], [
            'accepte_loi25.accepted' => 'Vous devez accepter la politique de confidentialité (Loi 25).',
            'accepte_conditions.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ecole_id' => $request->ecole_id,
            'telephone' => $request->telephone,
            'date_naissance' => $request->date_naissance,
            'sexe' => $request->sexe,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'code_postal' => $request->code_postal,
            'contact_urgence_nom' => $request->contact_urgence_nom,
            'contact_urgence_telephone' => $request->contact_urgence_telephone,
            'active' => true,
            'date_inscription' => now(),
        ]);

        // Assigner le rôle membre par défaut
        $user->assignRole('membre');

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Votre compte a été créé avec succès !');
    }
}
