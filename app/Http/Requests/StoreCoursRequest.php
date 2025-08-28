<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class StoreCoursRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Cours::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructeur_id' => [
                'nullable',
                'exists:users,id',
                Rule::exists('users', 'id')->where('ecole_id', $this->user()->ecole_id),
            ],
            'niveau' => 'required|in:tous,debutant,intermediaire,avance,prive,competition,a_la_carte',
            'age_min' => 'required|integer|min:3|max:99',
            'age_max' => 'nullable|integer|min:3|max:99|gte:age_min',
            'places_max' => 'required|integer|min:1|max:50',
            'jour_semaine' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            // Nouveau système de tarification flexible
            'type_tarif' => 'required|in:mensuel,trimestriel,horaire,a_la_carte,autre',
            'montant' => 'required|numeric|min:0|max:9999.99',
            'details_tarif' => 'required_if:type_tarif,autre|nullable|string|max:1000',
            // Ancien système (compatibilité) - gérer les strings vides
            'tarif_mensuel' => 'nullable|numeric|min:0|max:500',
            'actif' => 'boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convertir les strings vides en null pour tarif_mensuel
        if ($this->has('tarif_mensuel') && $this->input('tarif_mensuel') === '') {
            $this->merge(['tarif_mensuel' => null]);
        }
        
        // Assurer la cohérence du système de tarification
        if ($this->input('type_tarif') === 'mensuel' && $this->filled('montant')) {
            $this->merge(['tarif_mensuel' => $this->input('montant')]);
        } elseif ($this->input('type_tarif') !== 'mensuel' && !$this->filled('tarif_mensuel')) {
            $this->merge(['tarif_mensuel' => null]);
        }
        
        // Auto-assignation école (ROBUSTE)
        $user = $this->user();
        $ecoleId = null;
        
        if ($user && isset($user->ecole_id) && $user->ecole_id) {
            $ecoleId = $user->ecole_id;
        } else {
            // Fallback pour mono-école : utiliser ID 1 ou première école disponible
            $premiereEcole = \DB::table('ecoles')->first();
            if ($premiereEcole) {
                $ecoleId = $premiereEcole->id;
            } else {
                // Dernier fallback : ID par défaut 1 (mono-école)
                $ecoleId = 1;
            }
        }
        
        $this->merge(['ecole_id' => $ecoleId]);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du cours est obligatoire.',
            'niveau.required' => 'Le niveau est obligatoire.',
            'niveau.in' => 'Le niveau sélectionné n\'est pas valide.',
            'age_min.required' => 'L\'âge minimum est obligatoire.',
            'age_min.min' => 'L\'âge minimum doit être d\'au moins 3 ans.',
            'age_max.gte' => 'L\'âge maximum doit être supérieur ou égal à l\'âge minimum.',
            'places_max.required' => 'Le nombre de places maximum est obligatoire.',
            'places_max.min' => 'Il faut au minimum 1 place.',
            'places_max.max' => 'Maximum 50 places autorisées.',
            'jour_semaine.required' => 'Le jour de la semaine est obligatoire.',
            'heure_debut.required' => 'L\'heure de début est obligatoire.',
            'heure_fin.required' => 'L\'heure de fin est obligatoire.',
            'heure_fin.after' => 'L\'heure de fin doit être après l\'heure de début.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_fin.after' => 'La date de fin doit être après la date de début.',
            'type_tarif.required' => 'Le type de tarification est obligatoire.',
            'montant.required' => 'Le montant est obligatoire.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant ne peut pas être négatif.',
            'details_tarif.required_if' => 'Les détails sont obligatoires pour le type "autre".',
            'instructeur_id.exists' => 'L\'instructeur doit appartenir à votre école.',
        ];
    }
}
