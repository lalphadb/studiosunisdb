<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PresenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin_ecole']);
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'cours_id' => 'required|exists:cours,id',
            'date_cours' => 'required|date',
            'present' => 'boolean',
            'notes' => 'nullable|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Veuillez sélectionner un utilisateur.',
            'user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas.',
            'cours_id.required' => 'Veuillez sélectionner un cours.',
            'cours_id.exists' => 'Le cours sélectionné n\'existe pas.',
            'date_cours.required' => 'La date du cours est requise.',
            'date_cours.date' => 'La date du cours doit être une date valide.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 500 caractères.'
        ];
    }

    protected function prepareForValidation(): void
    {
        // Définir present à true par défaut si non fourni
        if (!$this->has('present')) {
            $this->merge(['present' => true]);
        }

        // Définir la date du cours à aujourd'hui si non fournie
        if (!$this->has('date_cours')) {
            $this->merge(['date_cours' => today()->format('Y-m-d')]);
        }
    }
}
