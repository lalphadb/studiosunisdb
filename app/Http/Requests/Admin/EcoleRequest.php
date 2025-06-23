<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EcoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-ecole') || $this->user()->can('edit-ecole');
    }

    public function rules(): array
    {
        $ecoleId = $this->route('ecole') ? $this->route('ecole')->id : null;
        
        return [
            'nom' => ['required', 'string', 'max:255'],
            'code' => [
                'required', 
                'string', 
                'max:10',
                Rule::unique('ecoles', 'code')->ignore($ecoleId)
            ],
            'adresse' => ['required', 'string', 'max:500'],
            'ville' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:50'],
            'code_postal' => ['required', 'string', 'regex:/^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/'],
            'telephone' => ['nullable', 'string', 'regex:/^[\d\-\(\)\ \+\.]+$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'site_web' => ['nullable', 'url', 'max:255'],
            'directeur' => ['nullable', 'string', 'max:255'],
            'capacite_max' => ['nullable', 'integer', 'min:10', 'max:1000'],
            'description' => ['nullable', 'string', 'max:1000'],
            'active' => ['required', 'boolean'],
            'statut' => ['nullable', 'in:actif,inactif'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de l\'école est obligatoire.',
            'code.required' => 'Le code de l\'école est obligatoire.',
            'code.unique' => 'Ce code est déjà utilisé par une autre école.',
            'adresse.required' => 'L\'adresse est obligatoire.',
            'ville.required' => 'La ville est obligatoire.',
            'province.required' => 'La province est obligatoire.',
            'code_postal.required' => 'Le code postal est obligatoire.',
            'code_postal.regex' => 'Le format du code postal est invalide (ex: H1H 1H1).',
            'telephone.regex' => 'Le format du téléphone est invalide.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'site_web.url' => 'L\'URL du site web n\'est pas valide.',
            'capacite_max.min' => 'La capacité doit être d\'au moins 10 personnes.',
            'capacite_max.max' => 'La capacité ne peut pas dépasser 1000 personnes.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('code_postal')) {
            $this->merge([
                'code_postal' => strtoupper(str_replace(' ', '', $this->code_postal))
            ]);
        }

        if ($this->has('active')) {
            $this->merge([
                'active' => filter_var($this->active, FILTER_VALIDATE_BOOLEAN)
            ]);
        }
    }
}
