<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'prenom' => $this->prenom,
            'nom' => $this->nom,
            'nom_complet' => $this->prenom.' '.$this->nom,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'date_naissance' => $this->date_naissance?->format('Y-m-d'),
            'age' => $this->date_naissance ? now()->diffInYears($this->date_naissance) : null,
            'sexe' => $this->sexe,
            'adresse' => $this->adresse,
            'ville' => $this->ville,
            'code_postal' => $this->code_postal,
            'province' => $this->province,
            'adresse_complete' => trim("{$this->adresse}, {$this->ville}, {$this->province} {$this->code_postal}"),
            'contact_urgence' => [
                'nom' => $this->contact_urgence_nom,
                'telephone' => $this->contact_urgence_telephone,
                'relation' => $this->contact_urgence_relation,
            ],
            'statut' => $this->statut,
            'statut_badge' => $this->getStatutBadge(),
            'ceinture_actuelle' => $this->whenLoaded('ceintureActuelle', function () {
                return [
                    'id' => $this->ceintureActuelle->id,
                    'nom' => $this->ceintureActuelle->nom,
                    'couleur' => $this->ceintureActuelle->couleur ?? '',
                    'ordre' => $this->ceintureActuelle->ordre ?? 0,
                ];
            }),
            'date_inscription' => $this->date_inscription?->format('Y-m-d'),
            'date_derniere_presence' => $this->date_derniere_presence?->format('Y-m-d'),
            'jours_depuis_derniere_presence' => $this->date_derniere_presence
                ? now()->diffInDays($this->date_derniere_presence)
                : null,
            'notes_medicales' => $this->notes_medicales,
            'allergies' => $this->allergies,
            'medicaments' => $this->medicaments,
            'consentements' => [
                'photos' => (bool) $this->consentement_photos,
                'communications' => (bool) $this->consentement_communications,
                'date' => $this->date_consentement?->format('Y-m-d H:i'),
            ],
            'liens_familiaux' => $this->whenLoaded('liensFamiliaux', function () {
                return $this->liensFamiliaux->map(fn ($lien) => [
                    'id' => $lien->id,
                    'membre_lie' => [
                        'id' => $lien->membreLie->id,
                        'nom_complet' => $lien->membreLie->prenom.' '.$lien->membreLie->nom,
                        'telephone' => $lien->membreLie->telephone,
                        'email' => $lien->membreLie->email,
                    ],
                    'type_relation' => $lien->type_relation,
                    'est_tuteur_legal' => (bool) $lien->est_tuteur_legal,
                    'contact_urgence' => (bool) $lien->contact_urgence,
                ]);
            }),
            'presences_count' => $this->whenCounted('presences'),
            'examens_count' => $this->whenCounted('examens'),
            'paiements_count' => $this->whenCounted('paiements'),
            'user' => $this->whenLoaded('user'),
            'ecole' => $this->whenLoaded('ecole'),
            'champs_personnalises' => $this->champs_personnalises,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    private function getStatutBadge(): array
    {
        return match ($this->statut) {
            'actif' => ['label' => 'Actif', 'color' => 'green'],
            'inactif' => ['label' => 'Inactif', 'color' => 'gray'],
            'suspendu' => ['label' => 'Suspendu', 'color' => 'red'],
            'archive' => ['label' => 'Archivé', 'color' => 'yellow'],
            default => ['label' => ucfirst($this->statut), 'color' => 'gray'],
        };
    }
}

class MembreCollection extends \Illuminate\Http\Resources\Json\ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total_actifs' => $this->collection->where('statut', 'actif')->count(),
                'total_inactifs' => $this->collection->where('statut', 'inactif')->count(),
            ],
        ];
    }
}
