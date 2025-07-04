<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEcoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasRole('superadmin');
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255', 'unique:ecoles,nom'],
            'adresse' => ['required', 'string', 'max:500'],
            'ville' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:50'],
            'code_postal' => ['required', 'string', 'max:10'],
            'telephone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:ecoles,email'],
            'site_web' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif'],
            'contact_principal' => ['required', 'string', 'max:255'],
            'numero_affiliation' => ['nullable', 'string', 'max:50'],
            'type_ecole' => ['required', 'string', 'in:principale,succursale,partenaire'],
            'actif' => ['nullable', 'boolean']
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de l\'école est obligatoire.',
            'nom.unique' => 'Une école avec ce nom existe déjà.',
            'email.unique' => 'Une école avec cette adresse email existe déjà.',
            'logo.image' => 'Le logo doit être une image.',
            'logo.max' => 'Le logo ne peut pas dépasser 2 MB.'
        ];
    }
}
