<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin_ecole', 'admin']);
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => [
                'required', 
                'email', 
                'max:255',
                Rule::unique('users')->ignore($userId)
            ],
            'password' => [
                $isUpdate ? 'nullable' : 'required',
                'string',
                'min:8',
                'confirmed'
            ],
            'ecole_id' => [
                'required',
                'exists:ecoles,id',
                function ($attribute, $value, $fail) {
                    if (!auth()->user()->hasRole('superadmin')) {
                        $allowedEcoles = collect([auth()->user()->ecole_id]);
                        if (!$allowedEcoles->contains($value)) {
                            $fail('École non autorisée.');
                        }
                    }
                }
            ],
            'role' => [
                'nullable',
                'string',
                Rule::in($this->getAvailableRoles())
            ],
            'telephone' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'sexe' => ['nullable', Rule::in(['M', 'F', 'Autre'])],
            'contact_urgence_nom' => ['nullable', 'string', 'max:255'],
            'contact_urgence_telephone' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'active' => ['boolean'],
            
            // Champs famille
            'nom_famille' => ['nullable', 'string', 'max:255'],
            'contact_principal_famille' => ['nullable', 'string', 'max:255'],
            'telephone_principal_famille' => ['nullable', 'string', 'max:20'],
            'notes_famille' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'name.min' => 'Le nom doit contenir au moins 2 caractères.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'ecole_id.required' => 'L\'école est obligatoire.',
            'ecole_id.exists' => 'L\'école sélectionnée n\'existe pas.',
            'role.in' => 'Le rôle sélectionné n\'est pas valide.',
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'sexe.in' => 'Le sexe sélectionné n\'est pas valide.',
        ];
    }

    protected function getAvailableRoles(): array
    {
        if (auth()->user()->hasRole('superadmin')) {
            return ['membre', 'instructeur', 'admin', 'admin_ecole', 'superadmin'];
        }

        if (auth()->user()->hasRole('admin_ecole')) {
            return ['membre', 'instructeur', 'admin'];
        }

        return ['membre', 'instructeur'];
    }
}
