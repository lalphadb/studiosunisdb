<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEcole;

class Consentement extends Model
{
    use HasFactory, BelongsToEcole;
    
    protected $table = 'consentements';
    
    protected $fillable = [
        'membre_id',
        'ecole_id',
        'type',
        'version',
        'consent_given',
        'consent_text',
        'consent_method',
        'consent_details',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'signature_hash',
        'signature_data',
        'guardian_name',
        'guardian_email',
        'guardian_relationship',
        'revoked_at',
        'revocation_reason',
        'revoked_by',
        'expires_at',
    ];
    
    protected $casts = [
        'consent_given' => 'boolean',
        'consent_details' => 'array',
        'revoked_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Types de consentement
    const TYPE_PHOTOS = 'photos';
    const TYPE_COMMUNICATIONS = 'communications';
    const TYPE_DONNEES_MEDICALES = 'donnees_medicales';
    const TYPE_NEWSLETTER = 'newsletter';
    const TYPE_PARTAGE_RESULTATS = 'partage_resultats';
    const TYPE_URGENCE_MEDICALE = 'urgence_medicale';
    
    // Méthodes de consentement
    const METHOD_WEB = 'web';
    const METHOD_PAPIER = 'papier';
    const METHOD_VERBAL = 'verbal';
    const METHOD_EMAIL = 'email';
    const METHOD_SMS = 'sms';
    
    /**
     * Relations
     */
    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }
    
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }
    
    public function revokedBy()
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }
    
    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('consent_given', true)
                    ->whereNull('revoked_at')
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }
    
    public function scopeRevoked($query)
    {
        return $query->whereNotNull('revoked_at');
    }
    
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
                    ->where('expires_at', '<=', now());
    }
    
    public function scopeForType($query, $type)
    {
        return $query->where('type', $type);
    }
    
    public function scopeForMembre($query, $membreId)
    {
        return $query->where('membre_id', $membreId);
    }
    
    public function scopeCurrentVersion($query, $type)
    {
        return $query->where('type', $type)
                    ->orderBy('version', 'desc')
                    ->limit(1);
    }
    
    /**
     * Attributs calculés
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->consent_given 
            && !$this->revoked_at 
            && (!$this->expires_at || $this->expires_at->isFuture());
    }
    
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
    
    public function getIsRevokedAttribute(): bool
    {
        return !is_null($this->revoked_at);
    }
    
    public function getNeedsRenewalAttribute(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        
        // Alerte 30 jours avant expiration
        return $this->expires_at->diffInDays(now()) <= 30;
    }
    
    /**
     * Méthodes
     */
    public function revoke(string $reason = null, User $user = null): bool
    {
        $this->revoked_at = now();
        $this->revocation_reason = $reason;
        $this->revoked_by = $user?->id ?? auth()->id();
        
        // Log la révocation
        AuditLog::log(
            'consent_revoked',
            "Consentement {$this->type} révoqué pour membre #{$this->membre_id}",
            $this,
            ['consent_given' => true],
            ['consent_given' => false],
            AuditLog::SEVERITY_INFO,
            true
        );
        
        return $this->save();
    }
    
    /**
     * Créer un nouveau consentement
     */
    public static function record(
        Membre $membre,
        string $type,
        bool $given,
        string $text,
        string $method = self::METHOD_WEB,
        array $details = [],
        array $guardian = null
    ): self {
        // Déterminer la version
        $lastVersion = static::where('type', $type)
            ->orderBy('version', 'desc')
            ->value('version') ?? '0.0';
        
        $newVersion = static::incrementVersion($lastVersion);
        
        $consentement = new static([
            'membre_id' => $membre->id,
            'ecole_id' => $membre->ecole_id,
            'type' => $type,
            'version' => $newVersion,
            'consent_given' => $given,
            'consent_text' => $text,
            'consent_method' => $method,
            'consent_details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device_type' => static::detectDeviceType(),
            'browser' => static::detectBrowser(),
        ]);
        
        // Si mineur, ajouter info tuteur
        if ($guardian && $membre->age < 18) {
            $consentement->guardian_name = $guardian['name'] ?? null;
            $consentement->guardian_email = $guardian['email'] ?? null;
            $consentement->guardian_relationship = $guardian['relationship'] ?? null;
        }
        
        $consentement->save();
        
        // Log l'enregistrement
        AuditLog::log(
            'consent_recorded',
            "Consentement {$type} enregistré pour membre #{$membre->id}",
            $consentement,
            null,
            ['given' => $given, 'version' => $newVersion],
            AuditLog::SEVERITY_INFO,
            true
        );
        
        return $consentement;
    }
    
    /**
     * Vérifier si un membre a un consentement actif
     */
    public static function hasActiveConsent(Membre $membre, string $type): bool
    {
        return static::forMembre($membre->id)
            ->forType($type)
            ->active()
            ->exists();
    }
    
    /**
     * Obtenir tous les consentements actifs d'un membre
     */
    public static function getActiveConsents(Membre $membre): \Illuminate\Database\Eloquent\Collection
    {
        return static::forMembre($membre->id)
            ->active()
            ->get();
    }
    
    /**
     * Helpers privés
     */
    private static function incrementVersion(string $version): string
    {
        $parts = explode('.', $version);
        $minor = (int)($parts[1] ?? 0) + 1;
        return ($parts[0] ?? '1') . '.' . $minor;
    }
    
    private static function detectDeviceType(): string
    {
        $agent = request()->userAgent();
        
        if (preg_match('/Mobile|Android|iPhone/i', $agent)) {
            return 'mobile';
        } elseif (preg_match('/Tablet|iPad/i', $agent)) {
            return 'tablet';
        }
        
        return 'desktop';
    }
    
    private static function detectBrowser(): string
    {
        $agent = request()->userAgent();
        
        if (preg_match('/Firefox/i', $agent)) {
            return 'Firefox';
        } elseif (preg_match('/Chrome/i', $agent)) {
            return 'Chrome';
        } elseif (preg_match('/Safari/i', $agent)) {
            return 'Safari';
        } elseif (preg_match('/Edge/i', $agent)) {
            return 'Edge';
        }
        
        return 'Other';
    }
}
