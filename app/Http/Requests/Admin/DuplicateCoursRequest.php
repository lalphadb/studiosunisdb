<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DuplicateCoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create', \App\Models\Cours::class);
    }

    public function rules(): array
    {
        return [
            'nombre_copies' => ['required', 'integer', 'min:1', 'max:10'],
            'suffixe' => ['nullable', 'string', 'max:50']
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_copies.required' => 'Le nombre de copies est obligatoire.',
            'nombre_copies.min' => 'Au moins une copie doit être créée.',
            'nombre_copies.max' => 'Maximum 10 copies peuvent être créées à la fois.'
        ];
    }
}
