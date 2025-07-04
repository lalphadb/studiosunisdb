<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePresenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('edit_presences');
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'session_cours_id' => ['required', 'integer', 'exists:sessions_cours,id'],
            'cours_horaire_id' => ['nullable', 'integer', 'exists:cours_horaires,id'],
            'date_presence' => ['required', 'date'],
            'heure_arrivee' => ['nullable', 'date_format:H:i'],
            'heure_depart' => ['nullable', 'date_format:H:i'],
            'statut' => ['required', 'string', 'in:present,absent,retard,excuse'],
            'notes' => ['nullable', 'string', 'max:500']
        ];
    }
}
