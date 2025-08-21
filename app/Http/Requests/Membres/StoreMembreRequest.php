<?php
declare(strict_types=1);

namespace App\Http\Requests\Membres;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreMembreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('membres.create') ?? false;
    }

    public function rules(): array
    {
        $ecoleId = $this->user()?->ecole_id;

        return [
            'prenom'                => ['required','string','max:100'],
            'nom'                   => ['required','string','max:100'],
            'date_naissance'        => ['required','date','before:today'],
            'telephone'             => ['nullable','string','max:30'],
            'adresse'               => ['nullable','string','max:255'],
            'statut'                => ['nullable', Rule::in(['actif','inactif','suspendu'])],
            'ceinture_actuelle_id'  => ['nullable','integer','exists:ceintures,id'],
            'date_inscription'      => ['nullable','date'],

            // User lié (optionnel)
            'email'                 => [
                'nullable','email','max:255',
                Rule::unique('users','email'),
            ],
            'password'              => ['nullable','string','min:8'],
        ];
    }
}
