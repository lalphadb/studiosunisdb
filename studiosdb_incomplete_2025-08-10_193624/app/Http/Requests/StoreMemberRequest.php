<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'prenom' => ['required','string','max:120'],
            'nom' => ['required','string','max:120'],
            'courriel' => ['nullable','email','max:255'],
            'telephone' => ['nullable','string','max:50'],
            'adresse' => ['nullable','string','max:255'],
            'date_naissance' => ['nullable','date'],
            'date_inscription' => ['nullable','date'],
            'statut' => ['required','in:actif,suspendu,inactif'],
            'ceinture_actuelle_id' => ['nullable','exists:belts,id'],
        ];
    }
}
