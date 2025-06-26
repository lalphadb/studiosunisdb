<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCeinture extends Model
{
    use HasFactory;

    protected $table = 'user_ceintures';

    protected $fillable = [
        'user_id',
        'ceinture_id',
        'date_obtention',
        'examinateur',
        'commentaires',
        'valide',
        'instructeur_id',
        'examen_id',
        'ecole_id',
    ];

    protected $casts = [
        'date_obtention' => 'date',
        'valide' => 'boolean',
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

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function instructeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    // SCOPES MANQUANTS - AJOUT PROFESSIONNEL
    public function scopeForEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    public function scopeRecentes($query, $jours = 30)
    {
        return $query->where('date_obtention', '>=', now()->subDays($jours));
    }

    public function scopeValides($query)
    {
        return $query->where('valide', true);
    }

    public function scopeParCeinture($query, $ceintureId)
    {
        return $query->where('ceinture_id', $ceintureId);
    }

    public function scopeParAnnee($query, $annee = null)
    {
        $annee = $annee ?? now()->year;
        return $query->whereYear('date_obtention', $annee);
    }
}
