<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MembreController extends Controller
{
    /**
     * Afficher le profil du membre
     */
    public function profil()
    {
        $user = auth()->user();
        return view('membre.profil', compact('user'));
    }

    /**
     * Formulaire de modification du profil
     */
    public function edit()
    {
        $user = auth()->user();
        return view('membre.profil-edit', compact('user'));
    }

    /**
     * Mettre à jour le profil (MÊMES DONNÉES que l'admin)
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'telephone' => ['nullable', 'string', 'max:20'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'sexe' => ['nullable', 'in:M,F,Autre'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'ville' => ['nullable', 'string', 'max:100'],
            'code_postal' => ['nullable', 'string', 'max:10'],
            'contact_urgence_nom' => ['nullable', 'string', 'max:255'],
            'contact_urgence_telephone' => ['nullable', 'string', 'max:20'],
            'nom_famille_groupe' => ['nullable', 'string', 'max:255'],
            'contact_principal_famille' => ['nullable', 'string', 'max:255'],
            'telephone_principal_famille' => ['nullable', 'string', 'max:20'],
            'notes_famille' => ['nullable', 'string', 'max:1000'],
        ]);

        // Mettre à jour les MÊMES champs que l'admin peut modifier
        $user->update($validated);

        return redirect()
            ->route('membre.profil')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Changer le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('membre.profil')
            ->with('success', 'Mot de passe modifié avec succès.');
    }
}
