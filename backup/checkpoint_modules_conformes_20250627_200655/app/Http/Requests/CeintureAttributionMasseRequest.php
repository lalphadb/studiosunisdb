<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CeintureAttributionMasseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin_ecole', 'instructeur']);
    }

    public function rules(): array
    {
        return [
            'ceinture_id' => 'required|exists:ceintures,id',
            'ceinture_nom' => 'required|string',
            'date_obtention' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
            'attributions' => 'required|array|min:1',
            'attributions.*.user_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'ceinture_id.required' => 'Vous devez sélectionner une ceinture.',
            'date_obtention.required' => 'La date d\'obtention est obligatoire.',
            'date_obtention.before_or_equal' => 'La date ne peut pas être dans le futur.',
            'attributions.required' => 'Vous devez sélectionner au moins un membre.',
            'attributions.min' => 'Vous devez sélectionner au moins un membre.',
        ];
    }
}
