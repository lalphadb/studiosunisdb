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
            'examinateur' => ['nullable', 'string', 'max:255'],
            'commentaires' => ['nullable', 'string', 'max:1000'],
            'valide' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Le membre est obligatoire.',
            'user_id.exists' => 'Le membre sélectionné n\'existe pas.',
            'ceinture_id.required' => 'La ceinture est obligatoire.',
            'ceinture_id.exists' => 'La ceinture sélectionnée n\'existe pas.',
            'date_obtention.required' => 'La date d\'obtention est obligatoire.',
            'date_obtention.before_or_equal' => 'La date d\'obtention ne peut pas être dans le futur.',
            'examinateur.max' => 'Le nom de l\'examinateur ne peut pas dépasser 255 caractères.',
            'commentaires.max' => 'Les commentaires ne peuvent pas dépasser 1000 caractères.',
        ];
    }
}
