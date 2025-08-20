<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMembreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Membre::class);
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:membres,email',
            'telephone' => 'nullable|string|max:30',
            'date_naissance' => 'nullable|date',
            'statut' => 'required|in:actif,inactif,suspendu',
            'ceinture_id' => 'nullable|exists:ceintures,id',
        ];
    }
}
