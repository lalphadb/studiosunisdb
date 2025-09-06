<?php

namespace App\Models;

use App\Traits\BelongsToEcole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressionCeinture extends Model
{
    use HasFactory, BelongsToEcole;

    protected $table = 'progression_ceintures';

    protected $fillable = [
        'membre_id',
        'ecole_id',
        'ceinture_actuelle_id',
        'ceinture_cible_id',
        'instructeur_id',
        'statut',
        'date_eligibilite',
        'date_examen',
        'notes_instructeur',
        'evaluation_techniques',
        'note_finale',
        'recommandations',
    ];

    protected $casts = [
        'date_eligibilite' => 'date',
        'date_examen' => 'date',
        'evaluation_techniques' => 'array',
        'note_finale' => 'integer',
    ];

    // Statuts possibles
    const STATUT_ELIGIBLE = 'eligible';
    const STATUT_CANDIDAT = 'candidat';
    const STATUT_EXAMEN_PLANIFIE = 'examen_planifie';
    const STATUT_EXAMEN_REUSSI = 'examen_reussi';
    const STATUT_CERTIFIE = 'certifie';
    const STATUT_ECHEC = 'echec';

    // Relations
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    public function ceintureActuelle(): BelongsTo
    {
        return $this->belongsTo(Ceinture::class, 'ceinture_actuelle_id');
    }

    public function ceintureCible(): BelongsTo
    {
        return $this->belongsTo(Ceinture::class, 'ceinture_cible_id');
    }

    // Alias pour compatibilité avec service
    public function ceintureNouvelle(): BelongsTo
    {
        return $this->ceintureCible();
    }

    public function instructeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    // Scopes
    public function scopeCertifie($query)
    {
        return $query->where('statut', self::STATUT_CERTIFIE);
    }

    public function scopeReussi($query)
    {
        return $query->whereIn('statut', [self::STATUT_EXAMEN_REUSSI, self::STATUT_CERTIFIE]);
    }

    public function scopeEnCours($query)
    {
        return $query->whereIn('statut', [self::STATUT_ELIGIBLE, self::STATUT_CANDIDAT, self::STATUT_EXAMEN_PLANIFIE]);
    }

    // Accesseurs
    public function getEstReussieAttribute(): bool
    {
        return in_array($this->statut, [self::STATUT_EXAMEN_REUSSI, self::STATUT_CERTIFIE]);
    }

    public function getEstCertifieeAttribute(): bool
    {
        return $this->statut === self::STATUT_CERTIFIE;
    }

    public function getDateObtentionAttribute()
    {
        // Alias pour compatibilité - utilise date_examen si certifié
        return $this->est_certifiee ? $this->date_examen : null;
    }

    public function getStatutLibelleAttribute(): string
    {
        $libelles = [
            self::STATUT_ELIGIBLE => 'Éligible',
            self::STATUT_CANDIDAT => 'Candidat',
            self::STATUT_EXAMEN_PLANIFIE => 'Examen planifié',
            self::STATUT_EXAMEN_REUSSI => 'Examen réussi',
            self::STATUT_CERTIFIE => 'Certifié',
            self::STATUT_ECHEC => 'Échec',
        ];

        return $libelles[$this->statut] ?? $this->statut;
    }

    public function getNoteFinaleFormatteeAttribute(): string
    {
        if (!$this->note_finale) return 'N/A';
        
        if ($this->note_finale >= 80) return $this->note_finale . '/100 ✅';
        if ($this->note_finale >= 60) return $this->note_finale . '/100 ⚠️';
        return $this->note_finale . '/100 ❌';
    }
}
