<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'niveau' => 'nullable|string|in:debutant,intermediaire,avance,tous_niveaux',
            'capacite_max' => 'nullable|integer|min:1|max:100',
            'prix' => 'nullable|numeric|min:0|max:9999.99',
            'duree_minutes' => 'nullable|integer|min:15|max:300',
            'instructeur' => 'nullable|string|max:255|regex:/^[a-zA-ZÀ-ÿ\s\-\'\.]+$/', // CORRECTION: Validation instructeur
            'active' => 'boolean',
            'ecole_id' => 'nullable|exists:ecoles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du cours est obligatoire.',
            'niveau.in' => 'Le niveau doit être : débutant, intermédiaire, avancé ou tous niveaux.',
            'capacite_max.min' => 'La capacité doit être d\'au moins 1 participant.',
            'capacite_max.max' => 'La capacité ne peut dépasser 100 participants.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'duree_minutes.min' => 'La durée minimum est de 15 minutes.',
            'duree_minutes.max' => 'La durée maximum est de 5 heures.',
            'instructeur.regex' => 'Le nom de l\'instructeur contient des caractères invalides.',
            'ecole_id.exists' => 'L\'école sélectionnée n\'existe pas.',
        ];
    }

    protected function prepareForValidation()
    {
        // Auto-assignation ecole_id pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole') && !$this->has('ecole_id')) {
            $this->merge([
                'ecole_id' => auth()->user()->ecole_id
            ]);
        }

        // Nettoyage du champ active
        $this->merge([
            'active' => $this->boolean('active')
        ]);
    }
}
