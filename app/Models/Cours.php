<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\MultiTenant;

class Cours extends Model
{
    use HasFactory, MultiTenant;

    protected $table = 'cours';

    protected $fillable = [
        'nom',
        'description',
        'ecole_id',
        'statut',
        'periode',
        'archive_date'
    ];

    protected $casts = [
        'ecole_id' => 'integer',
        'archive_date' => 'date'
    ];

    protected $attributes = [
        'statut' => 'actif'
    ];

    // ===== RELATIONS =====

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class, 'ecole_id');
    }

    public function horaires(): HasMany
    {
        return $this->hasMany(CoursHoraire::class, 'cours_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(SessionCours::class, 'cours_id');
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(InscriptionCours::class, 'cours_id');
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'cours_id');
    }

    // ===== SCOPES =====

    /**
     * Cours actifs seulement
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Cours terminés
     */
    public function scopeTermines($query)
    {
        return $query->where('statut', 'termine');
    }

    /**
     * Cours archivés
     */
    public function scopeArchives($query)
    {
        return $query->where('statut', 'archive');
    }

    /**
     * Cours par période
     */
    public function scopePeriode($query, string $periode)
    {
        return $query->where('periode', $periode);
    }

    /**
     * Cours non archivés (actifs + terminés)
     */
    public function scopeNonArchives($query)
    {
        return $query->whereIn('statut', ['actif', 'termine']);
    }

    // ===== MÉTHODES =====

    /**
     * Terminer un cours
     */
    public function terminer(): bool
    {
        if ($this->statut === 'actif') {
            $this->statut = 'termine';
            return $this->save();
        }
        return false;
    }

    /**
     * Archiver un cours
     */
    public function archiver(): bool
    {
        if (in_array($this->statut, ['actif', 'termine'])) {
            $this->statut = 'archive';
            $this->archive_date = now()->toDateString();
            return $this->save();
        }
        return false;
    }

    /**
     * Réactiver un cours
     */
    public function reactiver(): bool
    {
        if (in_array($this->statut, ['termine', 'archive'])) {
            $this->statut = 'actif';
            $this->archive_date = null;
            return $this->save();
        }
        return false;
    }

    /**
     * Vérifier si le cours peut être archivé
     */
    public function peutEtreArchive(): bool
    {
        return in_array($this->statut, ['actif', 'termine']);
    }

    /**
     * Obtenir le badge de statut
     */
    public function getBadgeStatutAttribute(): string
    {
        return match($this->statut) {
            'actif' => '<span class="badge bg-success">Actif</span>',
            'termine' => '<span class="badge bg-warning">Terminé</span>',
            'archive' => '<span class="badge bg-secondary">Archivé</span>',
            default => '<span class="badge bg-light">Inconnu</span>'
        };
    }
}
