<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SeminaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['superadmin', 'admin']);
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'lieu' => 'required|string|max:255',
            'organisateur' => 'required|string|max:255',
            'prix' => 'nullable|numeric|min:0|max:9999.99',
            'places_max' => 'nullable|integer|min:1|max:500',
            'ecole_id' => 'nullable|exists:ecoles,id',
            'active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du séminaire est requis.',
            'date_debut.required' => 'La date de début est requise.',
            'date_debut.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
            'date_fin.required' => 'La date de fin est requise.',
            'date_fin.after_or_equal' => 'La date de fin doit être après la date de début.',
            'lieu.required' => 'Le lieu est requis.',
            'organisateur.required' => 'L\'organisateur est requis.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'places_max.integer' => 'Le nombre de places doit être un entier.',
        ];
    }
}
