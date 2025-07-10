<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PresenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'cours_id' => 'required|exists:cours,id',
            'date_cours' => 'required|date',
            'present' => 'boolean'
        ];
    }
}
