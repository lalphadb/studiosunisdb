<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Cours extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cours';

    /**
     * Global scope pour mono-école - DÉSACTIVÉ TEMPORAIREMENT POUR DEBUG
     */
    protected static function booted()
    {
        // Global Scope DÉSACTIVÉ temporairement - causes pages blanches
        /*
        static::addGlobalScope('ecole', function ($query) {
            if (auth()->check() && !auth()->user()->hasRole('superadmin')) {
                try {
                    if (\Schema::hasColumn('cours', 'ecole_id')) {
                        $query->where('ecole_id', auth()->user()->ecole_id);
                    }
                } catch (\Exception $e) {
                    // Ignorer erreur
                }
            }
        });
        */
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'description',
        'instructeur_id',
        'ecole_id',
        'niveau',
        'age_min',
        'age_max',
        'places_max',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'date_debut',
        'date_fin',
        'tarif_mensuel', // Conservé pour compatibilité
        // Nouveau système tarification flexible
        'type_tarif',
        'montant',
        'details_tarif',
        'actif',
        'couleur', // Pour l'affichage dans le calendrier
        'salle', // Salle ou dojo
        'type_cours', // Kata, Combat, Technique, etc.
        'prerequis', // Ceinture minimum requise
        'materiel_requis', // Équipement nécessaire
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'actif' => 'boolean',
        'age_min' => 'integer',
        'age_max' => 'integer',
        'places_max' => 'integer',
        'tarif_mensuel' => 'decimal:2', // Conservé pour compatibilité
        'montant' => 'decimal:2', // Nouveau système
        'date_debut' => 'date',
        'date_fin' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'materiel_requis' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date_debut',
        'date_fin',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'places_disponibles',
        'taux_remplissage',
        'horaire_complet',
        'statut_inscription',
        'prochaine_seance',
        'niveau_label',
        'type_tarif_label',
        'age_range',
    ];

    // =================== CONSTANTES ÉTENDUES ===================
    
    /**
     * Niveaux disponibles - ÉTENDUS selon demande
     */
    public const NIVEAUX = [
        'tous' => 'Tous niveaux',
        'debutant' => 'Débutant',
        'intermediaire' => 'Intermédiaire',
        'avance' => 'Avancé',
        'prive' => 'Cours privé',
        'competition' => 'Compétition',
        'a_la_carte' => 'À la carte',
    ];

    /**
     * Types de tarification - SYSTÈME FLEXIBLE
     */
    public const TYPES_TARIF = [
        'mensuel' => 'Mensuel',
        'trimestriel' => 'Trimestriel (3 mois)',
        'horaire' => 'À l\'heure',
        'a_la_carte' => 'À la carte (10 samedis)',
        'autre' => 'Autre (préciser)',
    ];

    /**
     * Jours de la semaine
     */
    public const JOURS_SEMAINE = [
        'lundi' => 'Lundi',
        'mardi' => 'Mardi',
        'mercredi' => 'Mercredi',
        'jeudi' => 'Jeudi',
        'vendredi' => 'Vendredi',
        'samedi' => 'Samedi',
        'dimanche' => 'Dimanche',
    ];

    // =================== RELATIONS ===================

    /**
     * Get the instructor for the course (optional).
     */
    public function instructeur()
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    /**
     * Get instructor name or default text.
     */
    public function getInstructeurNomAttribute()
    {
        return $this->instructeur ? $this->instructeur->name : 'Non assigné';
    }

    /**
     * Get the school for the course.
     */
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    /**
     * Get the members enrolled in the course.
     */
    public function membres()
    {
        return $this->belongsToMany(Membre::class, 'cours_membres')
            ->withPivot('date_inscription', 'date_fin', 'statut')
            ->withTimestamps();
    }

    /**
     * Get the active members enrolled in the course.
     */
    public function membresActifs()
    {
        return $this->membres()->wherePivot('statut', 'actif');
    }

    /**
     * Get the presences for the course.
     */
    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Get the payments related to the course.
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    /**
     * Get the sessions/events for the course.
     */
    public function sessions()
    {
        return $this->hasMany(SessionCours::class);
    }

    // =================== SCOPES ===================

    /**
     * Scope a query to only include active courses.
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope a query to only include courses for a specific level.
     */
    public function scopeNiveau($query, $niveau)
    {
        return $query->where('niveau', $niveau);
    }

    /**
     * Scope a query to only include courses on a specific day.
     */
    public function scopeJour($query, $jour)
    {
        return $query->where('jour_semaine', $jour);
    }

    /**
     * Scope a query to only include courses for a specific age.
     */
    public function scopePourAge($query, $age)
    {
        return $query->where('age_min', '<=', $age)
                     ->where(function($q) use ($age) {
                         $q->where('age_max', '>=', $age)
                           ->orWhereNull('age_max'); // Gérer âge max optionnel
                     });
    }

    /**
     * Scope a query to only include courses with available places.
     */
    public function scopeAvecPlacesDisponibles($query)
    {
        return $query->whereRaw('places_max > (SELECT COUNT(*) FROM cours_membres WHERE cours_id = cours.id AND statut = "actif")');
    }

    // =================== ACCESSEURS ÉTENDUS ===================

    /**
     * Get niveau label from constants.
     */
    public function getNiveauLabelAttribute(): string
    {
        return self::NIVEAUX[$this->niveau] ?? $this->niveau;
    }

    /**
     * Get type tarif label from constants.
     */
    public function getTypeTarifLabelAttribute(): string
    {
        return self::TYPES_TARIF[$this->type_tarif] ?? $this->type_tarif;
    }

    /**
     * Get age range display.
     */
    public function getAgeRangeAttribute(): string
    {
        if (!$this->age_max) {
            return $this->age_min . '+ ans';
        }
        return $this->age_min . '-' . $this->age_max . ' ans';
    }

    /**
     * Get the number of available places.
     */
    public function getPlacesDisponiblesAttribute()
    {
        return max(0, $this->places_max - $this->membresActifs()->count());
    }

    /**
     * Get the fill rate percentage.
     */
    public function getTauxRemplissageAttribute()
    {
        if ($this->places_max == 0) return 0;
        return round(($this->membresActifs()->count() / $this->places_max) * 100, 2);
    }

    /**
     * Get the complete schedule string.
     */
    public function getHoraireCompletAttribute()
    {
        $jour = self::JOURS_SEMAINE[$this->jour_semaine] ?? ucfirst($this->jour_semaine);
        return $jour . ' ' . 
               Carbon::parse($this->heure_debut)->format('H:i') . ' - ' . 
               Carbon::parse($this->heure_fin)->format('H:i');
    }

    /**
     * Get the registration status.
     */
    public function getStatutInscriptionAttribute()
    {
        if (!$this->actif) {
            return 'ferme';
        }

        if ($this->places_disponibles > 0) {
            return 'ouvert';
        }

        return 'complet';
    }

    /**
     * Get the next session date.
     */
    public function getProchaineSeanceAttribute()
    {
        $jourMap = [
            'lundi' => 1,
            'mardi' => 2,
            'mercredi' => 3,
            'jeudi' => 4,
            'vendredi' => 5,
            'samedi' => 6,
            'dimanche' => 0, // Dimanche = 0
        ];

        $targetDay = $jourMap[$this->jour_semaine] ?? 1;
        $now = Carbon::now();
        $nextSession = $now->copy();

        // Si on est le même jour mais après l'heure du cours
        if ($now->dayOfWeek == $targetDay) {
            $courseTime = Carbon::parse($this->heure_debut);
            if ($now->format('H:i') > $courseTime->format('H:i')) {
                $nextSession->addWeek();
            }
        } else {
            // Trouver le prochain jour de la semaine
            while ($nextSession->dayOfWeek != $targetDay) {
                $nextSession->addDay();
            }
        }

        // Définir l'heure du cours
        $time = Carbon::parse($this->heure_debut);
        $nextSession->setHour($time->hour)->setMinute($time->minute)->setSecond(0);

        return $nextSession;
    }

    // =================== MÉTHODES BUSINESS ===================

    /**
     * Check if a member can enroll in this course.
     */
    public function peutInscrire(Membre $membre)
    {
        // Vérifier l'âge
        $age = $membre->age;
        if ($age < $this->age_min) {
            return false;
        }
        
        // Âge max optionnel
        if ($this->age_max && $age > $this->age_max) {
            return false;
        }

        // Vérifier les places disponibles
        if ($this->places_disponibles <= 0) {
            return false;
        }

        // Vérifier si déjà inscrit
        if ($this->membres()->where('membre_id', $membre->id)->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Enroll a member in the course.
     */
    public function inscrireMembre(Membre $membre)
    {
        if (!$this->peutInscrire($membre)) {
            return false;
        }

        $this->membres()->attach($membre->id, [
            'date_inscription' => now(),
            'statut' => 'actif',
        ]);

        return true;
    }

    /**
     * Unenroll a member from the course.
     */
    public function desinscrireMembre(Membre $membre, $raison = null)
    {
        $this->membres()->updateExistingPivot($membre->id, [
            'date_fin' => now(),
            'statut' => 'inactif',
        ]);

        return true;
    }

    /**
     * Check if course conflicts with another schedule.
     */
    public function conflitHoraire($jour, $heureDebut, $heureFin)
    {
        if ($this->jour_semaine !== $jour) {
            return false;
        }

        $debut1 = Carbon::parse($this->heure_debut);
        $fin1 = Carbon::parse($this->heure_fin);
        $debut2 = Carbon::parse($heureDebut);
        $fin2 = Carbon::parse($heureFin);

        // Vérifier le chevauchement
        return !($fin1 <= $debut2 || $debut1 >= $fin2);
    }

    /**
     * Get statistics for the course - AVEC NOUVEAU SYSTÈME TARIF.
     */
    public function getStatistiques()
    {
        $totalPresences = $this->presences()->count();
        $presencesPresents = $this->presences()->where('statut', 'present')->count();
        $tauxPresence = $totalPresences > 0 ? ($presencesPresents / $totalPresences) * 100 : 0;

        $membresInscrits = $this->membresActifs()->count();
        
        // Revenue selon type tarif
        $revenuEstime = $this->getRevenuEstime($membresInscrits);

        return [
            'membres_inscrits' => $membresInscrits,
            'places_disponibles' => $this->places_disponibles,
            'taux_remplissage' => $this->taux_remplissage,
            'total_presences' => $totalPresences,
            'taux_presence' => round($tauxPresence, 2),
            'revenue_estime' => $revenuEstime,
            'type_tarif' => $this->type_tarif_label,
        ];
    }

    /**
     * Calculer revenu estimé selon type tarif.
     */
    private function getRevenuEstime($membresInscrits)
    {
        switch ($this->type_tarif) {
            case 'mensuel':
                return $membresInscrits * $this->montant;
            case 'trimestriel':
                return $membresInscrits * $this->montant / 3; // Per month
            case 'horaire':
                // Estimation 4 séances par mois
                return $membresInscrits * $this->montant * 4;
            case 'a_la_carte':
                // 10 samedis répartis sur ~2.5 mois
                return $membresInscrits * $this->montant / 2.5;
            default:
                return $membresInscrits * $this->montant;
        }
    }

    /**
     * Generate color for calendar display.
     */
    public function getCouleurCalendrierAttribute()
    {
        if ($this->couleur) {
            return $this->couleur;
        }

        // Couleurs par défaut selon le niveau
        $couleurs = [
            'tous' => '#6b7280', // gray - tous niveaux
            'debutant' => '#10b981', // green
            'intermediaire' => '#3b82f6', // blue
            'avance' => '#8b5cf6', // purple
            'prive' => '#f59e0b', // amber - privé
            'competition' => '#ef4444', // red
            'a_la_carte' => '#06b6d4', // cyan - à la carte
        ];

        return $couleurs[$this->niveau] ?? '#6b7280'; // gray par défaut
    }

    /**
     * Get upcoming sessions for the course.
     */
    public function prochainesSeances($limit = 5)
    {
        $seances = collect();
        $date = $this->prochaine_seance;

        for ($i = 0; $i < $limit; $i++) {
            $seances->push([
                'date' => $date->copy(),
                'jour' => self::JOURS_SEMAINE[$this->jour_semaine] ?? ucfirst($this->jour_semaine),
                'heure_debut' => $this->heure_debut,
                'heure_fin' => $this->heure_fin,
            ]);
            $date->addWeek();
        }

        return $seances;
    }

    /**
     * Format course for calendar display.
     */
    public function pourCalendrier()
    {
        return [
            'id' => $this->id,
            'title' => $this->nom,
            'start' => $this->heure_debut,
            'end' => $this->heure_fin,
            'color' => $this->couleur_calendrier,
            'instructor' => $this->instructeur_nom,
            'level' => $this->niveau,
            'enrolled' => $this->membresActifs()->count(),
            'capacity' => $this->places_max,
            'tarif_info' => $this->type_tarif_label . ' - ' . number_format($this->montant, 2) . '$',
        ];
    }
}
