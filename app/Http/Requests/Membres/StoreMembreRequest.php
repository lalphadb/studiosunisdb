<?php

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
                }),
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
                }),
            ],
            
            // Médical
            'allergies' => ['nullable', 'string'],
            'conditions_medicales' => ['nullable', 'string'],
            'medications' => ['nullable', 'string'],
            
            // Photo
            'photo' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg'],
            
            // User lié (optionnel pour créer un compte)
            'create_user' => ['boolean'],
            'user_email' => [
                'required_if:create_user,true',
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'user_password' => ['required_if:create_user,true', 'nullable', 'string', 'min:8'],
            
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
            'user_email.required_if' => 'L\'email est requis pour créer un compte utilisateur.',
            'user_password.required_if' => 'Le mot de passe est requis pour créer un compte utilisateur.',
            'user_password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ];
    }
}
