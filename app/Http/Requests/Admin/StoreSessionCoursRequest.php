<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSessionCoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create', \App\Models\SessionCours::class);
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'date_limite_inscription' => 'nullable|date',
            'actif' => 'boolean',
            'inscriptions_ouvertes' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la session est obligatoire.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_fin.after' => 'La date de fin doit être postérieure à la date de début.'
        ];
    }
}
