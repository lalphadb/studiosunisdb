<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCeinture extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ceinture_id',
        'date_obtention',
        'evaluateur_id',
        'notes',
        'certificat_numero',
    ];

    protected $casts = [
        'date_obtention' => 'date',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ceinture(): BelongsTo
    {
        return $this->belongsTo(Ceinture::class);
    }

    public function evaluateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluateur_id');
    }

    // Accessors
    public function getTempsDepuisObtentionAttribute(): string
    {
        $mois = $this->date_obtention->diffInMonths(now());
        
        if ($mois < 1) {
            return 'Récent';
        } elseif ($mois < 12) {
            return $mois . ' mois';
        } else {
            $annees = floor($mois / 12);
            $moisRestants = $mois % 12;
            
            if ($moisRestants === 0) {
                return $annees . ' an' . ($annees > 1 ? 's' : '');
            } else {
                return $annees . 'a ' . $moisRestants . 'm';
            }
        }
    }

    // Boot pour génération certificat
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($userCeinture) {
            if (empty($userCeinture->certificat_numero)) {
                $year = now()->year;
                $lastCertificat = static::where('certificat_numero', 'like', "CERT-{$year}-%")
                    ->latest('id')
                    ->first();
                    
                $nextNumber = $lastCertificat ? 
                    intval(substr($lastCertificat->certificat_numero, -4)) + 1 : 1;
                    
                $userCeinture->certificat_numero = sprintf(
                    'CERT-%d-%04d',
                    $year,
                    $nextNumber
                );
            }
        });
    }
}
