<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoursHoraireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('update', $this->route('coursHoraire'));
    }

    public function rules(): array
    {
        return [
            'cours_id' => 'required|exists:cours,id',
            'session_id' => 'nullable|exists:sessions_cours,id',
            'jour_semaine' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'salle' => 'nullable|string|max:255',
            'instructeur_affecte' => 'nullable|string|max:255',
            'capacite_max' => 'nullable|integer|min:1|max:100',
            'prix' => 'nullable|numeric|min:0|max:999999.99',
            'nom_affiche' => 'nullable|string|max:255',
            'active' => 'boolean'
        ];
    }
}
