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
        'ceinture_precedente_id',
        'ceinture_nouvelle_id',
        'date_obtention',
        'instructeur_id',
        'notes',
        'examen_id',
        'type_progression', // examen, attribution_manuelle
    ];

    protected $casts = [
        'date_obtention' => 'date',
    ];

    // Relations
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    public function ceinturePrecedente(): BelongsTo
    {
        return $this->belongsTo(Ceinture::class, 'ceinture_precedente_id');
    }

    public function ceintureNouvelle(): BelongsTo
    {
        return $this->belongsTo(Ceinture::class, 'ceinture_nouvelle_id');
    }

    public function instructeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    public function examen(): BelongsTo
    {
        return $this->belongsTo(Examen::class);
    }

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }
}
