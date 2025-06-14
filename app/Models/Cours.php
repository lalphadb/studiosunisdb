<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cours extends Model
{
    use HasFactory;

    protected $table = 'cours';

    protected $fillable = [
        'ecole_id',
        'instructeur_id',
        'nom',
        'description',
        'niveau_requis',
        'age_min',
        'age_max',
        'capacite_max',
        'duree_minutes',
        'prix',
        'prix_mensuel',
        'prix_session',
        'status',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'type_cours',
        'date_debut',
        'date_fin',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'prix' => 'decimal:2',
        'prix_mensuel' => 'decimal:2',
        'prix_session' => 'decimal:2',
        'age_min' => 'integer',
        'age_max' => 'integer',
    ];

    // Relations
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function instructeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(InscriptionCours::class);
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    public function membres()
    {
        return $this->belongsToMany(Membre::class, 'inscriptions_cours')
                   ->withPivot('date_inscription', 'status', 'montant_paye')
                   ->withTimestamps();
    }

    // Accesseurs
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'actif' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">✅ Actif</span>',
            'inactif' => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">⏸️ Inactif</span>',
            'complet' => '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">🔄 Complet</span>',
            default => ucfirst($this->status ?? 'Inconnu')
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type_cours) {
            'karate' => '🥋 Karaté',
            'boxe' => '🥊 Boxe',
            'kickboxing' => '🦵 Kickboxing',
            'cardiobox' => '💪 Cardio Box',
            'enfants' => '👶 Enfants',
            'adultes' => '👨 Adultes',
            default => ucfirst($this->type_cours ?? 'Non défini')
        };
    }

    public function getJourLabelAttribute(): string
    {
        return match($this->jour_semaine) {
            'lundi' => '📅 Lundi',
            'mardi' => '📅 Mardi',
            'mercredi' => '📅 Mercredi',
            'jeudi' => '📅 Jeudi',
            'vendredi' => '📅 Vendredi',
            'samedi' => '📅 Samedi',
            'dimanche' => '📅 Dimanche',
            default => ucfirst($this->jour_semaine ?? 'Non défini')
        };
    }

    public function getCreneauAttribute(): string
    {
        if ($this->heure_debut && $this->heure_fin) {
            return $this->heure_debut->format('H:i') . ' - ' . $this->heure_fin->format('H:i');
        }
        return 'Horaire non défini';
    }

    public function getAgeRangeAttribute(): string
    {
        if ($this->age_min && $this->age_max) {
            return $this->age_min . ' - ' . $this->age_max . ' ans';
        } elseif ($this->age_min) {
            return $this->age_min . '+ ans';
        } elseif ($this->age_max) {
            return 'Jusqu\'à ' . $this->age_max . ' ans';
        }
        return 'Tous âges';
    }

    public function getPlacesDisponiblesAttribute(): int
    {
        return $this->capacite_max - $this->inscriptions()->where('status', 'active')->count();
    }

    public function getTauxOccupationAttribute(): int
    {
        if ($this->capacite_max == 0) return 0;
        return round(($this->inscriptions()->where('status', 'active')->count() / $this->capacite_max) * 100);
    }
}
