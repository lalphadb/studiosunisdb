<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SessionCoursRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasAnyPermission(['create-sessions', 'update-sessions']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $sessionId = $this->route('session') ? $this->route('session')->id : null;

        return [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'ecole_id' => 'required|exists:ecoles,id',
            'actif' => 'boolean',
            'inscriptions_ouvertes' => 'boolean',
            'date_limite_inscription' => 'nullable|date|before_or_equal:date_debut',
            'dupliquer_depuis_session_id' => 'nullable|exists:sessions_cours,id',
            'ajuster_prix' => 'boolean',
            'pourcentage_augmentation' => 'nullable|numeric|min:0|max:100'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la session est obligatoire.',
            'nom.unique' => 'Une session avec ce nom existe déjà pour cette école.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.after' => 'La date de fin doit être après la date de début.',
            'ecole_id.required' => 'L\'école est obligatoire.',
            'ecole_id.exists' => 'L\'école sélectionnée n\'existe pas.',
            'date_limite_inscription.before_or_equal' => 'La date limite d\'inscription doit être avant ou égale à la date de début.',
            'pourcentage_augmentation.numeric' => 'Le pourcentage doit être un nombre.',
            'pourcentage_augmentation.max' => 'Le pourcentage ne peut pas dépasser 100%.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Force ecole_id pour admin_ecole (sécurité multi-tenant)
        if (!Auth::user()->hasRole('super_admin')) {
            $this->merge([
                'ecole_id' => Auth::user()->ecole_id
            ]);
        }

        // Valeurs par défaut
        if (!$this->has('actif')) {
            $this->merge(['actif' => false]);
        }

        if (!$this->has('inscriptions_ouvertes')) {
            $this->merge(['inscriptions_ouvertes' => false]);
        }
    }

    /**
     * Configure validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validation supplémentaire pour duplication
            if ($this->filled('dupliquer_depuis_session_id')) {
                $sessionSource = \App\Models\SessionCours::find($this->dupliquer_depuis_session_id);
                
                if (!$sessionSource) {
                    $validator->errors()->add('dupliquer_depuis_session_id', 'Session source introuvable.');
                    return;
                }

                // Vérifier que session source appartient à même école (sécurité)
                if (!Auth::user()->hasRole('super_admin') && 
                    $sessionSource->ecole_id !== Auth::user()->ecole_id) {
                    $validator->errors()->add('dupliquer_depuis_session_id', 'Vous ne pouvez dupliquer que vos propres sessions.');
                }

                // Vérifier que session source a des horaires
                if ($sessionSource->coursHoraires()->count() === 0) {
                    $validator->errors()->add('dupliquer_depuis_session_id', 'La session source ne contient aucun horaire à dupliquer.');
                }
            }

            // Validation dates logiques
            if ($this->filled(['date_debut', 'date_fin'])) {
                $debut = \Carbon\Carbon::parse($this->date_debut);
                $fin = \Carbon\Carbon::parse($this->date_fin);
                
                if ($debut->diffInDays($fin) < 7) {
                    $validator->errors()->add('date_fin', 'La session doit durer au moins 7 jours.');
                }

                if ($debut->diffInDays($fin) > 365) {
                    $validator->errors()->add('date_fin', 'La session ne peut pas durer plus d\'un an.');
                }
            }
        });
    }
}
