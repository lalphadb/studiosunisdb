<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SeminaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'lieu' => 'required|string|max:255',
            'instructeur' => 'nullable|string|max:255',
            'prix' => 'nullable|numeric|min:0',
            'capacite_max' => 'nullable|integer|min:1'
        ];
    }
}
