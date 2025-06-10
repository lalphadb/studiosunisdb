<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InscriptionCours extends Model
{
    use HasFactory;

    protected $table = 'inscriptions_cours';

    protected $fillable = [
        'cours_id',
        'membre_id',
        'date_inscription',
        'status',
        'montant_paye',
        'date_debut_facturation',
        'date_fin_facturation',
        'notes',
    ];

    protected $casts = [
        'date_inscription' => 'date',
        'date_debut_facturation' => 'date',
        'date_fin_facturation' => 'date',
        'montant_paye' => 'decimal:2',
    ];

    // Relations
    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    // Accesseurs
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">✅ Active</span>',
            'suspendue' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">⏸️ Suspendue</span>',
            'annulee' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">❌ Annulée</span>',
            default => ucfirst($this->status ?? 'Inconnu')
        };
    }

    public function getDureeInscriptionAttribute(): string
    {
        if ($this->date_debut_facturation && $this->date_fin_facturation) {
            return $this->date_debut_facturation->format('d/m/Y') . ' → ' . $this->date_fin_facturation->format('d/m/Y');
        }
        return 'Durée non définie';
    }
}
