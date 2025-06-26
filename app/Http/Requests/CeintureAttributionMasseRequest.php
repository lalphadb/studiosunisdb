<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CeintureAttributionMasseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ceinture_id' => ['required', 'exists:ceintures,id'],
            'ceinture_nom' => ['required', 'string'],
            'date_obtention' => ['required', 'date', 'before_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'attributions' => ['required', 'array', 'min:1'],
            'attributions.*.user_id' => ['required', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'ceinture_id.required' => 'La ceinture est obligatoire.',
            'date_obtention.required' => 'La date d\'obtention est obligatoire.',
            'date_obtention.before_or_equal' => 'La date ne peut pas être dans le futur.',
            'attributions.required' => 'Au moins un membre doit être sélectionné.',
            'attributions.min' => 'Au moins un membre doit être sélectionné.',
            'attributions.*.user_id.exists' => 'Un des membres sélectionnés n\'existe pas.',
        ];
    }
}
