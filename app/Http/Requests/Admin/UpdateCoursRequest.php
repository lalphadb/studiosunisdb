<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('update', $this->route('cours'));
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
            'active' => ['nullable', 'boolean']
        ];
    }
}
