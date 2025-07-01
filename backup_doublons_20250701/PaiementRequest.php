<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaiementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'montant' => 'required|numeric|min:0.01',
            'statut' => 'required|in:en_attente,paye,rembourse,annule',
            'date_paiement' => 'nullable|date',
            'methode_paiement' => 'nullable|string|max:100',
            'reference_externe' => 'nullable|string|max:255',
            'reference_interne' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
