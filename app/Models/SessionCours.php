<?php

namespace App\Models;

use App\Traits\HasEcoleScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SessionCours extends Model
{
    use HasFactory, HasEcoleScope;

    /**
     * Table associée
     */
    protected $table = 'session_cours';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cours_id',
        'instructeur_id',
        'date',
        'heure_debut',
        'heure_fin',
        'salle',
        'capacite_max',
        'statut',
        'notes',
        'ecole_id',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'capacite_max' => 'integer',
    ];

    /**
     * Les valeurs par défaut
     */
    protected $attributes = [
        'statut' => 'planifie',
    ];

    /**
     * Relation avec le cours
     */
    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    /**
     * Relation avec l'instructeur
     */
    public function instructeur()
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    /**
     * Relation avec les présences
     */
    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Scope pour les sessions planifiées
     */
    public function scopePlanifiees($query)
    {
        return $query->where('statut', 'planifie');
    }

    /**
     * Scope pour les sessions complétées
     */
    public function scopeCompletees($query)
    {
        return $query->where('statut', 'complete');
    }

    /**
     * Scope pour les sessions annulées
     */
    public function scopeAnnulees($query)
    {
        return $query->where('statut', 'annule');
    }

    /**
     * Scope pour les sessions futures
     */
    public function scopeFutures($query)
    {
        return $query->where('date', '>=', now()->toDateString());
    }

    /**
     * Scope pour les sessions passées
     */
    public function scopePassees($query)
    {
        return $query->where('date', '<', now()->toDateString());
    }

    /**
     * Accesseur pour l'heure de début formatée
     */
    public function getHeureDebutFormatteeAttribute()
    {
        return Carbon::parse($this->heure_debut)->format('H:i');
    }

    /**
     * Accesseur pour l'heure de fin formatée
     */
    public function getHeureFinFormatteeAttribute()
    {
        return Carbon::parse($this->heure_fin)->format('H:i');
    }

    /**
     * Accesseur pour la date et heure complète de début
     */
    public function getDateHeureDebutAttribute()
    {
        return Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->heure_debut);
    }

    /**
     * Accesseur pour la date et heure complète de fin
     */
    public function getDateHeureFinAttribute()
    {
        return Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->heure_fin);
    }

    /**
     * Obtenir le nombre de participants
     */
    public function getNombreParticipants()
    {
        return $this->presences()->where('status', 'present')->count();
    }

    /**
     * Obtenir le nombre d'inscrits
     */
    public function getNombreInscrits()
    {
        return $this->cours->inscriptions()
            ->where('actif', true)
            ->whereDate('date_debut', '<=', $this->date)
            ->where(function ($query) {
                $query->whereNull('date_fin')
                    ->orWhereDate('date_fin', '>=', $this->date);
            })
            ->count();
    }

    /**
     * Vérifier si la session est complète
     */
    public function estComplete()
    {
        $capacite = $this->capacite_max ?? $this->cours->capacite_max;
        
        if (!$capacite) {
            return false;
        }

        return $this->getNombreParticipants() >= $capacite;
    }

    /**
     * Vérifier si la session peut être modifiée
     */
    public function peutEtreModifiee()
    {
        // Ne peut pas modifier une session passée ou annulée
        if ($this->statut === 'annule' || $this->statut === 'complete') {
            return false;
        }

        // Ne peut pas modifier une session qui commence dans moins d'une heure
        if ($this->date_heure_debut->diffInMinutes(now()) < 60) {
            return false;
        }

        return true;
    }

    /**
     * Annuler la session
     */
    public function annuler($raison = null)
    {
        if (!$this->peutEtreModifiee()) {
            throw new \Exception('Cette session ne peut pas être annulée');
        }

        $this->update([
            'statut' => 'annule',
            'notes' => $raison ? "Annulée: {$raison}" : 'Session annulée'
        ]);

        activity()
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->withProperties(['raison' => $raison])
            ->log("Session du {$this->date->format('d/m/Y')} annulée");

        // TODO: Notifier les participants
    }

    /**
     * Marquer la session comme complète
     */
    public function marquerComplete()
    {
        $this->update(['statut' => 'complete']);

        activity()
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->log("Session du {$this->date->format('d/m/Y')} marquée comme complète");
    }

    /**
     * Obtenir les statistiques de présence
     */
    public function getStatistiquesPresence()
    {
        $presences = $this->presences;
        $total = $presences->count();

        return [
            'total' => $total,
            'presents' => $presences->where('status', 'present')->count(),
            'absents' => $presences->where('status', 'absent')->count(),
            'retards' => $presences->where('status', 'retard')->count(),
            'excuses' => $presences->where('status', 'excuse')->count(),
            'taux_presence' => $total > 0 
                ? round(($presences->where('status', 'present')->count() / $total) * 100, 2)
                : 0
        ];
    }

    /**
     * Générer les présences pour tous les inscrits
     */
    public function genererPresences()
    {
        $inscrits = $this->cours->inscriptions()
            ->where('actif', true)
            ->whereDate('date_debut', '<=', $this->date)
            ->where(function ($query) {
                $query->whereNull('date_fin')
                    ->orWhereDate('date_fin', '>=', $this->date);
            })
            ->get();

        foreach ($inscrits as $inscription) {
            Presence::firstOrCreate([
                'user_id' => $inscription->user_id,
                'session_cours_id' => $this->id,
                'ecole_id' => $this->ecole_id
            ], [
                'status' => 'absent',
                'notes' => 'Généré automatiquement'
            ]);
        }

        return $inscrits->count();
    }
}
