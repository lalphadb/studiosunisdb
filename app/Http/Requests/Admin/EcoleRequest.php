<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EcoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ecoleId = $this->route('ecole')?->id;
        
        return [
            'nom' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('ecoles', 'code')->ignore($ecoleId)
            ],
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'code_postal' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'site_web' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'proprietaire' => 'nullable|string|max:255',
            'active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de l\'école est obligatoire.',
            'code.required' => 'Le code de l\'école est obligatoire.',
            'code.unique' => 'Ce code d\'école existe déjà.',
            'adresse.required' => 'L\'adresse est obligatoire.',
            'ville.required' => 'La ville est obligatoire.',
            'province.required' => 'La province est obligatoire.',
            'code_postal.required' => 'Le code postal est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'site_web.url' => 'Le site web doit être une URL valide.'
        ];
    }
}
