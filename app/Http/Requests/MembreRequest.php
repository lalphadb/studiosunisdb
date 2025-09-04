<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MembreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // La logique d'autorisation est dans les Policies
    }

    public function rules(): array
    {
        $membreId = $this->route('membre')?->id;
        $ecoleId = auth()->user()->ecole_id ?? 1;

        $rules = [
            'prenom' => ['required', 'string', 'max:100'],
            'nom' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('membres')
                    ->where('ecole_id', $ecoleId)
                    ->ignore($membreId)
            ],
            'telephone' => ['nullable', 'string', 'max:20'],
            'date_naissance' => ['required', 'date', 'before:today'],
            'sexe' => ['required', Rule::in(['M', 'F', 'Autre'])],
            'adresse' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:100'],
            'code_postal' => ['nullable', 'string', 'regex:/^[A-Z]\d[A-Z]\s?\d[A-Z]\d$/i'],
            'province' => ['nullable', 'string', 'max:2'],
            
            // Contact urgence
            'contact_urgence_nom' => ['required', 'string', 'max:200'],
            'contact_urgence_telephone' => ['required', 'string', 'max:20'],
            'contact_urgence_relation' => ['required', 'string', 'max:50'],
            
            // Statut et ceinture
            'statut' => ['required', Rule::in(['actif', 'inactif', 'suspendu'])],
            'ceinture_actuelle_id' => ['nullable', 'exists:belts,id'],
            
            // Informations médicales
            'notes_medicales' => ['nullable', 'string', 'max:1000'],
            'allergies' => ['nullable', 'array'],
            'medicaments' => ['nullable', 'array'],
            
            // Consentements
            'consentement_photos' => ['boolean'],
            'consentement_communications' => ['boolean'],
            
            // Liens familiaux
            'liens_familiaux' => ['nullable', 'array'],
            'liens_familiaux.*.membre_lie_id' => ['required_with:liens_familiaux', 'exists:membres,id'],
            'liens_familiaux.*.type_relation' => ['required_with:liens_familiaux', 'string', 'max:50'],
            'liens_familiaux.*.est_tuteur_legal' => ['boolean'],
            'liens_familiaux.*.contact_urgence' => ['boolean'],
            
            // Champs personnalisés
            'champs_personnalises' => ['nullable', 'array'],
            
            // Photo
            'photo' => ['nullable', 'image', 'max:5120'], // 5MB max
        ];

        // Règles spécifiques pour la création
        if (!$membreId) {
            $rules['user_id'] = ['nullable', 'exists:users,id'];
            $rules['date_inscription'] = ['nullable', 'date'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'prenom.required' => 'Le prénom est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse courriel est obligatoire.',
            'email.email' => 'L\'adresse courriel doit être valide.',
            'email.unique' => 'Cette adresse courriel est déjà utilisée dans cette école.',
            'date_naissance.required' => 'La date de naissance est obligatoire.',
            'date_naissance.before' => 'La date de naissance doit être dans le passé.',
            'sexe.required' => 'Le sexe est obligatoire.',
            'code_postal.regex' => 'Le code postal doit être au format A1A 1A1.',
            'contact_urgence_nom.required' => 'Le nom du contact d\'urgence est obligatoire.',
            'contact_urgence_telephone.required' => 'Le téléphone du contact d\'urgence est obligatoire.',
            'contact_urgence_relation.required' => 'La relation avec le contact d\'urgence est obligatoire.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.max' => 'L\'image ne doit pas dépasser 5 MB.',
        ];
    }

    public function validated($key = null, $default = null): mixed
    {
        $validated = parent::validated($key, $default);
        
        // Ajouter ecole_id automatiquement
        if (is_array($validated) && !isset($validated['ecole_id'])) {
            $validated['ecole_id'] = auth()->user()->ecole_id ?? 1;
        }
        
        // Gérer les consentements
        if (isset($validated['consentement_photos']) || isset($validated['consentement_communications'])) {
            $validated['date_consentement'] = now();
        }
        
        return $validated;
    }
}
