<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('manage-users');
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        
        return [
            'name' => ['required', 'string', 'max:191'],
            'email' => [
                'required', 
                'email', 
                'max:191',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'password' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'string',
                'min:8',
                'confirmed'
            ],
            'ecole_id' => [
                'required',
                'exists:ecoles,id',
                function ($attribute, $value, $fail) {
                    // Multi-tenant: admin d'école ne peut assigner que son école
                    if (auth()->user()->ecole_id && $value != auth()->user()->ecole_id) {
                        $fail('Vous ne pouvez assigner que votre école.');
                    }
                }
            ],
            'telephone' => ['nullable', 'string', 'max:191'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'sexe' => ['nullable', Rule::in(['M', 'F', 'Autre'])],
            'adresse' => ['nullable', 'string', 'max:500'],
            'ville' => ['nullable', 'string', 'max:191'],
            'code_postal' => ['nullable', 'string', 'max:10'],
            'contact_urgence_nom' => ['nullable', 'string', 'max:191'],
            'contact_urgence_telephone' => ['nullable', 'string', 'max:191'],
            'active' => ['boolean'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'role' => ['nullable', 'exists:roles,name']
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
            'ecole_id.required' => 'L\'école est obligatoire.',
            'ecole_id.exists' => 'L\'école sélectionnée n\'existe pas.',
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Nettoyer les données
        if ($this->filled('telephone')) {
            $this->merge([
                'telephone' => preg_replace('/[^\d\+\-\(\)\s]/', '', $this->telephone)
            ]);
        }

        // Actif par défaut
        if (!$this->filled('active')) {
            $this->merge(['active' => true]);
        }
    }
}
