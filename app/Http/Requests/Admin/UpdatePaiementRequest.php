<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaiementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('paiement'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'type_paiement' => ['required', 'string', 'in:interac,especes,carte,virement,cheque'],
            'motif' => ['required', 'string', 'in:session_automne,session_hiver,session_printemps,session_ete,seminaire,examen_ceinture,equipement,autre'],
            'description' => ['nullable', 'string', 'max:255'],
            'montant' => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
            'frais' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'email_expediteur' => ['nullable', 'email', 'max:255'],
            'nom_expediteur' => ['nullable', 'string', 'max:255'],
            'reference_interac' => ['nullable', 'string', 'max:255'],
            'message_interac' => ['nullable', 'string'],
            'statut' => ['required', 'string', 'in:en_attente,recu,valide,rembourse,annule'],
            'periode_facturation' => ['nullable', 'string', 'max:255'],
            'notes_admin' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'type_paiement' => 'type de paiement',
            'motif' => 'motif',
            'description' => 'description',
            'montant' => 'montant',
            'frais' => 'frais',
            'email_expediteur' => 'email expéditeur',
            'nom_expediteur' => 'nom expéditeur',
            'reference_interac' => 'référence Interac',
            'message_interac' => 'message Interac',
            'statut' => 'statut',
            'periode_facturation' => 'période de facturation',
            'notes_admin' => 'notes administrateur',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'type_paiement.required' => 'Le type de paiement est obligatoire.',
            'type_paiement.in' => 'Le type de paiement sélectionné n\'est pas valide.',
            'motif.required' => 'Le motif est obligatoire.',
            'motif.in' => 'Le motif sélectionné n\'est pas valide.',
            'montant.required' => 'Le montant est obligatoire.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être d\'au moins 0,01 $.',
            'montant.max' => 'Le montant ne peut pas dépasser 9 999,99 $.',
            'frais.numeric' => 'Les frais doivent être un nombre.',
            'frais.min' => 'Les frais ne peuvent pas être négatifs.',
            'frais.max' => 'Les frais ne peuvent pas dépasser 999,99 $.',
            'email_expediteur.email' => 'L\'email expéditeur doit être valide.',
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Calculer montant_net si frais fourni
        if ($this->has('montant') && $this->has('frais')) {
            $this->merge([
                'montant_net' => $this->montant - ($this->frais ?? 0),
            ]);
        }
    }
}
