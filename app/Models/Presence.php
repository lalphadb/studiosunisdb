<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\MultiTenant;

class Presence extends Model
{
    use HasFactory, MultiTenant;

    protected $table = 'presences';

    protected $fillable = [
        'user_id',
        'cours_id',
        'ecole_id',
        'date_presence',
        'statut',
        'notes',
        'heure_arrivee',
        'heure_depart'
    ];

    protected $casts = [
        'date_presence' => 'date',
        'heure_arrivee' => 'datetime:H:i',
        'heure_depart' => 'datetime:H:i'
    ];

    protected $attributes = [
        'statut' => 'present'
    ];

    // ===== RELATIONS =====

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }
}
