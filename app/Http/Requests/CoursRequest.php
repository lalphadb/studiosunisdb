<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CoursRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->can('create-cours') || Auth::user()->can('edit-cours');
    }

    public function rules()
    {
        $rules = [
            'ecole_id' => 'required|exists:ecoles,id',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_cours' => 'required|in:regulier,specialise,competition,examen',
            'niveau_requis' => 'nullable|string|max:100',
            'age_min' => 'required|integer|min:3|max:99',
            'age_max' => 'required|integer|min:3|max:99|gte:age_min',
            'capacite_max' => 'required|integer|min:1|max:50',
            'duree_minutes' => 'required|integer|min:30|max:180',
            'prix_mensuel' => 'nullable|numeric|min:0',
            'prix_session' => 'nullable|numeric|min:0',
            'instructeur_principal_id' => 'nullable|exists:users,id',
            'instructeur_assistant_id' => 'nullable|exists:users,id|different:instructeur_principal_id',
            'status' => 'required|in:actif,inactif,complet,annule',
            'salle' => 'nullable|string|max:100',
            'materiel_requis' => 'nullable|string',
            'objectifs' => 'nullable|string',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            
            // Horaires
            'horaires' => 'required|array|min:1',
            'horaires.*.jour_semaine' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'horaires.*.heure_debut' => 'required|date_format:H:i',
            'horaires.*.heure_fin' => 'required|date_format:H:i|after:horaires.*.heure_debut',
            'horaires.*.salle' => 'nullable|string|max:255'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'nom.required' => 'Le nom du cours est obligatoire',
            'ecole_id.required' => 'L\'école est obligatoire',
            'age_max.gte' => 'L\'âge maximum doit être supérieur ou égal à l\'âge minimum',
            'instructeur_assistant_id.different' => 'L\'instructeur assistant doit être différent de l\'instructeur principal',
            'horaires.required' => 'Au moins un horaire est requis',
            'horaires.*.heure_fin.after' => 'L\'heure de fin doit être après l\'heure de début'
        ];
    }

    protected function prepareForValidation()
    {
        // Auto-assigner l'école si pas superadmin
        if (!Auth::user()->hasRole('superadmin') && !$this->filled('ecole_id')) {
            $this->merge(['ecole_id' => Auth::user()->ecole_id]);
        }
    }
}
