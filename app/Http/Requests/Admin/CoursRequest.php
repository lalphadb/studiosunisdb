<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CoursRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasAnyPermission(['create-cours', 'update-cours']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $coursId = $this->route('cour') ? $this->route('cour')->id : null;

        return [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'niveau' => 'nullable|string|max:100',
            'ecole_id' => 'required|exists:ecoles,id',
            'capacite_max_defaut' => 'nullable|integer|min:1|max:100',
            'prix_defaut' => 'nullable|numeric|min:0|max:9999.99',
            'instructeur_defaut' => 'nullable|string|max:255',
            'active' => 'boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du cours est obligatoire.',
            'nom.max' => 'Le nom du cours ne peut pas dépasser 255 caractères.',
            'ecole_id.required' => 'L\'école est obligatoire.',
            'ecole_id.exists' => 'L\'école sélectionnée n\'existe pas.',
            'capacite_max_defaut.integer' => 'La capacité doit être un nombre entier.',
            'capacite_max_defaut.min' => 'La capacité doit être d\'au moins 1 personne.',
            'capacite_max_defaut.max' => 'La capacité ne peut pas dépasser 100 personnes.',
            'prix_defaut.numeric' => 'Le prix doit être un nombre.',
            'prix_defaut.min' => 'Le prix ne peut pas être négatif.',
            'prix_defaut.max' => 'Le prix ne peut pas dépasser 9999.99.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Force ecole_id pour admin_ecole (sécurité multi-tenant)
        if (!Auth::user()->hasRole('super_admin')) {
            $this->merge([
                'ecole_id' => Auth::user()->ecole_id
            ]);
        }

        // Valeurs par défaut
        if (!$this->has('active')) {
            $this->merge(['active' => true]);
        }

        // Nettoyer les valeurs vides
        if ($this->capacite_max_defaut === '') {
            $this->merge(['capacite_max_defaut' => null]);
        }

        if ($this->prix_defaut === '') {
            $this->merge(['prix_defaut' => null]);
        }
    }
}
