<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class QuickBulkValidatePaiementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('bulkUpdate', \App\Models\Paiement::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'paiement_ids' => ['required', 'array', 'min:1', 'max:50'],
            'paiement_ids.*' => ['required', 'integer', 'exists:paiements,id'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'paiement_ids' => 'paiements sélectionnés',
            'paiement_ids.*' => 'paiement',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'paiement_ids.required' => 'Vous devez sélectionner au moins un paiement.',
            'paiement_ids.array' => 'Les paiements sélectionnés doivent être un tableau.',
            'paiement_ids.min' => 'Vous devez sélectionner au moins un paiement.',
            'paiement_ids.max' => 'Vous ne pouvez pas sélectionner plus de 50 paiements à la fois.',
            'paiement_ids.*.required' => 'Chaque paiement sélectionné est requis.',
            'paiement_ids.*.integer' => 'L\'ID du paiement doit être un entier.',
            'paiement_ids.*.exists' => 'Un ou plusieurs paiements sélectionnés n\'existent pas.',
        ];
    }
}
