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
        // Récupérer les rôles disponibles directement ici
        $availableRoles = $this->getAvailableRolesForValidation();
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'ecole_id' => ['required', 'exists:ecoles,id'],
            'role' => ['required', 'string', Rule::in(array_keys($availableRoles))],
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
            'famille_principale_id' => ['nullable', 'exists:users,id'],
        ];

        // Email unique sauf pour l'utilisateur actuel en mode édition
        if ($this->isMethod('POST')) {
            $rules['email'][] = 'unique:users,email';
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        } else {
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
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }

    /**
     * Version simple pour la validation
     */
    private function getAvailableRolesForValidation(): array
    {
        $user = auth()->user();
        
        if (!$user) {
            return ['membre' => 'Membre'];
        }
        
        if ($user->hasRole('superadmin')) {
            return [
                'membre' => 'Membre',
                'instructeur' => 'Instructeur', 
                'admin' => 'Admin',
                'admin_ecole' => 'Admin École',
                'superadmin' => 'Superadmin'
            ];
        } elseif ($user->hasRole('admin_ecole')) {
            return [
                'membre' => 'Membre',
                'instructeur' => 'Instructeur',
                'admin' => 'Admin'
            ];
        } elseif ($user->hasRole('admin')) {
            return [
                'membre' => 'Membre',
                'instructeur' => 'Instructeur'
            ];
        }
        
        return ['membre' => 'Membre'];
    }
}
