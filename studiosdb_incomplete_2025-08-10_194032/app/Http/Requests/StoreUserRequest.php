<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8'],
            'role'     => ['required','in:admin_ecole,instructeur,membre'],
            'ecole_id' => ['nullable','exists:schools,id'],
            'statut'   => ['required','in:actif,suspendu,inactif'],
        ];
    }
}
