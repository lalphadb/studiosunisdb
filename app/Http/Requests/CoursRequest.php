<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CoursRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', \App\Models\Cours::class) || 
               Gate::allows('update', \App\Models\Cours::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'ecole_id' => 'required|exists:ecoles,id',
            'instructeur_id' => 'nullable|exists:users,id',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'niveau_requis' => 'nullable|string|max:100',
            'age_min' => 'nullable|integer|min:5|max:99',
            'age_max' => 'nullable|integer|min:5|max:99|gte:age_min',
            'capacite_max' => 'required|integer|min:1|max:100',
            'duree_minutes' => 'required|integer|min:15|max:180',
            'prix_mensuel' => 'required|numeric|min:0|max:999.99',
            'prix_session' => 'nullable|numeric|min:0|max:999.99',
            'status' => 'required|in:actif,inactif,complet',
            'type_cours' => 'required|in:karate,boxe,kickboxing,cardiobox,enfants,adultes',
            'jour_semaine' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'date_debut' => 'nullable|date|after_or_equal:today',
            'date_fin' => 'nullable|date|after:date_debut',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du cours est obligatoire.',
            'capacite_max.required' => 'La capacité maximale est obligatoire.',
            'capacite_max.min' => 'La capacité doit être au moins de 1 personne.',
            'duree_minutes.required' => 'La durée du cours est obligatoire.',
            'duree_minutes.min' => 'La durée minimum est de 15 minutes.',
            'duree_minutes.max' => 'La durée maximum est de 180 minutes.',
            'prix_mensuel.required' => 'Le prix mensuel est obligatoire.',
            'heure_debut.required' => 'L\'heure de début est obligatoire.',
            'heure_fin.required' => 'L\'heure de fin est obligatoire.',
            'heure_fin.after' => 'L\'heure de fin doit être après l\'heure de début.',
            'age_max.gte' => 'L\'âge maximum doit être supérieur ou égal à l\'âge minimum.',
            'date_fin.after' => 'La date de fin doit être après la date de début.',
        ];
    }
}
