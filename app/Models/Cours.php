<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToEcole;
use Carbon\Carbon;

class Cours extends Model
{
    use HasFactory, SoftDeletes, BelongsToEcole;

    protected $table = 'cours';

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
        'session', // Automne, hiver, printemps, été
        'heure_debut',
        'heure_fin',
        'date_debut',
        'date_fin',
        'tarif_mensuel',
        'type_tarif',
        'montant',
        'details_tarif',
        'actif',
        'couleur',
        'salle',
        'type_cours',
        'prerequis',
        'materiel_requis',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'age_min' => 'integer',
        'age_max' => 'integer',
        'places_max' => 'integer',
        'tarif_mensuel' => 'decimal:2',
        'montant' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'materiel_requis' => 'array',
    ];

    protected $dates = [
        'date_debut',
        'date_fin',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

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

    // =================== CONSTANTES ===================

    public const NIVEAUX = [
        'tous' => 'Tous niveaux',
        'debutant' => 'Débutant',
        'intermediaire' => 'Intermédiaire',
        'avance' => 'Avancé',
        'prive' => 'Cours privé',
        'competition' => 'Compétition',
        'a_la_carte' => 'À la carte',
    ];

    public const TYPES_TARIF = [
        'mensuel' => 'Mensuel',
        'trimestriel' => 'Trimestriel (3 mois)',
        'horaire' => 'À l\'heure',
        'a_la_carte' => 'À la carte (10 samedis)',
        'autre' => 'Autre (préciser)',
    ];

    public const JOURS_SEMAINE = [
        'lundi' => 'Lundi',
        'mardi' => 'Mardi',
        'mercredi' => 'Mercredi',
        'jeudi' => 'Jeudi',
        'vendredi' => 'Vendredi',
        'samedi' => 'Samedi',
        'dimanche' => 'Dimanche',
    ];

    public const SESSIONS = [
        'automne' => 'Automne',
        'hiver' => 'Hiver', 
        'printemps' => 'Printemps',
        'ete' => 'Été',
    ];

    // =================== RELATIONS ===================

    public function instructeur()
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    public function getInstructeurNomAttribute(): string
    {
        return $this->instructeur ? $this->instructeur->name : 'Non assigné';
    }

    public function membres()
    {
        return $this->belongsToMany(Membre::class, 'cours_membres')
            ->withPivot('date_inscription', 'date_fin', 'statut')
            ->withTimestamps();
    }

    public function membresActifs()
    {
        return $this->membres()->wherePivot('statut', 'actif');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function sessions()
    {
        return $this->hasMany(SessionCours::class);
    }

    // =================== SCOPES ===================

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeNiveau($query, $niveau)
    {
        return $query->where('niveau', $niveau);
    }

    public function scopeJour($query, $jour)
    {
        return $query->where('jour_semaine', $jour);
    }

    public function scopePourAge($query, $age)
    {
        return $query->where('age_min', '<=', $age)
                     ->where(function($q) use ($age) {
                         $q->where('age_max', '>=', $age)
                           ->orWhereNull('age_max');
                     });
    }

    public function scopeAvecPlacesDisponibles($query)
    {
        return $query->whereRaw('places_max > (SELECT COUNT(*) FROM cours_membres WHERE cours_id = cours.id AND statut = "actif")');
    }

    // =================== ACCESSEURS ===================

    public function getNiveauLabelAttribute(): string
    {
        return self::NIVEAUX[$this->niveau] ?? ($this->niveau ?? 'Non défini');
    }

    public function getTypeTarifLabelAttribute(): string
    {
        return self::TYPES_TARIF[$this->type_tarif] ?? ($this->type_tarif ?? 'Non défini');
    }

    public function getAgeRangeAttribute(): string
    {
        $ageMin = $this->age_min ?? 0;
        if (!$this->age_max) {
            return $ageMin . '+ ans';
        }
        return $ageMin . '-' . $this->age_max . ' ans';
    }

    public function getPlacesDisponiblesAttribute()
    {
        return max(0, $this->places_max - $this->membresActifs()->count());
    }

    public function getTauxRemplissageAttribute()
    {
        if ($this->places_max == 0) return 0;
        return round(($this->membresActifs()->count() / $this->places_max) * 100, 2);
    }

    public function getHoraireCompletAttribute(): string
    {
        $jour = self::JOURS_SEMAINE[$this->jour_semaine] ?? ucfirst($this->jour_semaine ?? 'Inconnue');
        $heureDebut = $this->heure_debut ? Carbon::parse($this->heure_debut)->format('H:i') : '00:00';
        $heureFin = $this->heure_fin ? Carbon::parse($this->heure_fin)->format('H:i') : '00:00';
        return $jour . ' ' . $heureDebut . ' - ' . $heureFin;
    }

    public function getStatutInscriptionAttribute(): string
    {
        if (!$this->actif) {
            return 'ferme';
        }

        if ($this->places_disponibles > 0) {
            return 'ouvert';
        }

        return 'complet';
    }

    public function getProchaineSeanceAttribute()
    {
        $jourMap = [
            'lundi' => 1,
            'mardi' => 2,
            'mercredi' => 3,
            'jeudi' => 4,
            'vendredi' => 5,
            'samedi' => 6,
            'dimanche' => 0,
        ];

        $targetDay = $jourMap[$this->jour_semaine] ?? 1;
        $now = Carbon::now();
        $nextSession = $now->copy();

        if ($now->dayOfWeek == $targetDay) {
            $courseTime = Carbon::parse($this->heure_debut);
            if ($now->format('H:i') > $courseTime->format('H:i')) {
                $nextSession->addWeek();
            }
        } else {
            while ($nextSession->dayOfWeek != $targetDay) {
                $nextSession->addDay();
            }
        }

        $time = Carbon::parse($this->heure_debut);
        $nextSession->setHour($time->hour)->setMinute($time->minute)->setSecond(0);

        return $nextSession;
    }

    public function getCouleurCalendrierAttribute(): string
    {
        if ($this->couleur) {
            return $this->couleur;
        }

        $couleurs = [
            'tous' => '#6b7280',
            'debutant' => '#10b981',
            'intermediaire' => '#3b82f6',
            'avance' => '#8b5cf6',
            'prive' => '#f59e0b',
            'competition' => '#ef4444',
            'a_la_carte' => '#06b6d4',
        ];

        return $couleurs[$this->niveau] ?? '#6b7280';
    }

    // =================== MÉTHODES BUSINESS ===================

    public function peutInscrire(Membre $membre)
    {
        $age = $membre->age;
        if ($age < $this->age_min) {
            return false;
        }
        
        if ($this->age_max && $age > $this->age_max) {
            return false;
        }

        if ($this->places_disponibles <= 0) {
            return false;
        }

        if ($this->membres()->where('membre_id', $membre->id)->exists()) {
            return false;
        }

        return true;
    }

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

    public function desinscrireMembre(Membre $membre, $raison = null)
    {
        $this->membres()->updateExistingPivot($membre->id, [
            'date_fin' => now(),
            'statut' => 'inactif',
        ]);

        return true;
    }

    public function conflitHoraire($jour, $heureDebut, $heureFin)
    {
        if ($this->jour_semaine !== $jour) {
            return false;
        }

        $debut1 = Carbon::parse($this->heure_debut);
        $fin1 = Carbon::parse($this->heure_fin);
        $debut2 = Carbon::parse($heureDebut);
        $fin2 = Carbon::parse($heureFin);

        return !($fin1 <= $debut2 || $debut1 >= $fin2);
    }

    public function getStatistiques()
    {
        $totalPresences = $this->presences()->count();
        $presencesPresents = $this->presences()->where('statut', 'present')->count();
        $tauxPresence = $totalPresences > 0 ? ($presencesPresents / $totalPresences) * 100 : 0;

        $membresInscrits = $this->membresActifs()->count();
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

    private function getRevenuEstime($membresInscrits)
    {
        switch ($this->type_tarif) {
            case 'mensuel':
                return $membresInscrits * $this->montant;
            case 'trimestriel':
                return $membresInscrits * $this->montant / 3;
            case 'horaire':
                return $membresInscrits * $this->montant * 4;
            case 'a_la_carte':
                return $membresInscrits * $this->montant / 2.5;
            default:
                return $membresInscrits * $this->montant;
        }
    }

    public function prochainesSeances($limit = 5)
    {
        $seances = collect();
        $date = $this->prochaine_seance;

        for ($i = 0; $i < $limit; $i++) {
            $seances->push([
                'date' => $date->copy(),
                'jour' => self::JOURS_SEMAINE[$this->jour_semaine] ?? ucfirst($this->jour_semaine ?? 'inconnue'),
                'heure_debut' => $this->heure_debut,
                'heure_fin' => $this->heure_fin,
            ]);
            $date->addWeek();
        }

        return $seances;
    }

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

    // =================== MÉTHODES DUPLICATION ===================

    public function duppliquerPourJour($nouveauJour)
    {
        $nouveauCours = $this->replicate();
        $nouveauCours->jour_semaine = $nouveauJour;
        $nouveauCours->nom = $this->nom . ' (' . ucfirst($nouveauJour) . ')';
        $nouveauCours->actif = false;
        $nouveauCours->created_at = now();
        $nouveauCours->updated_at = now();
        
        $nouveauCours->save();
        
        return $nouveauCours;
    }

    public function duppliquerPourSession($nouvelleSession)
    {
        $nouveauCours = $this->replicate();
        $nouveauCours->session = $nouvelleSession;
        $nouveauCours->nom = $this->nom . ' (' . (self::SESSIONS[$nouvelleSession] ?? ucfirst($nouvelleSession)) . ')';
        $nouveauCours->actif = false;
        $nouveauCours->created_at = now();
        $nouveauCours->updated_at = now();
        
        $nouveauCours = $this->adapterDatesSession($nouveauCours, $nouvelleSession);
        
        $nouveauCours->save();
        
        return $nouveauCours;
    }

    private function adapterDatesSession($cours, $session)
    {
        $year = date('Y');
        
        switch($session) {
            case 'automne':
                $cours->date_debut = Carbon::parse("$year-09-01");
                $cours->date_fin = Carbon::parse("$year-12-15");
                break;
            case 'hiver':
                $cours->date_debut = Carbon::parse("$year-01-08");
                $cours->date_fin = Carbon::parse("$year-03-31");
                break;
            case 'printemps':
                $cours->date_debut = Carbon::parse("$year-04-01");
                $cours->date_fin = Carbon::parse("$year-06-30");
                break;
            case 'ete':
                $cours->date_debut = Carbon::parse("$year-07-01");
                $cours->date_fin = Carbon::parse("$year-08-31");
                break;
        }
        
        return $cours;
    }
}
