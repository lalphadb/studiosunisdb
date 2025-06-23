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
        $userId = $this->route('user') ? $this->route('user')->id : null;
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'email', 
                'max:255',
                Rule::unique('users')->ignore($userId)
            ],
            'telephone' => ['nullable', 'string', 'max:20'],
            'date_naissance' => ['nullable', 'date'],
            'sexe' => ['nullable', 'in:M,F,Autre'],
            'adresse' => ['nullable', 'string'],
            'ville' => ['nullable', 'string', 'max:255'],
            'code_postal' => ['nullable', 'string', 'max:10'],
            'contact_urgence_nom' => ['nullable', 'string', 'max:255'],
            'contact_urgence_telephone' => ['nullable', 'string', 'max:20'],
            'famille_principale_id' => ['nullable', 'exists:users,id'],
            'password' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'string',
                'min:8',
                'confirmed'
            ],
            'role' => [
                'required',
                'string',
                Rule::in($this->getAvailableRoles())
            ],
            'ecole_id' => ['required', 'exists:ecoles,id'],
            'active' => ['boolean'],
            'date_inscription' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'role.required' => 'Le rôle est obligatoire.',
            'role.in' => 'Le rôle sélectionné n\'est pas valide.',
            'ecole_id.required' => 'L\'école est obligatoire.',
            'ecole_id.exists' => 'L\'école sélectionnée n\'existe pas.',
            'famille_principale_id.exists' => 'Le chef de famille sélectionné n\'existe pas.',
            'date_naissance.date' => 'La date de naissance doit être une date valide.',
            'sexe.in' => 'Le sexe doit être M, F ou Autre.',
        ];
    }

    /**
     * Retourne les rôles disponibles selon le rôle de l'utilisateur connecté
     */
    private function getAvailableRoles(): array
    {
        $user = auth()->user();
        
        if ($user->hasRole('superadmin')) {
            return ['membre', 'instructeur', 'admin', 'admin_ecole', 'superadmin'];
        } elseif ($user->hasRole('admin_ecole')) {
            return ['membre', 'instructeur', 'admin'];
        } elseif ($user->hasRole('admin')) {
            return ['membre', 'instructeur'];
        }
        
        return [];
    }

    protected function prepareForValidation()
    {
        // Convertir les valeurs vides en null
        if ($this->famille_principale_id === '') {
            $this->merge(['famille_principale_id' => null]);
        }
        
        if ($this->active === null) {
            $this->merge(['active' => false]);
        } elseif ($this->active === 'on' || $this->active === '1') {
            $this->merge(['active' => true]);
        }
    }
}
