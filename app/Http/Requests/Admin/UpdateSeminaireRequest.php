<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeminaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('update', $this->route('seminaire'));
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'lieu' => 'nullable|string|max:255',
            'prix' => 'nullable|numeric|min:0',
            'capacite_max' => 'nullable|integer|min:1'
        ];
    }
}
