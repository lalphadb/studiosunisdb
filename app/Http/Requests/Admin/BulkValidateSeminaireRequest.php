<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BulkValidateSeminaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('bulkUpdate', \App\Models\Seminaire::class);
    }

    public function rules(): array
    {
        return [
            'inscription_ids' => ['required', 'array', 'min:1', 'max:100'],
            'inscription_ids.*' => ['required', 'integer', 'exists:inscriptions_seminaires,id'],
            'action' => ['required', 'string', 'in:marquer_present,marquer_absent,confirmer_inscription,annuler_inscription,attribuer_certificat,supprimer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'inscription_ids' => 'inscriptions sélectionnées',
            'inscription_ids.*' => 'inscription',
            'action' => 'action',
        ];
    }

    public function messages(): array
    {
        return [
            'inscription_ids.required' => 'Vous devez sélectionner au moins une inscription.',
            'inscription_ids.min' => 'Vous devez sélectionner au moins une inscription.',
            'inscription_ids.max' => 'Vous ne pouvez pas sélectionner plus de 100 inscriptions à la fois.',
            'action.required' => 'Vous devez choisir une action.',
            'action.in' => 'L\'action sélectionnée n\'est pas valide.',
        ];
    }
}
