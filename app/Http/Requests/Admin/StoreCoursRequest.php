<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create', \App\Models\Cours::class);
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'niveau' => ['required', 'in:debutant,intermediaire,avance,tous_niveaux'],
            'duree_minutes' => ['required', 'integer', 'min:15', 'max:180'],
            'instructeur' => ['nullable', 'string', 'max:255'],
            'capacite_max' => ['nullable', 'integer', 'min:1', 'max:50'],
            'prix' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'active' => ['nullable', 'boolean'],
            'ecole_id' => ['nullable', 'integer', 'exists:ecoles,id']
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du cours est obligatoire.',
            'niveau.required' => 'Le niveau est obligatoire.',
            'duree_minutes.required' => 'La durée est obligatoire.',
            'duree_minutes.min' => 'La durée minimum est de 15 minutes.',
            'duree_minutes.max' => 'La durée maximum est de 180 minutes.'
        ];
    }
}
