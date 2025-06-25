<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CeintureAttributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'ceinture_id' => ['required', 'exists:ceintures,id'],
            'date_obtention' => ['required', 'date', 'before_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'valide' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Le membre est obligatoire.',
            'user_id.exists' => 'Ce membre n\'existe pas.',
            'ceinture_id.required' => 'La ceinture est obligatoire.',
            'ceinture_id.exists' => 'Cette ceinture n\'existe pas.',
            'date_obtention.required' => 'La date d\'obtention est obligatoire.',
            'date_obtention.before_or_equal' => 'La date ne peut pas être dans le futur.',
        ];
    }
}
