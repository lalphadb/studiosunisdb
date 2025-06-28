<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PresenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['superadmin', 'admin']);
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'cours_id' => 'required|exists:cours,id',
            'date_cours' => 'required|date',
            'statut' => 'required|in:present,absent,excuse,retard',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i|after:heure_arrivee',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Veuillez sélectionner un utilisateur.',
            'cours_id.required' => 'Veuillez sélectionner un cours.',
            'date_cours.required' => 'La date du cours est requise.',
            'statut.required' => 'Le statut de présence est requis.',
            'heure_depart.after' => 'L\'heure de départ doit être après l\'heure d\'arrivée.',
        ];
    }
}
