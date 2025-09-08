<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'cours_id',
        'user_id',  // Gardé user_id mais pointe vers users
        'instructeur_id',
        'date_cours',
        'statut',
        'heure_arrivee',
        'notes',
    ];

    protected $casts = [
        'date_cours' => 'date',
        'heure_arrivee' => 'datetime:H:i',
    ];

    // Relations
    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    // Relation avec User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function instructeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    // Scopes
    public function scopePresent($query)
    {
        return $query->where('statut', 'present');
    }

    public function scopeAbsent($query)
    {
        return $query->where('statut', 'absent');
    }

    public function scopeParMois($query, $mois, $annee = null)
    {
        $annee = $annee ?: now()->year;

        return $query->whereYear('date_cours', $annee)
            ->whereMonth('date_cours', $mois);
    }

    public function scopeParSemaine($query, $semaine = null)
    {
        $semaine = $semaine ?: now()->weekOfYear;

        return $query->whereRaw('WEEK(date_cours) = ?', [$semaine]);
    }

    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_cours', today());
    }

    // Accessors
    public function getEstPresentAttribute(): bool
    {
        return $this->statut === 'present';
    }

    public function getEstRetardAttribute(): bool
    {
        return $this->statut === 'retard';
    }

    public function getCouleurStatutAttribute(): string
    {
        return match ($this->statut) {
            'present' => '#10B981', // Vert
            'retard' => '#F59E0B',  // Orange
            'excuse' => '#6B7280',  // Gris
            'absent' => '#EF4444',  // Rouge
            default => '#6B7280'
        };
    }

    public function getIconeStatutAttribute(): string
    {
        return match ($this->statut) {
            'present' => '✓',
            'retard' => '⏰',
            'excuse' => '📝',
            'absent' => '✗',
            default => '?'
        };
    }

    // Méthodes utilitaires
    public static function marquerPresence(
        int $coursId,
        int $membreId,
        string $statut = 'present',
        ?string $notes = null
    ): self {
        return self::updateOrCreate(
            [
                'cours_id' => $coursId,
                'user_id' => $membreId,
                'date_cours' => today(),
            ],
            [
                'statut' => $statut,
                'heure_arrivee' => now(),
                'notes' => $notes,
                'instructeur_id' => auth()->id(),
            ]
        );
    }

    public static function statistiquesParMembre(int $membreId, ?int $mois = null): array
    {
        $query = self::where('user_id', $membreId);

        if ($mois) {
            $query->whereMonth('date_cours', $mois);
        }

        $total = $query->count();
        $presents = $query->where('statut', 'present')->count();
        $retards = $query->where('statut', 'retard')->count();
        $absences = $query->where('statut', 'absent')->count();

        return [
            'total' => $total,
            'presents' => $presents,
            'retards' => $retards,
            'absences' => $absences,
            'taux_presence' => $total > 0 ? round(($presents + $retards) / $total * 100, 1) : 0,
            'taux_absence' => $total > 0 ? round($absences / $total * 100, 1) : 0,
        ];
    }
}
