<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';

    protected $fillable = [
        'user_id',
        'ecole_id',
        'processed_by_user_id',
        'reference_interne',
        'type_paiement',
        'motif',
        'description',
        'montant',
        'frais',
        'montant_net',
        'email_expediteur',
        'nom_expediteur',
        'reference_interac',
        'message_interac',
        'statut',
        'date_facture',
        'date_echeance',
        'date_reception',
        'date_validation',
        'periode_facturation',
        'annee_fiscale',
        'recu_fiscal_emis',
        'metadonnees',
        'notes_admin'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'frais' => 'decimal:2',
        'montant_net' => 'decimal:2',
        'date_facture' => 'datetime',
        'date_echeance' => 'datetime',
        'date_reception' => 'datetime',
        'date_validation' => 'datetime',
        'recu_fiscal_emis' => 'boolean',
        'metadonnees' => 'array'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec l'école
     */
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    /**
     * Relation avec l'utilisateur qui traite
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by_user_id');
    }

    /**
     * Scope pour filtrer par école (multi-tenant)
     */
    public function scopeForEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    /**
     * Scope pour les paiements en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les paiements reçus
     */
    public function scopeRecu($query)
    {
        return $query->where('statut', 'recu');
    }

    /**
     * Scope pour les paiements validés
     */
    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    /**
     * Accesseur pour le statut formaté
     */
    public function getStatutTextAttribute(): string
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'recu' => 'Reçu',
            'valide' => 'Validé',
            'rembourse' => 'Remboursé',
            'annule' => 'Annulé',
            default => ucfirst($this->statut),
        };
    }

    /**
     * Accesseur pour les classes CSS du badge de statut (couleurs module paiements)
     */
    public function getStatutBadgeAttribute(): string
    {
        return match($this->statut) {
            'valide' => 'bg-green-600 text-white',
            'recu' => 'bg-blue-600 text-white',
            'en_attente' => 'bg-yellow-600 text-white',
            'rembourse' => 'bg-purple-600 text-white',
            'annule' => 'bg-red-600 text-white',
            default => 'bg-gray-600 text-white',
        };
    }

    /**
     * Accesseur pour le motif formaté
     */
    public function getMotifTextAttribute(): string
    {
        return match($this->motif) {
            'session_automne' => 'Session Automne',
            'session_hiver' => 'Session Hiver',
            'session_printemps' => 'Session Printemps',
            'session_ete' => 'Session Été',
            'seminaire' => 'Séminaire',
            'examen_ceinture' => 'Examen Ceinture',
            'equipement' => 'Équipement',
            'autre' => 'Autre',
            default => ucfirst($this->motif),
        };
    }
}
