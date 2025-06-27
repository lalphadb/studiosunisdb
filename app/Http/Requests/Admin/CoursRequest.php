<?php

namespace App\Http\Requests\Admin;

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
        return [
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'ecole_id' => [
                'required', 
                'exists:ecoles,id',
                Rule::in($this->getAvailableEcoles())
            ],
            'niveau' => [
                'required', 
                Rule::in(['debutant', 'intermediaire', 'avance', 'tous_niveaux'])
            ],
            'capacite_max' => ['required', 'integer', 'min:1', 'max:100'],
            'duree_minutes' => ['required', 'integer', 'min:30', 'max:180'],
            'prix' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'instructeur' => ['nullable', 'string', 'max:255'],
            'active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du cours est obligatoire.',
            'nom.max' => 'Le nom du cours ne peut dépasser 255 caractères.',
            'ecole_id.required' => 'L\'école est obligatoire.',
            'ecole_id.exists' => 'L\'école sélectionnée n\'existe pas.',
            'niveau.required' => 'Le niveau est obligatoire.',
            'niveau.in' => 'Le niveau sélectionné n\'est pas valide.',
            'capacite_max.required' => 'La capacité maximale est obligatoire.',
            'capacite_max.min' => 'La capacité maximale doit être d\'au moins 1 personne.',
            'capacite_max.max' => 'La capacité maximale ne peut dépasser 100 personnes.',
            'duree_minutes.required' => 'La durée est obligatoire.',
            'duree_minutes.min' => 'La durée minimale est de 30 minutes.',
            'duree_minutes.max' => 'La durée maximale est de 180 minutes.',
            'prix.min' => 'Le prix ne peut être négatif.',
            'prix.max' => 'Le prix ne peut dépasser 999.99$.',
        ];
    }

    /**
     * Préparer les données pour validation
     */
    protected function prepareForValidation()
    {
        // Convertir active en boolean
        if ($this->has('active')) {
            $this->merge([
                'active' => $this->boolean('active')
            ]);
        } else {
            $this->merge(['active' => false]);
        }
    }

    /**
     * Obtenir les écoles disponibles selon permissions
     */
    private function getAvailableEcoles(): array
    {
        $user = auth()->user();

        if ($user->hasRole('superadmin')) {
            return \App\Models\Ecole::where('active', true)->pluck('id')->toArray();
        }

        if ($user->hasRole('admin_ecole')) {
            return [$user->ecole_id];
        }

        return [];
    }
}
