<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'ecole_id' => ['required', 'exists:ecoles,id'],
            'role' => ['required', 'string', 'in:membre,instructeur,admin,admin_ecole,superadmin'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'sexe' => ['nullable', 'in:M,F,Autre'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'ville' => ['nullable', 'string', 'max:100'],
            'code_postal' => ['nullable', 'string', 'max:10'],
            'contact_urgence_nom' => ['nullable', 'string', 'max:255'],
            'contact_urgence_telephone' => ['nullable', 'string', 'max:20'],
            'active' => ['boolean'],
            'date_inscription' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            
            // CHAMPS FAMILLE
            'famille_principale_id' => ['nullable', 'exists:users,id'],
            'nom_famille_groupe' => ['nullable', 'string', 'max:255'],
            'contact_principal_famille' => ['nullable', 'string', 'max:255'],
            'telephone_principal_famille' => ['nullable', 'string', 'max:20'],
            'notes_famille' => ['nullable', 'string', 'max:1000'],
        ];

        // Email unique sauf pour l'utilisateur actuel en mode édition
        if ($this->isMethod('POST')) {
            // Création : email unique + mot de passe obligatoire
            $rules['email'][] = 'unique:users,email';
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        } else {
            // Modification : email unique sauf utilisateur actuel + mot de passe optionnel
            $rules['email'][] = 'unique:users,email,' . $this->route('user')?->id;
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom complet est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'ecole_id.required' => 'L\'école est obligatoire.',
            'role.required' => 'Le rôle est obligatoire.',
            'role.in' => 'Le rôle sélectionné n\'est pas autorisé.',
            'password.required' => 'Le mot de passe est obligatoire pour un nouvel utilisateur.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'famille_principale_id.exists' => 'Le chef de famille sélectionné n\'existe pas.',
        ];
    }
}
