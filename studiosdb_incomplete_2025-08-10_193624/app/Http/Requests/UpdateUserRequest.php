<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255', Rule::unique('users','email')->ignore($this->route('user'))],
            'password' => ['nullable','string','min:8'],
            'role'     => ['required','in:admin_ecole,instructeur,membre'],
            'statut'   => ['required','in:actif,suspendu,inactif'],
            'ecole_id' => ['nullable','exists:schools,id'],
        ];
    }
}
