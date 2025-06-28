<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PaiementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['superadmin', 'admin']);
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'ecole_id' => 'required|exists:ecoles,id',
            'montant' => 'required|numeric|min:0|max:9999.99',
            'type_paiement' => 'required|in:cotisation,cours,seminaire,equipement,autre',
            'statut' => 'required|in:pending,completed,failed,refunded',
            'methode_paiement' => 'required|in:especes,carte,virement,cheque',
            'date_paiement' => 'nullable|date',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Veuillez sélectionner un utilisateur.',
            'ecole_id.required' => 'Veuillez sélectionner une école.',
            'montant.required' => 'Le montant est requis.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant ne peut pas être négatif.',
            'type_paiement.required' => 'Le type de paiement est requis.',
            'statut.required' => 'Le statut est requis.',
            'methode_paiement.required' => 'La méthode de paiement est requise.',
        ];
    }
}
