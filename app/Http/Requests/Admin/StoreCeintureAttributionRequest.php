<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCeintureAttributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create_ceintures');
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'ceinture_id' => ['required', 'exists:ceintures,id'],
            'date_obtention' => ['required', 'date'],
            'examinateur' => ['nullable', 'string', 'max:255'],
            'commentaires' => ['nullable', 'string', 'max:1000'],
            'valide' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Veuillez sélectionner un membre.',
            'ceinture_id.required' => 'Veuillez sélectionner une ceinture.',
            'date_obtention.required' => 'La date d\'obtention est obligatoire.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Multi-tenant: vérifier que l'utilisateur appartient à la bonne école
        if ($this->user_id && auth()->user()->hasRole('admin_ecole')) {
            $user = \App\Models\User::find($this->user_id);
            if ($user && $user->ecole_id !== auth()->user()->ecole_id) {
                abort(403, 'Utilisateur non autorisé pour cette école');
            }
        }
    }
}
