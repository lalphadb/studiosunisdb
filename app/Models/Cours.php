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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'description',
        'instructeur_id',
        'niveau',
        'age_min',
        'age_max',
        'places_max',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'date_debut',
        'date_fin',
        'tarif_mensuel',
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
        'tarif_mensuel' => 'decimal:2',
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
    ];

    /**
     * Get the instructor for the course.
     */
    public function instructeur()
    {
        return $this->belongsTo(User::class, 'instructeur_id');
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

    /**
     * Scope a query to only include active courses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope a query to only include courses for a specific level.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $niveau
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNiveau($query, $niveau)
    {
        return $query->where('niveau', $niveau);
    }

    /**
     * Scope a query to only include courses on a specific day.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $jour
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJour($query, $jour)
    {
        return $query->where('jour_semaine', $jour);
    }

    /**
     * Scope a query to only include courses for a specific age.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $age
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePourAge($query, $age)
    {
        return $query->where('age_min', '<=', $age)
                     ->where('age_max', '>=', $age);
    }

    /**
     * Scope a query to only include courses with available places.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvecPlacesDisponibles($query)
    {
        return $query->whereRaw('places_max > (SELECT COUNT(*) FROM cours_membres WHERE cours_id = cours.id AND statut = "actif")');
    }

    /**
     * Get the number of available places.
     *
     * @return int
     */
    public function getPlacesDisponiblesAttribute()
    {
        return max(0, $this->places_max - $this->membresActifs()->count());
    }

    /**
     * Get the fill rate percentage.
     *
     * @return float
     */
    public function getTauxRemplissageAttribute()
    {
        if ($this->places_max == 0) return 0;
        return round(($this->membresActifs()->count() / $this->places_max) * 100, 2);
    }

    /**
     * Get the complete schedule string.
     *
     * @return string
     */
    public function getHoraireCompletAttribute()
    {
        return ucfirst($this->jour_semaine) . ' ' . 
               Carbon::parse($this->heure_debut)->format('H:i') . ' - ' . 
               Carbon::parse($this->heure_fin)->format('H:i');
    }

    /**
     * Get the registration status.
     *
     * @return string
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
     *
     * @return Carbon|null
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
            'dimanche' => 7,
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

    /**
     * Check if a member can enroll in this course.
     *
     * @param  \App\Models\Membre  $membre
     * @return bool
     */
    public function peutInscrire(Membre $membre)
    {
        // Vérifier l'âge
        $age = $membre->age;
        if ($age < $this->age_min || $age > $this->age_max) {
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

        // Vérifier les prérequis (ceinture)
        if ($this->prerequis) {
            // Logique pour vérifier la ceinture du membre
            // À implémenter selon votre système de ceintures
        }

        return true;
    }

    /**
     * Enroll a member in the course.
     *
     * @param  \App\Models\Membre  $membre
     * @return bool
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
     *
     * @param  \App\Models\Membre  $membre
     * @param  string  $raison
     * @return bool
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
     * Get statistics for the course.
     *
     * @return array
     */
    public function getStatistiques()
    {
        $totalPresences = $this->presences()->count();
        $presencesPresents = $this->presences()->where('statut', 'present')->count();
        $tauxPresence = $totalPresences > 0 ? ($presencesPresents / $totalPresences) * 100 : 0;

        return [
            'membres_inscrits' => $this->membresActifs()->count(),
            'places_disponibles' => $this->places_disponibles,
            'taux_remplissage' => $this->taux_remplissage,
            'total_presences' => $totalPresences,
            'taux_presence' => round($tauxPresence, 2),
            'revenue_mensuel' => $this->membresActifs()->count() * $this->tarif_mensuel,
        ];
    }

    /**
     * Generate color for calendar display.
     *
     * @return string
     */
    public function getCouleurCalendrierAttribute()
    {
        if ($this->couleur) {
            return $this->couleur;
        }

        // Couleurs par défaut selon le niveau
        $couleurs = [
            'debutant' => '#10b981', // green
            'intermediaire' => '#3b82f6', // blue
            'avance' => '#8b5cf6', // purple
            'competition' => '#ef4444', // red
        ];

        return $couleurs[$this->niveau] ?? '#6b7280'; // gray par défaut
    }

    /**
     * Check if course conflicts with another schedule.
     *
     * @param  string  $jour
     * @param  string  $heureDebut
     * @param  string  $heureFin
     * @return bool
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
     * Get upcoming sessions for the course.
     *
     * @param  int  $limit
     * @return \Illuminate\Support\Collection
     */
    public function prochainesSeances($limit = 5)
    {
        $seances = collect();
        $date = $this->prochaine_seance;

        for ($i = 0; $i < $limit; $i++) {
            $seances->push([
                'date' => $date->copy(),
                'jour' => ucfirst($this->jour_semaine),
                'heure_debut' => $this->heure_debut,
                'heure_fin' => $this->heure_fin,
            ]);
            $date->addWeek();
        }

        return $seances;
    }

    /**
     * Format course for calendar display.
     *
     * @return array
     */
    public function pourCalendrier()
    {
        return [
            'id' => $this->id,
            'title' => $this->nom,
            'start' => $this->heure_debut,
            'end' => $this->heure_fin,
            'color' => $this->couleur_calendrier,
            'instructor' => $this->instructeur->name,
            'level' => $this->niveau,
            'enrolled' => $this->membresActifs()->count(),
            'capacity' => $this->places_max,
        ];
    }
}
