<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'birth_date',
        'gender',
        'address',
        'city',
        'postal_code',
        'province',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'status',
        'current_belt_id',
        'registration_date',
        'last_attendance_date',
        'medical_notes',
        'allergies',
        'medications',
        'consent_photos',
        'consent_communications',
        'consent_date',
        'user_id',
        'family_id',
        'custom_fields',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'registration_date' => 'date',
        'last_attendance_date' => 'date',
        'consent_date' => 'datetime',
        'consent_photos' => 'boolean',
        'consent_communications' => 'boolean',
        'allergies' => 'array',
        'medications' => 'array',
        'custom_fields' => 'array',
    ];

    protected $appends = [
        'full_name',
        'age',
        'is_minor',
        'membership_duration',
    ];

    // ========== RELATIONS ==========

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function currentBelt(): BelongsTo
    {
        return $this->belongsTo(Belt::class, 'current_belt_id');
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_members')
            ->withPivot(['registration_date', 'status'])
            ->withTimestamps();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function beltProgressions(): HasMany
    {
        return $this->hasMany(BeltProgression::class);
    }

    // ========== SCOPES ==========

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) return $query;
        
        return $query->where(function ($q) use ($term) {
            $q->where('first_name', 'like', "%{$term}%")
              ->orWhere('last_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', 'inactive');
    }

    public function scopeWithBelt(Builder $query, int $beltId): Builder
    {
        return $query->where('current_belt_id', $beltId);
    }

    public function scopeMinors(Builder $query): Builder
    {
        return $query->whereDate('birth_date', '>', now()->subYears(18));
    }

    public function scopeAdults(Builder $query): Builder
    {
        return $query->whereDate('birth_date', '<=', now()->subYears(18));
    }

    public function scopeRegisteredBetween(Builder $query, $start, $end): Builder
    {
        return $query->whereBetween('registration_date', [$start, $end]);
    }

    public function scopeWithRecentAttendance(Builder $query, int $days = 30): Builder
    {
        return $query->where('last_attendance_date', '>=', now()->subDays($days));
    }

    // ========== ACCESSEURS ==========

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute(): int
    {
        return $this->birth_date ? $this->birth_date->age : 0;
    }

    public function getIsMinorAttribute(): bool
    {
        return $this->age < 18;
    }

    public function getMembershipDurationAttribute(): string
    {
        if (!$this->registration_date) return '0 jours';
        
        $diff = $this->registration_date->diff(now());
        
        if ($diff->y > 0) {
            return $diff->y . ' an' . ($diff->y > 1 ? 's' : '') . 
                   ($diff->m > 0 ? ' et ' . $diff->m . ' mois' : '');
        } elseif ($diff->m > 0) {
            return $diff->m . ' mois' . ($diff->d > 0 ? ' et ' . $diff->d . ' jours' : '');
        } else {
            return $diff->d . ' jour' . ($diff->d > 1 ? 's' : '');
        }
    }

    // ========== MÉTHODES MÉTIER ==========

    public function updateLastAttendance(): void
    {
        $this->update(['last_attendance_date' => now()]);
    }

    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    public function deactivate(): void
    {
        $this->update(['status' => 'inactive']);
    }

    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);
    }

    public function changeBelt(Belt $belt): void
    {
        $this->update(['current_belt_id' => $belt->id]);
        
        $this->beltProgressions()->create([
            'from_belt_id' => $this->current_belt_id,
            'to_belt_id' => $belt->id,
            'date' => now(),
            'instructor_id' => auth()->id(),
        ]);
    }

    public function hasValidConsent(): bool
    {
        return $this->consent_photos && $this->consent_communications;
    }

    public function needsConsentRenewal(): bool
    {
        if (!$this->consent_date) return true;
        return $this->consent_date->lt(now()->subYear());
    }

    public function getAttendanceRate(int $days = 30): float
    {
        $coursesCount = $this->courses()
            ->where('course_members.status', 'active')
            ->count();
            
        if ($coursesCount === 0) return 0;
        
        $expectedAttendances = $coursesCount * 4 * ($days / 30); // 4 cours/mois en moyenne
        $actualAttendances = $this->attendances()
            ->where('date', '>=', now()->subDays($days))
            ->where('status', 'present')
            ->count();
            
        return min(100, ($actualAttendances / $expectedAttendances) * 100);
    }
}
