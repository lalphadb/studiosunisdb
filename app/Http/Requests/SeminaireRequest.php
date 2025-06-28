<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class SeminaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'type' => ['required', 'string', Rule::in(['technique', 'kata', 'competition', 'arbitrage', 'formation'])],
            'date_debut' => ['required', 'date', 'after_or_equal:today'],
            'date_fin' => ['required', 'date', 'after_or_equal:date_debut'],
            'heure_debut' => ['required', 'string', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'heure_fin' => ['required', 'string', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', 'after_time:heure_debut'],
            'lieu' => ['required', 'string', 'max:255'],
            'adresse_lieu' => ['nullable', 'string', 'max:255'],
            'ville_lieu' => ['nullable', 'string', 'max:100'],
            'instructeur' => ['required', 'string', 'max:255'],
            'prix' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'niveau_requis' => ['required', 'string', Rule::in(['debutant', 'intermediaire', 'avance', 'tous_niveaux'])],
            'inscription_ouverte' => ['boolean'],
            'materiel_requis' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre du séminaire est obligatoire.',
            'type.required' => 'Le type de séminaire est obligatoire.',
            'type.in' => 'Le type doit être : technique, kata, compétition, arbitrage ou formation.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.after_or_equal' => 'La date de fin doit être après ou égale à la date de début.',
            'heure_debut.required' => 'L\'heure de début est obligatoire.',
            'heure_debut.regex' => 'L\'heure de début doit être au format HH:MM (24h).',
            'heure_fin.required' => 'L\'heure de fin est obligatoire.',
            'heure_fin.regex' => 'L\'heure de fin doit être au format HH:MM (24h).',
            'heure_fin.after_time' => 'L\'heure de fin doit être après l\'heure de début.',
            'lieu.required' => 'Le lieu est obligatoire.',
            'instructeur.required' => 'L\'instructeur est obligatoire.',
            'niveau_requis.required' => 'Le niveau requis est obligatoire.',
            'niveau_requis.in' => 'Le niveau doit être : débutant, intermédiaire, avancé ou tous niveaux.',
            'prix.numeric' => 'Le prix doit être un nombre.',
        ];
    }

    protected function prepareForValidation()
    {
        // Convertir dates format canadien vers format MySQL si nécessaire
        if ($this->date_debut && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->date_debut)) {
            try {
                $date = Carbon::createFromFormat('d/m/Y', $this->date_debut);
                $this->merge(['date_debut' => $date->format('Y-m-d')]);
            } catch (\Exception $e) {
                // Laisser Laravel valider l'erreur
            }
        }

        if ($this->date_fin && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->date_fin)) {
            try {
                $date = Carbon::createFromFormat('d/m/Y', $this->date_fin);
                $this->merge(['date_fin' => $date->format('Y-m-d')]);
            } catch (\Exception $e) {
                // Laisser Laravel valider l'erreur
            }
        }
    }
}
