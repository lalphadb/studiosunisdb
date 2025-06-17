<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EcoleRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage-ecoles') || auth()->user()->hasRole('superadmin');
    }

    public function rules()
    {
        $ecoleId = $this->route('ecole') ? $this->route('ecole')->id : null;

        return [
            'nom' => 'required|string|max:255|unique:ecoles,nom,'.$ecoleId,
            'adresse' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'province' => 'required|string|max:50',
            'code_postal' => 'required|string|max:10',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:ecoles,email,'.$ecoleId,
            'site_web' => 'nullable|url|max:255',
            'directeur' => 'required|string|max:255',
            'capacite_max' => 'required|integer|min:10|max:500',
            'statut' => 'required|in:actif,inactif',
            'description' => 'nullable|string|max:1000',
            'image_url' => 'nullable|url|max:500',
        ];
    }

    public function messages()
    {
        return [
            'nom.required' => 'Le nom de l\'école est obligatoire.',
            'nom.unique' => 'Ce nom d\'école existe déjà.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'capacite_max.min' => 'La capacité doit être d\'au moins 10 personnes.',
            'capacite_max.max' => 'La capacité ne peut dépasser 500 personnes.',
        ];
    }
}
