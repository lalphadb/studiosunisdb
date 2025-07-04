<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeminaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create', \App\Models\Seminaire::class);
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'lieu' => 'nullable|string|max:255',
            'prix' => 'nullable|numeric|min:0',
            'capacite_max' => 'nullable|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du séminaire est obligatoire.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_fin.after' => 'La date de fin doit être postérieure à la date de début.'
        ];
    }
}
