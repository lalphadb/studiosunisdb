<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StorePaiementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', \App\Models\Paiement::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'type_paiement' => ['required', 'string', 'in:interac,comptant,cheque,carte_credit,virement,autre'],
            'motif' => ['required', 'string', 'in:session_automne,session_hiver,session_printemps,session_ete,seminaire,examen_ceinture,equipement,autre'],
            'description' => ['required', 'string', 'max:500'],
            'montant' => ['required', 'numeric', 'min:0', 'max:10000'],
            'frais' => ['nullable', 'numeric', 'min:0', 'max:500'],
            'email_expediteur' => ['nullable', 'email', 'max:255'],
            'nom_expediteur' => ['nullable', 'string', 'max:255'],
            'reference_interac' => ['nullable', 'string', 'max:255'],
            'message_interac' => ['nullable', 'string', 'max:500'],
            'statut' => ['required', 'string', 'in:en_attente,recu,valide'],
            'date_facture' => ['nullable', 'date'],
            'date_echeance' => ['nullable', 'date'],
            'notes_admin' => ['nullable', 'string', 'max:1000'],
            'reference_interne' => ['nullable', 'string', 'max:100', 'unique:paiements,reference_interne']
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Veuillez sélectionner un membre.',
            'user_id.exists' => 'Le membre sélectionné n\'existe pas.',
            'montant.required' => 'Le montant est obligatoire.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être positif.',
            'montant.max' => 'Le montant ne peut pas dépasser 10 000$.',
            'type_paiement.required' => 'Le type de paiement est obligatoire.',
            'motif.required' => 'Le motif est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'email_expediteur.email' => 'L\'adresse email n\'est pas valide.',
            'reference_interne.unique' => 'Cette référence existe déjà.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Multi-tenant: vérifier que l'utilisateur appartient à la bonne école
        if ($this->user_id && auth()->user()->hasRole('admin_ecole')) {
            $user = \App\Models\User::find($this->user_id);
            if ($user && $user->ecole_id !== auth()->user()->ecole_id) {
                abort(403, 'Utilisateur non autorisé pour cette école');
            }
        }
    }
}
