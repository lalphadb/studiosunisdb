<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InscriptionSeminaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['superadmin', 'admin']);
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'seminaire_id' => 'required|exists:seminaires,id',
            'statut' => 'nullable|in:inscrit,present,absent,annule',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Veuillez sélectionner un utilisateur.',
            'user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas.',
            'seminaire_id.required' => 'Le séminaire est requis.',
            'seminaire_id.exists' => 'Le séminaire sélectionné n\'existe pas.',
            'statut.in' => 'Le statut doit être : inscrit, présent, absent ou annulé.',
        ];
    }
}
