<?php

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
        /** @var Membre $membre */
        $membre = $this->route('membre');

        return [
            // Informations personnelles
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'date_naissance' => ['required', 'date', 'before:today'],
            'sexe' => ['required', 'in:M,F'],
            
            // Contact
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('membres')->where(function ($query) {
                    return $query->where('ecole_id', auth()->user()->ecole_id);
                })->ignore($membre->id),
            ],
            'telephone' => ['nullable', 'string', 'max:20'],
            'telephone_urgence' => ['nullable', 'string', 'max:20'],
            'contact_urgence' => ['nullable', 'string', 'max:255'],
            
            // Adresse
            'adresse' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:100'],
            'code_postal' => ['nullable', 'string', 'max:10'],
            'province' => ['nullable', 'string', 'max:50'],
            
            // Karaté
            'ceinture_id' => ['required', 'exists:ceintures,id'],
            'date_inscription' => ['required', 'date'],
            'numero_membre' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('membres')->where(function ($query) {
                    return $query->where('ecole_id', auth()->user()->ecole_id);
                })->ignore($membre->id),
            ],
            
            // Médical
            'allergies' => ['nullable', 'string'],
            'conditions_medicales' => ['nullable', 'string'],
            'medications' => ['nullable', 'string'],
            
            // Photo
            'photo' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg'],
            
            // User lié (mise à jour optionnelle)
            'user_email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($membre->user_id),
            ],
            
            // Relations familiales
            'famille_ids' => ['nullable', 'array'],
            'famille_ids.*' => ['exists:membres,id'],
            
            // Statut
            'statut' => ['required', 'in:actif,inactif,suspendu'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'date_naissance.required' => 'La date de naissance est obligatoire.',
            'date_naissance.before' => 'La date de naissance doit être dans le passé.',
            'sexe.required' => 'Le sexe est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé par un autre membre de l\'école.',
            'ceinture_id.required' => 'La ceinture est obligatoire.',
            'date_inscription.required' => 'La date d\'inscription est obligatoire.',
            'numero_membre.unique' => 'Ce numéro de membre est déjà utilisé.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.max' => 'L\'image ne doit pas dépasser 2 MB.',
        ];
    }
}
