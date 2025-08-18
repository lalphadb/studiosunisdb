<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Membre extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'prenom',
        'nom',
        'date_naissance',
        'sexe',
        'telephone',
        'adresse',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'statut',
        'ceinture_actuelle_id',
        'date_inscription',
        'date_derniere_presence',
        'date_derniere_progression',
        'notes_medicales',
        'consentement_photos',
        'consentement_communications',
        'photo'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_naissance' => 'date',
        'date_inscription' => 'date',
        'date_derniere_presence' => 'date',
        'date_derniere_progression' => 'date',
        'consentement_photos' => 'boolean',
        'consentement_communications' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['nom_complet', 'age', 'photo_url'];

    /**
     * Get the user associated with the member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the current belt of the member.
     */
    public function ceintureActuelle(): BelongsTo
    {
        return $this->belongsTo(Ceinture::class, 'ceinture_actuelle_id');
    }

    /**
     * Get the presences for the member.
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Get the payments for the member.
     */
    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    /**
     * Get the courses the member is enrolled in.
     */
    public function cours(): BelongsToMany
    {
        return $this->belongsToMany(Cours::class, 'cours_membres')
            ->withPivot('date_inscription', 'date_fin', 'statut')
            ->withTimestamps();
    }

    /**
     * Get the belt progressions for the member.
     */
    public function progressionsCeintures(): HasMany
    {
        return $this->hasMany(ProgressionCeinture::class);
    }

    /**
     * Get the member's full name.
     */
    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    /**
     * Get the member's age.
     */
    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->date_naissance)->age;
    }

    /**
     * Get the member's photo URL.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo) {
            return null;
        }

        return asset('storage/' . $this->photo);
    }

    /**
     * Scope for active members.
     */
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope for members with recent activity.
     */
    public function scopeRecentActivity($query, $days = 30)
    {
        return $query->where('date_derniere_presence', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Check if member is eligible for belt exam.
     */
    public function isEligibleForExam(): bool
    {
        if (!$this->ceintureActuelle) {
            return false;
        }

        $presencesRequises = $this->ceintureActuelle->presences_minimum ?? 24;
        $presencesActuelles = $this->presences()
            ->where('statut', 'present')
            ->where('date_cours', '>=', $this->date_derniere_progression ?? $this->date_inscription)
            ->count();

        $dureeMinimum = $this->ceintureActuelle->duree_minimum_mois ?? 3;
        $dateEligible = ($this->date_derniere_progression ?? $this->date_inscription)
            ->addMonths($dureeMinimum);

        return $presencesActuelles >= $presencesRequises && Carbon::now()->gte($dateEligible);
    }

    /**
     * Update last presence date.
     */
    public function updateLastPresence(): void
    {
        $this->update([
            'date_derniere_presence' => Carbon::now()
        ]);
    }

    /**
     * Get member's attendance rate for current month.
     */
    public function getAttendanceRate(): float
    {
        $expectedPresences = $this->cours()->where('actif', true)->count() * 4;
        
        if ($expectedPresences == 0) {
            return 0;
        }

        $actualPresences = $this->presences()
            ->where('statut', 'present')
            ->where('date_cours', '>=', Carbon::now()->startOfMonth())
            ->count();

        return round(($actualPresences / $expectedPresences) * 100, 2);
    }

    /**
     * Get member's payment status.
     */
    public function getPaymentStatus(): string
    {
        $overduePayments = $this->paiements()
            ->where('statut', 'en_retard')
            ->count();

        if ($overduePayments > 0) {
            return 'en_retard';
        }

        $pendingPayments = $this->paiements()
            ->where('statut', 'en_attente')
            ->count();

        if ($pendingPayments > 0) {
            return 'en_attente';
        }

        return 'a_jour';
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($membre) {
            // Supprimer la photo si elle existe
            if ($membre->photo && \Storage::disk('public')->exists($membre->photo)) {
                \Storage::disk('public')->delete($membre->photo);
            }
        });
    }
}
