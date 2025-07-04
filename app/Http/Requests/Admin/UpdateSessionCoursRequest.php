<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSessionCoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('update', $this->route('sessionCours'));
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
}
