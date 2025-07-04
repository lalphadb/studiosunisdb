<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('update', $this->route('user'));
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', "unique:users,email,{$userId}"],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'ecole_id' => ['nullable', 'integer', 'exists:ecoles,id'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'date_naissance' => ['nullable', 'date'],
            'genre' => ['nullable', 'string', 'in:homme,femme,autre'],
            'role' => ['required', 'string', 'exists:roles,name'],
            'active' => ['nullable', 'boolean']
        ];
    }
}
