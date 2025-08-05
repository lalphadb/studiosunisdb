<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'membre_id',
        'type',
        'montant',
        'description',
        'date_echeance',
        'date_paiement',
        'statut',
        'methode_paiement',
        'reference_transaction',
        'notes',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_echeance' => 'date',
        'date_paiement' => 'date',
    ];

    // Relations
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    // Scopes
    public function scopePaye($query)
    {
        return $query->where('statut', 'paye');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeEnRetard($query)
    {
        return $query->where('statut', 'en_retard')
                    ->orWhere(function($q) {
                        $q->where('statut', 'en_attente')
                          ->where('date_echeance', '<', today());
                    });
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeParMois($query, $mois, $annee = null)
    {
        $annee = $annee ?: now()->year;
        return $query->whereYear('date_echeance', $annee)
                    ->whereMonth('date_echeance', $mois);
    }

    public function scopeRevenuMois($query, $mois, $annee = null)
    {
        $annee = $annee ?: now()->year;
        return $query->where('statut', 'paye')
                    ->whereYear('date_paiement', $annee)
                    ->whereMonth('date_paiement', $mois);
    }

    // Accessors
    public function getEstPayeAttribute(): bool
    {
        return $this->statut === 'paye';
    }

    public function getEstEnRetardAttribute(): bool
    {
        return $this->statut === 'en_retard' || 
               ($this->statut === 'en_attente' && $this->date_echeance < today());
    }

    public function getJoursRetardAttribute(): int
    {
        if ($this->est_paye || $this->date_echeance >= today()) {
            return 0;
        }

        return today()->diffInDays($this->date_echeance);
    }

    public function getCouleurStatutAttribute(): string
    {
        return match($this->statut) {
            'paye' => '#10B981',      // Vert
            'en_attente' => '#F59E0B', // Orange
            'en_retard' => '#EF4444',  // Rouge
            'annule' => '#6B7280',     // Gris
            default => '#6B7280'
        };
    }

    public function getIconeStatutAttribute(): string
    {
        return match($this->statut) {
            'paye' => '✓',
            'en_attente' => '⏳',
            'en_retard' => '⚠️',
            'annule' => '✗',
            default => '?'
        };
    }

    public function getMontantFormatAttribute(): string
    {
        return number_format($this->montant, 2, ',', ' ') . ' $';
    }

    // Méthodes utilitaires
    public function marquerPaye(
        string $methodePaiement = 'especes',
        ?string $reference = null,
        ?Carbon $datePaiement = null
    ): bool {
        $this->update([
            'statut' => 'paye',
            'date_paiement' => $datePaiement ?: today(),
            'methode_paiement' => $methodePaiement,
            'reference_transaction' => $reference,
        ]);

        return true;
    }

    public function marquerEnRetard(): bool
    {
        if ($this->statut === 'en_attente' && $this->date_echeance < today()) {
            $this->update(['statut' => 'en_retard']);
            return true;
        }

        return false;
    }

    public static function creerFactureMensuelle(
        int $membreId,
        float $montant,
        Carbon $dateEcheance,
        string $description = 'Cotisation mensuelle'
    ): self {
        return self::create([
            'membre_id' => $membreId,
            'type' => 'mensuel',
            'montant' => $montant,
            'description' => $description,
            'date_echeance' => $dateEcheance,
            'statut' => 'en_attente',
        ]);
    }

    public static function statistiquesFinancieres(int $mois = null, int $annee = null): array
    {
        $mois = $mois ?: now()->month;
        $annee = $annee ?: now()->year;

        $revenus = self::revenuMois($mois, $annee)->sum('montant');
        $enAttente = self::enAttente()->parMois($mois, $annee)->sum('montant');
        $enRetard = self::enRetard()->sum('montant');
        
        $totalPaiements = self::parMois($mois, $annee)->count();
        $paiementsReçus = self::paye()->parMois($mois, $annee)->count();

        return [
            'revenus_mois' => $revenus,
            'en_attente' => $enAttente,
            'en_retard' => $enRetard,
            'total_paiements' => $totalPaiements,
            'paiements_recus' => $paiementsReçus,
            'taux_recouvrement' => $totalPaiements > 0 ? 
                round($paiementsReçus / $totalPaiements * 100, 1) : 0,
        ];
    }
}
