<?php
declare(strict_types=1);

namespace App\Http\Requests\Membres;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class BulkMembreActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('membres.edit') ?? false;
    }

    public function rules(): array
    {
        $rules = [
            'ids'    => ['required','array','min:1'],
            'ids.*'  => ['integer','exists:membres,id'],
            'action' => ['required', Rule::in(['statut','assign_ceinture'])],
        ];

        if ($this->input('action') === 'statut') {
            $rules['value'] = ['required', Rule::in(['actif','inactif','suspendu'])];
        }

        if ($this->input('action') === 'assign_ceinture') {
            $rules['ceinture_id'] = ['required','integer','exists:ceintures,id'];
        }

        return $rules;
    }
}
