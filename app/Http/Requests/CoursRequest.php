<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $coursId = $this->route('cours')?->id;
        
        return [
            'ecole_id' => ['required', 'exists:ecoles,id'],
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'niveau' => ['required', 'string', Rule::in(['debutant', 'intermediaire', 'avance', 'tous_niveaux'])],
            'capacite_max' => ['required', 'integer', 'min:1', 'max:100'],
            'prix' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'duree_minutes' => ['required', 'integer', 'min:30', 'max:240'],
            'instructeur' => ['nullable', 'string', 'max:255'],
            'active' => ['boolean'],
            
            // Horaires (optionnels)
            'horaires' => ['nullable', 'array'],
            'horaires.*.jour_semaine' => ['required_with:horaires', Rule::in(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'])],
            'horaires.*.heure_debut' => ['required_with:horaires', 'date_format:H:i'],
            'horaires.*.heure_fin' => ['required_with:horaires', 'date_format:H:i', 'after:horaires.*.heure_debut'],
            'horaires.*.date_debut' => ['nullable', 'date'],
            'horaires.*.date_fin' => ['nullable', 'date', 'after_or_equal:horaires.*.date_debut'],
        ];
    }

    public function messages(): array
    {
        return [
            'ecole_id.required' => 'L\'école est obligatoire.',
            'ecole_id.exists' => 'L\'école sélectionnée n\'existe pas.',
            'nom.required' => 'Le nom du cours est obligatoire.',
            'niveau.required' => 'Le niveau est obligatoire.',
            'niveau.in' => 'Le niveau doit être : débutant, intermédiaire, avancé ou tous niveaux.',
            'capacite_max.required' => 'La capacité maximale est obligatoire.',
            'capacite_max.min' => 'La capacité doit être d\'au moins 1 personne.',
            'duree_minutes.required' => 'La durée est obligatoire.',
            'duree_minutes.min' => 'La durée minimale est de 30 minutes.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'horaires.*.jour_semaine.required_with' => 'Le jour de la semaine est obligatoire.',
            'horaires.*.heure_debut.required_with' => 'L\'heure de début est obligatoire.',
            'horaires.*.heure_fin.after' => 'L\'heure de fin doit être après l\'heure de début.',
        ];
    }

    protected function getAvailableEcoles(): array
    {
        $user = Auth::user();
        
        if ($user->hasRole('super-admin')) {
            return \App\Models\Ecole::pluck('nom', 'id')->toArray();
        } elseif ($user->hasRole('admin-ecole')) {
            return \App\Models\Ecole::where('id', $user->ecole_id)->pluck('nom', 'id')->toArray();
        }
        
        return [];
    }
}
