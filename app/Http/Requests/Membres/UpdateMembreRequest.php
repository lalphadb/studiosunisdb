<?php
declare(strict_types=1);

namespace App\Http\Requests\Membres;

use App\Models\Membre;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateMembreRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Membre $membre */
        $membre = $this->route('membre');
        return $this->user()?->can('membres.edit') && $membre?->exists;
    }

    public function rules(): array
    {
        $ecoleId = $this->user()?->ecole_id;
        /** @var Membre $membre */
        $membre = $this->route('membre');
        $userId = $membre?->user_id ?: 0;

        return [
            'prenom'                => ['required','string','max:100'],
            'nom'                   => ['required','string','max:100'],
            'date_naissance'        => ['required','date','before:today'],
            'telephone'             => ['nullable','string','max:30'],
            'adresse'               => ['nullable','string','max:255'],
            'statut'                => ['required', Rule::in(['actif','inactif','suspendu'])],
            'ceinture_actuelle_id'  => ['nullable','integer','exists:ceintures,id'],

            'email'                 => [
                'nullable','email','max:255',
                Rule::unique('users','email')
                    ->ignore($userId)
                    ,
            ],
            'password'              => ['nullable','string','min:8'],
        ];
    }
}
