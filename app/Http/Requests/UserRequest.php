<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin_ecole']);
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => $userId ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',
            'ecole_id' => auth()->user()->hasRole('admin_ecole') ? '' : 'nullable|exists:ecoles,id',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'sexe' => 'nullable|in:M,F',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:10',
            'contact_urgence_nom' => 'nullable|string|max:255',
            'contact_urgence_telephone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Auto-assigner l'école pour admin_ecole
        if (auth()->user()->hasRole('admin_ecole')) {
            $this->merge([
                'ecole_id' => auth()->user()->ecole_id,
            ]);
        }
    }
}
