<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeminaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('seminaire'));
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'type' => ['required', 'string', 'in:technique,kata,competition,arbitrage,grade,formation'],
            'niveau_requis' => ['required', 'string', 'in:debutant,intermediaire,avance,tous_niveaux'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['nullable', 'date', 'after:date_debut'],
            'duree' => ['nullable', 'integer', 'min:30', 'max:480'],
            'lieu' => ['required', 'string', 'max:255'],
            'adresse_lieu' => ['nullable', 'string', 'max:255'],
            'ville_lieu' => ['nullable', 'string', 'max:100'],
            'instructeur' => ['required', 'string', 'max:255'],
            'max_participants' => ['nullable', 'integer', 'min:1', 'max:500'],
            'cout' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'statut' => ['required', 'string', 'in:planifie,ouvert,complet,termine,annule'],
            'inscription_ouverte' => ['boolean'],
            'certificat' => ['boolean'],
            'objectifs' => ['nullable', 'string', 'max:2000'],
            'prerequis' => ['nullable', 'string', 'max:1000'],
            'materiel_requis' => ['nullable', 'string', 'max:1000'],
            'notes_admin' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'titre' => 'titre',
            'date_debut' => 'date de début',
            'date_fin' => 'date de fin',
            'niveau_requis' => 'niveau requis',
            'max_participants' => 'nombre maximum de participants',
        ];
    }
}
