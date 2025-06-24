<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'ecole_id' => ['required', 'exists:ecoles,id'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'date_naissance' => ['nullable', 'date'],
            'sexe' => ['nullable', 'string', Rule::in(['M', 'F', 'Autre'])],
            'adresse' => ['nullable', 'string', 'max:500'],
            'ville' => ['nullable', 'string', 'max:100'],
            'code_postal' => ['nullable', 'string', 'max:10'],
            'contact_urgence_nom' => ['nullable', 'string', 'max:255'],
            'contact_urgence_telephone' => ['nullable', 'string', 'max:20'],
            'active' => ['boolean'],
            'date_inscription' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'famille_principale_id' => ['nullable', 'exists:users,id'],
            'role' => ['required', 'string', Rule::in($this->getAvailableRoles())],
        ];

        // Pour la création, mot de passe requis
        if ($this->isMethod('POST')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }
        
        // Pour la modification, mot de passe optionnel
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'ecole_id.required' => 'L\'école est obligatoire.',
            'ecole_id.exists' => 'L\'école sélectionnée n\'existe pas.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'role.required' => 'Le rôle est obligatoire.',
            'role.in' => 'Le rôle sélectionné n\'est pas valide.',
        ];
    }

    private function getAvailableRoles(): array
    {
        $user = Auth::user();
        
        if (!$user) {
            return [];
        }

        if ($user->hasRole('super-admin')) {
            return ['utilisateur', 'instructeur', 'admin', 'admin-ecole', 'super-admin'];
        } elseif ($user->hasRole('admin-ecole')) {
            return ['utilisateur', 'instructeur', 'admin'];
        } elseif ($user->hasRole('admin')) {
            return ['utilisateur', 'instructeur'];
        }
        
        return [];
    }
}
