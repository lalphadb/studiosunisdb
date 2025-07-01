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
        'user_id',
        'cours_id',
        'cours_horaire_id',
        'session_id',
        'ecole_id',
        'frequence',
        'statut',
        'date_inscription'
    ];

    protected $casts = [
        'date_inscription' => 'datetime'
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    public function coursHoraire(): BelongsTo
    {
        return $this->belongsTo(CoursHoraire::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(SessionCours::class, 'session_id');
    }

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }
}
