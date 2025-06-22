<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CeintureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Vérifier les permissions via Spatie
        return $this->user()->can('create-ceinture') || 
               $this->user()->can('edit-ceinture');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'couleur' => 'required|string|max:50',
            'ordre' => 'required|integer|min:1|max:20',
            'description' => 'nullable|string|max:1000'
        ];

        // Si c'est une mise à jour, on peut exclure l'ID actuel de l'unicité
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $rules['ordre'] .= ',ordre,' . $this->route('ceinture');
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
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
            'ordre.max' => 'L\'ordre ne peut pas dépasser 20.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'nom' => 'nom de la ceinture',
            'couleur' => 'couleur',
            'ordre' => 'ordre',
            'description' => 'description'
        ];
    }
}
