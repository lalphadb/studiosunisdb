<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoursHoraire extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cours_horaires';

    protected $fillable = [
        'cours_id',
        'jour',
        'heure_debut',
        'heure_fin',
        'salle',
        'instructeur_id',
        'notes'
    ];

    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i'
    ];

    /**
     * Jours de la semaine autorisés
     */
    const JOURS_SEMAINE = [
        'lundi' => 'Lundi',
        'mardi' => 'Mardi', 
        'mercredi' => 'Mercredi',
        'jeudi' => 'Jeudi',
        'vendredi' => 'Vendredi',
        'samedi' => 'Samedi',
        'dimanche' => 'Dimanche'
    ];

    /**
     * Relation vers le cours
     */
    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    /**
     * Relation vers l'instructeur
     */
    public function instructeur(): BelongsTo
    {
        return $this->belongsTo(Membre::class, 'instructeur_id');
    }

    /**
     * Scope pour filtrer par jour
     */
    public function scopeParJour($query, $jour)
    {
        return $query->where('jour', $jour);
    }

    /**
     * Scope pour les horaires actifs (cours actifs)
     */
    public function scopeActifs($query)
    {
        return $query->whereHas('cours', function($q) {
            $q->where('statut', 'actif');
        });
    }

    /**
     * Obtenir l'emoji du jour
     */
    public function getJourEmojiAttribute(): string
    {
        $emojis = [
            'lundi' => '🌅',
            'mardi' => '🌞',
            'mercredi' => '⭐',
            'jeudi' => '🌙',
            'vendredi' => '✨',
            'samedi' => '🌈',
            'dimanche' => '🌸'
        ];

        return $emojis[$this->jour] ?? '📅';
    }

    /**
     * Obtenir le libellé du jour
     */
    public function getJourLibelleAttribute(): string
    {
        return self::JOURS_SEMAINE[$this->jour] ?? ucfirst($this->jour);
    }

    /**
     * Formatage de l'horaire complet
     */
    public function getHoraireFormateAttribute(): string
    {
        $debut = $this->heure_debut instanceof \DateTime 
            ? $this->heure_debut->format('H:i')
            : $this->heure_debut;
            
        $fin = $this->heure_fin instanceof \DateTime 
            ? $this->heure_fin->format('H:i')
            : $this->heure_fin;
            
        return "{$this->jour_emoji} {$this->jour_libelle} {$debut}-{$fin}";
    }

    /**
     * Vérifier si l'horaire est en conflit avec un autre
     */
    public function estEnConflit(string $jour, string $heureDebut, string $heureFin, ?int $excludeId = null): bool
    {
        $query = self::where('jour', $jour)
            ->where('cours_id', $this->cours_id)
            ->where(function($q) use ($heureDebut, $heureFin) {
                $q->whereBetween('heure_debut', [$heureDebut, $heureFin])
                  ->orWhereBetween('heure_fin', [$heureDebut, $heureFin])
                  ->orWhere(function($subQ) use ($heureDebut, $heureFin) {
                      $subQ->where('heure_debut', '<=', $heureDebut)
                           ->where('heure_fin', '>=', $heureFin);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Obtenir la durée en minutes
     */
    public function getDureeMinutesAttribute(): int
    {
        $debut = \Carbon\Carbon::createFromFormat('H:i', $this->heure_debut);
        $fin = \Carbon\Carbon::createFromFormat('H:i', $this->heure_fin);
        
        return $fin->diffInMinutes($debut);
    }

    /**
     * Obtenir tous les horaires d'une semaine pour un cours
     */
    public static function horairesHebdomadaires(int $coursId): array
    {
        $horaires = self::where('cours_id', $coursId)
            ->orderByRaw("FIELD(jour, 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche')")
            ->orderBy('heure_debut')
            ->get();

        $semaine = [];
        foreach (self::JOURS_SEMAINE as $jour => $libelle) {
            $semaine[$jour] = [
                'libelle' => $libelle,
                'emoji' => ['lundi' => '🌅', 'mardi' => '🌞', 'mercredi' => '⭐', 'jeudi' => '🌙', 'vendredi' => '✨', 'samedi' => '🌈', 'dimanche' => '🌸'][$jour],
                'horaires' => $horaires->where('jour', $jour)->values()
            ];
        }

        return $semaine;
    }
}
