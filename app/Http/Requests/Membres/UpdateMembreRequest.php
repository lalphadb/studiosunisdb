<?php

namespace App\Http\Requests\Membres;

use App\Models\Membre;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateMembreRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Membre $membre */
        $membre = $this->route('membre');

        // Vérifier que le membre existe et que l'utilisateur peut le modifier
        return $membre?->exists && $this->user()?->can('update', $membre);
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
            'sexe' => ['required', 'in:M,F,Autre'],

            // Contact
            'telephone' => ['nullable', 'string', 'max:20'],

            // Contact d'urgence
            'contact_urgence_nom' => ['nullable', 'string', 'max:255'],
            'contact_urgence_telephone' => ['nullable', 'string', 'max:20'],
            'contact_urgence_relation' => ['nullable', 'string', 'max:255'],

            // Adresse
            'adresse' => ['nullable', 'string'],
            'ville' => ['nullable', 'string', 'max:100'],
            'code_postal' => ['nullable', 'string', 'max:10'],

            // Karaté
            'ceinture_actuelle_id' => ['nullable', 'exists:ceintures,id'],
            'statut' => ['required', 'in:actif,inactif,suspendu,diplome'],

            // Médical
            'notes_medicales' => ['nullable', 'string'],
            'allergies' => ['nullable', 'array'],
            'allergies.*' => ['string', 'max:255'],

            // Notes administratives
            'notes_instructeur' => ['nullable', 'string'],
            'notes_admin' => ['nullable', 'string'],

            // Consentements
            'consentement_photos' => ['boolean'],
            'consentement_communications' => ['boolean'],

            // Accès système et rôles
            'has_system_access' => ['boolean'],
            'user_email' => ['nullable', 'email', 'max:255'],
            'user_password' => ['nullable', 'string', 'min:8'],
            'user_roles' => ['nullable', 'array'],
            'user_roles.*' => ['in:membre,instructeur,admin_ecole,superadmin'],
            'user_active' => ['boolean'],
            'user_email_verified' => ['boolean'],
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
            'sexe.in' => 'Le sexe doit être M, F ou Autre.',
            'ceinture_actuelle_id.exists' => 'La ceinture sélectionnée n\'existe pas.',
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut doit être actif, inactif, suspendu ou diplômé.',
            'allergies.array' => 'Les allergies doivent être une liste.',
            'allergies.*.string' => 'Chaque allergie doit être du texte.',
            'allergies.*.max' => 'Chaque allergie ne peut dépasser 255 caractères.',
            'consentement_photos.boolean' => 'Le consentement photos doit être vrai ou faux.',
            'consentement_communications.boolean' => 'Le consentement communications doit être vrai ou faux.',

            // Messages accès système
            'user_email.email' => 'L\'email de connexion doit être valide.',
            'user_password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'user_roles.*.in' => 'Rôle non autorisé.',
        ];
    }
}
