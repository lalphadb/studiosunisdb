<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin_ecole', 'admin']);
    }

    public function rules(): array
    {
        return [
            'ecole_id' => [
                'required',
                'exists:ecoles,id',
                function ($attribute, $value, $fail) {
                    if (!auth()->user()->hasRole('superadmin')) {
                        $allowedEcoles = collect([auth()->user()->ecole_id]);
                        if (!$allowedEcoles->contains($value)) {
                            $fail('École non autorisée.');
                        }
                    }
                }
            ],
            'nom' => ['required', 'string', 'min:2', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'niveau' => [
                'required',
                Rule::in(['debutant', 'intermediaire', 'avance', 'tous_niveaux'])
            ],
            'capacite_max' => ['required', 'integer', 'min:1', 'max:100'],
            'prix' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'duree_minutes' => ['required', 'integer', 'min:30', 'max:300'],
            'instructeur' => ['nullable', 'string', 'max:255'],
            'active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'ecole_id.required' => 'L\'école est obligatoire.',
            'ecole_id.exists' => 'L\'école sélectionnée n\'existe pas.',
            'nom.required' => 'Le nom du cours est obligatoire.',
            'nom.min' => 'Le nom doit contenir au moins 2 caractères.',
            'niveau.required' => 'Le niveau est obligatoire.',
            'niveau.in' => 'Le niveau sélectionné n\'est pas valide.',
            'capacite_max.required' => 'La capacité maximale est obligatoire.',
            'capacite_max.min' => 'La capacité doit être d\'au moins 1 personne.',
            'capacite_max.max' => 'La capacité ne peut dépasser 100 personnes.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix ne peut être négatif.',
            'duree_minutes.required' => 'La durée est obligatoire.',
            'duree_minutes.min' => 'La durée minimum est de 30 minutes.',
            'duree_minutes.max' => 'La durée maximum est de 5 heures.',
        ];
    }
}
