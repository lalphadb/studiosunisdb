<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BulkValidatePaiementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('edit_paiements');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'paiement_ids' => ['required', 'array', 'min:1', 'max:50'],
            'paiement_ids.*' => ['required', 'integer', 'exists:paiements,id'],
            'action' => ['required', 'string', 'in:valider,marquer_recu,attente,supprimer']
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'paiement_ids.required' => 'Veuillez sélectionner au moins un paiement.',
            'paiement_ids.min' => 'Veuillez sélectionner au moins un paiement.',
            'paiement_ids.max' => 'Vous ne pouvez pas traiter plus de 50 paiements à la fois.',
            'action.required' => 'Veuillez sélectionner une action.',
            'action.in' => 'Action non valide.'
        ];
    }
}
