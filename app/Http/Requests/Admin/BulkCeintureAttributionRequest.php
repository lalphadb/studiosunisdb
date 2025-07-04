<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BulkCeintureAttributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create_ceintures');
    }

    public function rules(): array
    {
        return [
            'ceinture_id' => ['required', 'exists:ceintures,id'],
            'date_obtention' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'attributions' => ['required', 'array', 'min:1'],
            'attributions.*.user_id' => ['required', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'ceinture_id.required' => 'Veuillez sélectionner une ceinture.',
            'date_obtention.required' => 'La date d\'obtention est obligatoire.',
            'attributions.required' => 'Veuillez sélectionner au moins un membre.',
            'attributions.min' => 'Veuillez sélectionner au moins un membre.',
        ];
    }
}
