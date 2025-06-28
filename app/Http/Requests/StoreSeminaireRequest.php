<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeminaireRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Seminaire::class);
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
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'lieu' => 'nullable|string|max:255',
            'prix' => 'nullable|numeric|min:0',
            'capacite_max' => 'nullable|integer|min:1',
            'instructeur' => 'nullable|string|max:255',
            'niveau_requis' => 'nullable|in:debutant,intermediaire,avance,tous_niveaux',
            'statut' => 'required|in:planifie,encours,termine,annule',
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
            'nom.required' => 'Le nom du séminaire est obligatoire.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.after_or_equal' => 'La date de fin doit être après ou égale à la date de début.',
            'lieu.max' => 'Le lieu ne peut pas dépasser 255 caractères.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix ne peut pas être négatif.',
            'capacite_max.min' => 'La capacité maximale doit être d\'au moins 1 personne.',
            'niveau_requis.in' => 'Le niveau requis doit être : débutant, intermédiaire, avancé ou tous niveaux.',
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut doit être : planifié, en cours, terminé ou annulé.',
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
    }
}
