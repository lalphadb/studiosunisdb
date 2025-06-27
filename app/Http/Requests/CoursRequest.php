<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $rules = [
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'niveau' => ['nullable', Rule::in(['debutant', 'intermediaire', 'avance', 'tous_niveaux'])],
            'ecole_id' => ['required', 'exists:ecoles,id'],
            'capacite_max' => ['nullable', 'integer', 'min:1'],
            'prix' => ['nullable', 'numeric', 'min:0'],
            'duree_minutes' => ['nullable', 'integer', 'min:1'],
            'instructeur' => ['nullable', 'string', 'max:255'],
            'active' => ['boolean'],
        ];

        // Règles pour la duplication
        if ($this->filled('enable_duplication')) {
            $rules['enable_duplication'] = ['boolean'];
            $rules['nombre_copies'] = ['required_if:enable_duplication,1', 'integer', 'min:1', 'max:10'];
            $rules['suffixes'] = ['required_if:enable_duplication,1', 'array'];
            $rules['suffixes.*'] = ['required', 'string', 'max:100'];
            $rules['jours_semaine'] = ['nullable', 'array'];
            $rules['jours_semaine.*'] = ['nullable', 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche'];
            $rules['modifier_horaires'] = ['boolean'];
            $rules['nouvelles_heures'] = ['nullable', 'array'];
            $rules['nouvelles_heures.*'] = ['nullable', 'string', 'max:50'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du cours est obligatoire.',
            'ecole_id.required' => 'L\'école est obligatoire.',
            'ecole_id.exists' => 'L\'école sélectionnée n\'existe pas.',
            'nombre_copies.required_if' => 'Le nombre de copies est obligatoire pour la duplication.',
            'suffixes.required_if' => 'Les suffixes sont obligatoires pour la duplication.',
            'suffixes.*.required' => 'Chaque suffixe est obligatoire.',
        ];
    }
}
