<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCeintureAttributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('edit_ceintures');
    }

    public function rules(): array
    {
        return [
            'ceinture_id' => ['required', 'exists:ceintures,id'],
            'date_obtention' => ['required', 'date'],
            'examinateur' => ['nullable', 'string', 'max:255'],
            'commentaires' => ['nullable', 'string', 'max:1000'],
            'valide' => ['nullable', 'boolean'],
        ];
    }
}
