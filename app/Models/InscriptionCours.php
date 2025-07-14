<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InscriptionCours extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inscriptions_cours';

    protected $fillable = [
        'user_id',
        'cours_id',
        'date_inscription',
        'date_debut',
        'date_fin',
        'statut',
        'type_paiement',
        'tarif_applique',
        'notes',
    ];

    protected $casts = [
        'date_inscription' => 'date',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'tarif_applique' => 'decimal:2',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    // Accessors
    public function getStatutColorAttribute(): string
    {
        return match($this->statut) {
            'active' => 'success',
            'suspendue' => 'warning',
            'terminee' => 'gray',
            default => 'gray'
        };
    }

    public function getTypePaiementLabelAttribute(): string
    {
        return match($this->type_paiement) {
            'mensuel' => '💳 Mensuel',
            'seance' => '💰 Par séance',
            'carte' => '🎫 Carte 10 cours',
            default => '❓ Non défini'
        };
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }
}
