<?php

namespace App\Http\Requests;

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
        $userId = $this->route('user')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => $this->isMethod('POST') ? ['required', 'string', 'min:8'] : ['nullable', 'string', 'min:8'],
            'ecole_id' => ['nullable', 'exists:ecoles,id'],
            'role' => ['required', 'string', Rule::in($this->getAvailableRoles())],
            'telephone' => ['nullable', 'string', 'max:20'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'sexe' => ['nullable', Rule::in(['M', 'F', 'Autre'])],
            'adresse' => ['nullable', 'string', 'max:500'],
            'ville' => ['nullable', 'string', 'max:100'],
            'code_postal' => ['nullable', 'string', 'max:10'],
            'contact_urgence_nom' => ['nullable', 'string', 'max:255'],
            'contact_urgence_telephone' => ['nullable', 'string', 'max:20'],
            'active' => ['boolean'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'role.required' => 'Le rôle est obligatoire.',
            'role.in' => 'Le rôle sélectionné n\'est pas valide.',
            'ecole_id.exists' => 'L\'école sélectionnée n\'existe pas.',
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'sexe.in' => 'Le sexe sélectionné n\'est pas valide.',
        ];
    }

    private function getAvailableRoles(): array
    {
        $user = auth()->user();
        
        if ($user->hasRole('superadmin')) {
            return ['user', 'instructeur', 'admin', 'admin_ecole', 'superadmin'];
        } elseif ($user->hasRole('admin_ecole')) {
            return ['user', 'instructeur', 'admin'];
        } elseif ($user->hasRole('admin')) {
            return ['user', 'instructeur'];
        }

        return ['user'];
    }
}
