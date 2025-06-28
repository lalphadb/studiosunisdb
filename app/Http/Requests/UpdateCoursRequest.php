<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoursRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('cour'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'niveau' => 'nullable|in:debutant,intermediaire,avance,tous_niveaux',
            'capacite_max' => 'nullable|integer|min:1',
            'prix' => 'nullable|numeric|min:0',
            'duree_minutes' => 'nullable|integer|min:1',
            'instructeur' => 'nullable|string|max:255',
            'active' => 'boolean',
        ];

        // Règle ecole_id selon rôle (XML requirement)
        if ($this->user()->hasRole('superadmin')) {
            $rules['ecole_id'] = 'required|exists:ecoles,id';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du cours est obligatoire.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'niveau.in' => 'Le niveau doit être : débutant, intermédiaire, avancé ou tous niveaux.',
            'capacite_max.min' => 'La capacité maximale doit être d\'au moins 1 personne.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix ne peut pas être négatif.',
            'duree_minutes.integer' => 'La durée doit être en minutes (nombre entier).',
            'duree_minutes.min' => 'La durée doit être d\'au moins 1 minute.',
            'ecole_id.required' => 'L\'école est obligatoire pour un superadmin.',
            'ecole_id.exists' => 'L\'école sélectionnée n\'existe pas.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Auto-assign ecole_id for admin_ecole (XML requirement)
        if ($this->user()->hasRole('admin_ecole')) {
            $this->merge([
                'ecole_id' => $this->user()->ecole_id,
            ]);
        }

        // Convert checkbox to boolean
        $this->merge([
            'active' => $this->boolean('active', true),
        ]);
    }
}
