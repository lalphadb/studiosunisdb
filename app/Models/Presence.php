<?php

namespace App\Models;

use App\Traits\HasEcoleScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory, HasEcoleScope;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'session_cours_id',
        'status',
        'heure_arrivee',
        'heure_depart',
        'notes',
        'ecole_id',
        'marque_par',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'heure_arrivee' => 'datetime:H:i',
        'heure_depart' => 'datetime:H:i',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la session de cours
     */
    public function sessionCours()
    {
        return $this->belongsTo(SessionCours::class);
    }

    /**
     * Relation avec l'utilisateur qui a marqué la présence
     */
    public function marquePar()
    {
        return $this->belongsTo(User::class, 'marque_par');
    }

    /**
     * Scope pour les présences
     */
    public function scopePresents($query)
    {
        return $query->where('status', 'present');
    }

    /**
     * Scope pour les absences
     */
    public function scopeAbsents($query)
    {
        return $query->where('status', 'absent');
    }

    /**
     * Scope pour les retards
     */
    public function scopeRetards($query)
    {
        return $query->where('status', 'retard');
    }

    /**
     * Scope pour les absences excusées
     */
    public function scopeExcuses($query)
    {
        return $query->where('status', 'excuse');
    }

    /**
     * Scope pour une période donnée
     */
    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereHas('sessionCours', function ($q) use ($dateDebut, $dateFin) {
            $q->whereBetween('date', [$dateDebut, $dateFin]);
        });
    }

    /**
     * Marquer comme présent
     */
    public function marquerPresent($heureArrivee = null)
    {
        $this->update([
            'status' => 'present',
            'heure_arrivee' => $heureArrivee ?? now()->format('H:i'),
            'marque_par' => auth()->id()
        ]);

        activity()
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->log("Présence marquée pour {$this->user->nom_complet}");
    }

    /**
     * Marquer comme absent
     */
    public function marquerAbsent()
    {
        $this->update([
            'status' => 'absent',
            'marque_par' => auth()->id()
        ]);

        activity()
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->log("Absence marquée pour {$this->user->nom_complet}");
    }

    /**
     * Marquer comme retard
     */
    public function marquerRetard($heureArrivee = null)
    {
        $this->update([
            'status' => 'retard',
            'heure_arrivee' => $heureArrivee ?? now()->format('H:i'),
            'marque_par' => auth()->id()
        ]);

        activity()
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->log("Retard marqué pour {$this->user->nom_complet}");
    }

    /**
     * Marquer comme excusé
     */
    public function marquerExcuse($raison = null)
    {
        $this->update([
            'status' => 'excuse',
            'notes' => $raison,
            'marque_par' => auth()->id()
        ]);

        activity()
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->log("Absence excusée pour {$this->user->nom_complet}");
    }

    /**
     * Vérifier si c'est un retard
     */
    public function estRetard()
    {
        if (!$this->heure_arrivee || $this->status !== 'present') {
            return false;
        }

        $heureDebut = $this->sessionCours->heure_debut;
        $tolerance = 15; // 15 minutes de tolérance

        return $this->heure_arrivee > $heureDebut->addMinutes($tolerance);
    }

    /**
     * Obtenir la durée de présence
     */
    public function getDureePresence()
    {
        if (!$this->heure_arrivee || !$this->heure_depart) {
            return null;
        }

        return $this->heure_depart->diffInMinutes($this->heure_arrivee);
    }

    /**
     * Obtenir le badge de statut
     */
    public function getBadgeStatut()
    {
        $badges = [
            'present' => '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Présent</span>',
            'absent' => '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Absent</span>',
            'retard' => '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Retard</span>',
            'excuse' => '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Excusé</span>',
        ];

        return $badges[$this->status] ?? '';
    }
}
