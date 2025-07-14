<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Cours extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cours';

    protected $fillable = [
        'ecole_id',
        'nom',
        'description',
        'niveau',
        'type_cours',
        'age_minimum',
        'capacite_max',
        'jours_semaine',
        'heure_debut',
        'heure_fin',
        'duree_minutes',
        'salle',
        'mode_paiement',
        'prix',
        'devise',
        'saison',
        'date_debut',
        'date_fin',
        'actif',
        'inscription_ouverte',
        'couleur',
        'cours_parent_id',
        'notes',
    ];

    protected $casts = [
        'jours_semaine' => 'array',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'duree_minutes' => 'integer',
        'age_minimum' => 'integer',
        'capacite_max' => 'integer',
        'prix' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'actif' => 'boolean',
        'inscription_ouverte' => 'boolean',
    ];

    // Relations
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function coursParent(): BelongsTo
    {
        return $this->belongsTo(Cours::class, 'cours_parent_id');
    }

    public function coursDupliques(): HasMany
    {
        return $this->hasMany(Cours::class, 'cours_parent_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(SessionCours::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(InscriptionCours::class);
    }

    // Accessors
    public function getJoursSemaineTextAttribute(): string
    {
        $jours = [
            1 => 'Lun', 2 => 'Mar', 3 => 'Mer', 4 => 'Jeu',
            5 => 'Ven', 6 => 'Sam', 7 => 'Dim'
        ];
        
        return collect($this->jours_semaine)
            ->map(fn($jour) => $jours[$jour] ?? '')
            ->join(', ');
    }

    public function getHoraireCompletAttribute(): string
    {
        return $this->jours_semaine_text . ' ' . 
               $this->heure_debut->format('H:i') . '-' . 
               $this->heure_fin->format('H:i');
    }

    public function getNiveauLabelAttribute(): string
    {
        return match($this->niveau) {
            'tous' => '👥 Tous niveaux',
            'debutant' => '🟢 Débutant',
            'avance' => '🔴 Avancé',
            'prive' => '👤 Privé',
            'a_la_carte' => '🎯 À la carte',
            'combat' => '🥊 Combat',
            'autres' => '📝 Autres',
            default => $this->niveau
        };
    }

    public function getModePaiementLabelAttribute(): string
    {
        return match($this->mode_paiement) {
            'quotidien' => '📅 Quotidien',
            'mensuel' => '📆 Mensuel',
            'trimestriel' => '📋 Trimestriel (3 mois)',
            'autre' => '💰 Autre',
            default => $this->mode_paiement
        };
    }

    public function getSaisonLabelAttribute(): string
    {
        return match($this->saison) {
            'automne' => '🍂 Automne',
            'hiver' => '❄️ Hiver',
            'printemps' => '🌸 Printemps',
            'ete' => '☀️ Été',
            default => '📅 Toute l\'année'
        };
    }

    // Méthodes métier
    public function dupliquer(array $nouveauxJours, ?string $nouvelleSaison = null): self
    {
        $nouveauCours = $this->replicate();
        $nouveauCours->jours_semaine = $nouveauxJours;
        $nouveauCours->cours_parent_id = $this->id;
        
        if ($nouvelleSaison) {
            $nouveauCours->saison = $nouvelleSaison;
        }
        
        $nouveauCours->save();
        
        return $nouveauCours;
    }

    public function genererSessions(Carbon $dateDebut, Carbon $dateFin): int
    {
        $sessionsCreees = 0;
        $date = $dateDebut->copy();
        
        while ($date->lte($dateFin)) {
            // Vérifier si ce jour est dans les jours du cours
            if (in_array($date->dayOfWeek === 0 ? 7 : $date->dayOfWeek, $this->jours_semaine)) {
                $this->sessions()->create([
                    'date' => $date->toDateString(),
                    'heure_debut' => $this->heure_debut,
                    'heure_fin' => $this->heure_fin,
                    'salle' => $this->salle,
                    'statut' => 'programmee',
                ]);
                $sessionsCreees++;
            }
            $date->addDay();
        }
        
        return $sessionsCreees;
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopePourSaison($query, string $saison)
    {
        return $query->where('saison', $saison);
    }

    public function scopePourNiveau($query, string $niveau)
    {
        return $query->where('niveau', $niveau);
    }
}
