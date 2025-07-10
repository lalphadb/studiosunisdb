<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PaiementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'montant' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'statut' => 'required|in:en_attente,valide,refuse',
            'date_paiement' => 'nullable|date'
        ];
    }
}
