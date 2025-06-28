<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CeintureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'ceinture_id' => 'required|exists:ceintures,id', 
            'date_obtention' => 'required|date|before_or_equal:today',
            'examinateur' => 'nullable|string|max:255',
            'commentaires' => 'nullable|string|max:1000',
            'valide' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Veuillez sélectionner un utilisateur.',
            'ceinture_id.required' => 'Veuillez sélectionner une ceinture.',
            'date_obtention.required' => 'La date d\'obtention est requise.',
            'date_obtention.before_or_equal' => 'La date ne peut pas être dans le futur.',
        ];
    }
}
