<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // La vérification est faite dans le contrôleur
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $ecoleId = $this->user()?->ecole_id;
        $authUser = $this->user();
        $superadminRule = $authUser && $authUser->hasRole('superadmin') ? [] : ['not_in:superadmin'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                \Illuminate\Validation\Rule::unique('users')->where(fn ($q) => $ecoleId ? $q->where('ecole_id', $ecoleId) : $q),
            ],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'roles' => ['sometimes', 'array'],
            'roles.*' => array_merge(['exists:roles,name'], $superadminRule),
            'email_verified' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}
