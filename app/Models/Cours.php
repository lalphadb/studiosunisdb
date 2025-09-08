<?php

namespace App\Models;

use App\Traits\BelongsToEcole;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cours extends Model
{
    use BelongsToEcole, HasFactory, SoftDeletes;

    protected $fillable = ['ecole_id', 'instructeur_id', 'nom', 'description', 'niveau', 'age_min', 'age_max', 'type_cours', 'jour_semaine', 'session', 'heure_debut', 'heure_fin', 'date_debut', 'date_fin', 'type_tarif', 'montant', 'details_tarif', 'places_max', 'tarif_mensuel', 'couleur', 'salle', 'prerequis', 'actif', 'statut', 'notes', 'materiel_requis']; // cleaned

    protected $casts = ['actif' => 'boolean', 'age_min' => 'integer', 'age_max' => 'integer', 'places_max' => 'integer', 'montant' => 'decimal:2', 'tarif_mensuel' => 'decimal:2', 'date_debut' => 'date', 'date_fin' => 'date', 'materiel_requis' => 'array'];

    protected $dates = [
        'date_debut',
        'date_fin',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = ['places_disponibles', 'taux_remplissage', 'horaire_complet', 'statut_inscription', 'niveau_label'];

    // =================== CONSTANTES ===================

    public const NIVEAUX = ['tous' => 'Tous niveaux', 'debutant' => 'Débutant', 'intermediaire' => 'Intermédiaire', 'avance' => 'Avancé', 'competition' => 'Compétition'];

    public const TYPES_TARIF = ['mensuel' => 'Mensuel', 'trimestriel' => 'Trimestriel', 'horaire' => 'À l\'heure', 'a_la_carte' => 'À la carte', 'autre' => 'Autre'];

    public const JOURS_SEMAINE = ['lundi' => 'Lundi', 'mardi' => 'Mardi', 'mercredi' => 'Mercredi', 'jeudi' => 'Jeudi', 'vendredi' => 'Vendredi', 'samedi' => 'Samedi', 'dimanche' => 'Dimanche'];

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

    // Relation avec les users (anciennement membres)
    // Table pivot: cours_users
    public function users()
    {
        return $this->belongsToMany(User::class, 'cours_users', 'cours_id', 'user_id')
            ->withPivot(['date_inscription', 'date_fin', 'statut_inscription', 'prix_personnalise', 'notes'])
            ->withTimestamps();
    }

    public function usersActifs()
    {
        return $this->users()->wherePivot('statut_inscription', 'actif');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function paiements()
    { // Table paiements n'a pas de cours_id actuellement -> désactivé temporairement
        return $this->hasMany(Paiement::class, 'cours_id')->whereRaw('1=0');
    }

    public function sessions()
    {
        return $this->hasMany(SessionCours::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_cours_id');
    }

    public function enfants()
    {
        return $this->hasMany(self::class, 'parent_cours_id');
    }

    // =================== ACCESSORS SIMPLES POUR FORM EDIT ===================

    public function getHeureDebutAttribute($value)
    {
        if (! $value) {
            return null;
        }
        // Si déjà format H:i le renvoyer tel quel
        if (preg_match('/^\d{2}:\d{2}$/', $value)) {
            return $value;
        }
        try {
            return Carbon::parse($value)->format('H:i');
        } catch (\Throwable $e) {
            return $value;
        }
    }

    public function getHeureFinAttribute($value)
    {
        if (! $value) {
            return null;
        }
        if (preg_match('/^\d{2}:\d{2}$/', $value)) {
            return $value;
        }
        try {
            return Carbon::parse($value)->format('H:i');
        } catch (\Throwable $e) {
            return $value;
        }
    }

    public function getInstructeurNomAttribute(): string
    {
        return $this->instructeur ? $this->instructeur->name : 'Non assigné';
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
            ->where(function ($q) use ($age) {
                $q->where('age_max', '>=', $age)
                    ->orWhereNull('age_max');
            });
    }

    public function scopeAvecPlacesDisponibles($query)
    {
        return $query->whereRaw('places_max > (SELECT COUNT(*) FROM cours_users WHERE cours_id = cours.id AND statut_inscription = "actif")');
    }

    // =================== ACCESSEURS ===================

    public function getNiveauLabelAttribute(): string
    {
        return self::NIVEAUX[$this->niveau] ?? ($this->niveau ?? 'Non défini');
    }

    public function getTypeTarifLabelAttribute(): string
    {
        return self::TYPES_TARIF[$this->type_tarif] ?? 'Non défini';
    }

    public function getAgeRangeAttribute(): string
    {
        return $this->age_max ? $this->age_min.'-'.$this->age_max.' ans' : ($this->age_min ?? 0).'+ ans';
    }

    public function getPlacesDisponiblesAttribute()
    {
        return max(0, $this->places_max - $this->usersActifs()->count());
    }

    public function getTauxRemplissageAttribute()
    {
        if ($this->places_max == 0) {
            return 0;
        }

        return round(($this->usersActifs()->count() / $this->places_max) * 100, 2);
    }

    public function getHoraireCompletAttribute(): string
    {
        $jour = self::JOURS_SEMAINE[$this->jour_semaine] ?? ucfirst($this->jour_semaine ?? 'Inconnue');
        $heureDebut = $this->heure_debut ? Carbon::parse($this->heure_debut)->format('H:i') : '00:00';
        $heureFin = $this->heure_fin ? Carbon::parse($this->heure_fin)->format('H:i') : '00:00';

        return $jour.' '.$heureDebut.' - '.$heureFin;
    }

    public function getStatutInscriptionAttribute(): string
    {
        if (! $this->actif) {
            return 'ferme';
        }

        if ($this->places_disponibles > 0) {
            return 'ouvert';
        }

        return 'complet';
    }

    public function getProchaineSeanceAttribute()
    {
        return null;
    }

    public function getEnfantsCountAttribute(): int
    {
        return 0;
    }

    public function getCouleurCalendrierAttribute(): string
    {
        return $this->couleur ?? ['debutant' => '#10b981', 'intermediaire' => '#3b82f6', 'avance' => '#8b5cf6', 'competition' => '#ef4444', 'tous' => '#6b7280'][$this->niveau] ?? '#6b7280';
    }

    // =================== MÉTHODES BUSINESS ===================

    public function peutInscrire(User $user)
    {
        // Vérifier que c'est un membre karate
        if (! $user->isMembreKarate()) {
            return false;
        }

        $age = $user->age;
        if ($age < $this->age_min) {
            return false;
        }

        if ($this->age_max && $age > $this->age_max) {
            return false;
        }

        if ($this->places_disponibles <= 0) {
            return false;
        }

        if ($this->users()->where('user_id', $user->id)->exists()) {
            return false;
        }

        return true;
    }

    public function inscrireUser(User $user)
    {
        if (! $this->peutInscrire($user)) {
            return false;
        }
        $this->users()->attach($user->id, ['date_inscription' => now(), 'statut_inscription' => 'actif']);

        return true;
    }

    public function desinscrireUser(User $user, $raison = null)
    {
        $this->users()->updateExistingPivot($user->id, ['statut_inscription' => 'termine']);

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

        return ! ($fin1 <= $debut2 || $debut1 >= $fin2);
    }

    public function getStatistiques()
    {
        $totalPresences = $this->presences()->count();
        $presencesPresents = $this->presences()->where('statut', 'present')->count();
        $tauxPresence = $totalPresences > 0 ? ($presencesPresents / $totalPresences) * 100 : 0;

        $usersInscrits = $this->usersActifs()->count();
        $revenuEstime = $this->getRevenuEstime($usersInscrits);

        return [
            'membres_inscrits' => $usersInscrits,
            'places_disponibles' => $this->places_disponibles,
            'taux_remplissage' => $this->taux_remplissage,
            'total_presences' => $totalPresences,
            'taux_presence' => round($tauxPresence, 2),
            'revenue_estime' => $revenuEstime,
            'type_tarif' => $this->type_tarif_label,
        ];
    }

    private function getRevenuEstime($usersInscrits)
    {
        switch ($this->type_tarif) {
            case 'mensuel':
                return $usersInscrits * $this->montant;
            case 'trimestriel':
                return $usersInscrits * $this->montant / 3;
            case 'horaire':
                return $usersInscrits * $this->montant * 4;
            case 'a_la_carte':
                return $usersInscrits * $this->montant / 2.5;
            default:
                return $usersInscrits * $this->montant;
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
            'enrolled' => $this->usersActifs()->count(),
            'capacity' => $this->places_max,
            'tarif_info' => $this->type_tarif_label.' - '.number_format($this->montant, 2).'$',
        ];
    }

    // =================== MÉTHODES DUPLICATION CORRIGÉES ===================

    public function dupliquerJour(string $jour)
    {
        $nouveau = $this->replicate(['duree_minutes']); // Exclure colonne générée
        $nouveau->jour_semaine = $jour;
        $nouveau->nom = $this->nom.' ('.ucfirst($jour).')';
        $nouveau->actif = false;
        $nouveau->save();

        return $nouveau;
    }

    public function dupliquerClone(): self
    {
        $nouveau = $this->replicate(['duree_minutes']); // Exclure colonne générée
        $nouveau->nom = $this->nom.' (Copie)';
        $nouveau->actif = false;
        $nouveau->save();

        return $nouveau;
    }

    public function duppliquerPourSession(string $session): self
    {
        $nouveau = $this->replicate(['duree_minutes']); // Exclure colonne générée
        $nouveau->session = $session;
        $nouveau->nom = $this->nom.' ('.self::SESSIONS[$session].')';
        $nouveau->actif = false;

        // Ajuster les dates selon la session
        $now = now();
        switch ($session) {
            case 'automne':
                $nouveau->date_debut = $now->copy()->month(9)->day(1)->format('Y-m-d');
                $nouveau->date_fin = $now->copy()->month(12)->day(20)->format('Y-m-d');
                break;
            case 'hiver':
                $nouveau->date_debut = $now->copy()->addYear()->month(1)->day(8)->format('Y-m-d');
                $nouveau->date_fin = $now->copy()->addYear()->month(3)->day(15)->format('Y-m-d');
                break;
            case 'printemps':
                $nouveau->date_debut = $now->copy()->month(4)->day(1)->format('Y-m-d');
                $nouveau->date_fin = $now->copy()->month(6)->day(15)->format('Y-m-d');
                break;
            case 'ete':
                $nouveau->date_debut = $now->copy()->month(7)->day(1)->format('Y-m-d');
                $nouveau->date_fin = $now->copy()->month(8)->day(31)->format('Y-m-d');
                break;
        }

        $nouveau->save();

        return $nouveau;
    }
}
