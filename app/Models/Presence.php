<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cours_id',
        'date_cours',
        'present',
        'notes'
    ];

    protected $casts = [
        'date_cours' => 'date',
        'present' => 'boolean'
    ];

    // Global Scope Multi-tenant StudiosDB
    protected static function booted(): void
    {
        if (!app()->runningInConsole() && auth()->check() && !auth()->user()->hasRole('superadmin')) {
            static::addGlobalScope('ecole', function (Builder $builder) {
                $builder->whereHas('cours', function ($query) {
                    $query->where('ecole_id', auth()->user()->ecole_id);
                });
            });
        }
    }

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class, 'ecole_id');
    }

    // Scopes utiles
    public function scopePresent(Builder $query): Builder
    {
        return $query->where('present', true);
    }

    public function scopeAbsent(Builder $query): Builder
    {
        return $query->where('present', false);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('date_cours', today());
    }

    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('date_cours', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('date_cours', now()->month)
                    ->whereYear('date_cours', now()->year);
    }

    public function scopePourEcole(Builder $query, int $ecoleId): Builder
    {
        return $query->whereHas('cours', function ($q) use ($ecoleId) {
            $q->where('ecole_id', $ecoleId);
        });
    }

    // Accessors StudiosDB
    public function getStatusTextAttribute(): string
    {
        return $this->present ? 'Présent' : 'Absent';
    }

    public function getStatusColorAttribute(): string
    {
        return $this->present ? 'green' : 'red';
    }

    public function getStatusIconAttribute(): string
    {
        return $this->present ? '✅' : '❌';
    }

    public function getStatusBadgeAttribute(): string
    {
        if ($this->present) {
            return '<span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">✅ Présent</span>';
        }
        
        return '<span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">❌ Absent</span>';
    }

    public function getDateFormattedAttribute(): string
    {
        return $this->date_cours->format('d/m/Y');
    }

    public function getDateHumanAttribute(): string
    {
        return $this->date_cours->diffForHumans();
    }

    // Méthodes utiles
    public function marquerPresent(): bool
    {
        return $this->update(['present' => true]);
    }

    public function marquerAbsent(): bool
    {
        return $this->update(['present' => false]);
    }

    public function togglePresence(): bool
    {
        return $this->update(['present' => !$this->present]);
    }

    // Méthodes statiques pour statistiques
    public static function tauxPresenceGlobal(): float
    {
        $total = static::count();
        if ($total === 0) return 0;

        $presents = static::where('present', true)->count();
        return round(($presents / $total) * 100, 1);
    }

    public static function tauxPresencePourCours(int $coursId): float
    {
        $total = static::where('cours_id', $coursId)->count();
        if ($total === 0) return 0;

        $presents = static::where('cours_id', $coursId)->where('present', true)->count();
        return round(($presents / $total) * 100, 1);
    }

    public static function tauxPresencePourUser(int $userId): float
    {
        $total = static::where('user_id', $userId)->count();
        if ($total === 0) return 0;

        $presents = static::where('user_id', $userId)->where('present', true)->count();
        return round(($presents / $total) * 100, 1);
    }

    public static function statistiquesParMois(int $ecoleId = null): array
    {
        $query = static::selectRaw('
            YEAR(date_cours) as annee,
            MONTH(date_cours) as mois,
            COUNT(*) as total,
            SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END) as presents,
            ROUND((SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1) as taux_presence
        ')->groupBy('annee', 'mois')
          ->orderBy('annee', 'desc')
          ->orderBy('mois', 'desc');

        if ($ecoleId) {
            $query->whereHas('cours', function ($q) use ($ecoleId) {
                $q->where('ecole_id', $ecoleId);
            });
        }

        return $query->get()->toArray();
    }
}
