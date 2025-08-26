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
            'sexe' => ['required', 'in:M,F,Autre'],
            
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
            
            // Contact urgence
            'contact_urgence_nom' => ['nullable', 'string', 'max:255'],
            'contact_urgence_telephone' => ['nullable', 'string', 'max:20'],
            'contact_urgence_relation' => ['nullable', 'string', 'max:255'],
            
            // Adresse
            'adresse' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:100'],
            'code_postal' => ['nullable', 'string', 'max:10'],
            'province' => ['nullable', 'string', 'max:50'],
            
            // Karaté
            'ceinture_actuelle_id' => ['nullable', 'exists:ceintures,id'],
            'date_inscription' => ['nullable', 'date'],
            
            // Médical
            'notes_medicales' => ['nullable', 'string'],
            'allergies' => ['nullable', 'array'],
            'medicaments' => ['nullable', 'array'],
            
            // Photo
            'photo' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg'],
            
            // User lié (optionnel pour créer un compte)
            'password' => ['nullable', 'string', 'min:8'],
            
            // Consentements
            'consentement_photos' => ['nullable', 'boolean'],
            'consentement_communications' => ['nullable', 'boolean'],
            
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
            'email.unique' => 'Cet email est déjà utilisé par un autre membre de l\'école.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.max' => 'L\'image ne doit pas dépasser 2 MB.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ];
    }
}
