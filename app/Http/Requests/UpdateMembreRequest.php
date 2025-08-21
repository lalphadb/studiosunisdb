<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMembreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('membre'));
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:membres,email,' . $this->route('membre')->id,
            'telephone' => 'nullable|string|max:30',
            'date_naissance' => 'nullable|date',
            'statut' => 'required|in:actif,inactif,suspendu',
            'ceinture_id' => 'nullable|exists:ceintures,id',
        ];
    }
}
