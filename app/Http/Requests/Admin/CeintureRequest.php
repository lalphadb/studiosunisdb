<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CeintureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_attribution' => 'required|date',
            'examinateur' => 'nullable|string|max:255',
            'lieu_examen' => 'nullable|string|max:255',
            'valide' => 'boolean'
        ];
    }
}
