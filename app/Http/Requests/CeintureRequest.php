<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CeintureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ceintureId = $this->route('ceinture')?->id;

        return [
            'nom' => ['required', 'string', 'max:255'],
            'couleur' => ['required', 'string', 'max:50'],
            'ordre' => [
                'required', 
                'integer', 
                'min:1', 
                'max:100',
                Rule::unique('ceintures')->ignore($ceintureId)
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la ceinture est obligatoire.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'couleur.required' => 'La couleur est obligatoire.',
            'couleur.max' => 'La couleur ne peut pas dépasser 50 caractères.',
            'ordre.required' => 'L\'ordre est obligatoire.',
            'ordre.integer' => 'L\'ordre doit être un nombre entier.',
            'ordre.min' => 'L\'ordre doit être au minimum 1.',
            'ordre.max' => 'L\'ordre ne peut pas dépasser 100.',
            'ordre.unique' => 'Cet ordre est déjà utilisé par une autre ceinture.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
        ];
    }
}
