<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCeinture extends Model
{
    use HasFactory;

    protected $table = 'user_ceintures';

    protected $fillable = [
        'user_id',
        'ceinture_id', 
        'date_obtention',
        'ecole_id',
        'instructeur_id',
        'examen_id',
        'examinateur',     // Colonne existante
        'commentaires',    // Colonne existante  
        'valide',
    ];

    protected $casts = [
        'date_obtention' => 'date',
        'valide' => 'boolean'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ceinture()
    {
        return $this->belongsTo(Ceinture::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function instructeur()
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    // Scopes
    public function scopeForEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    public function scopeRecentes($query, $jours = 30)
    {
        return $query->where('date_obtention', '>=', now()->subDays($jours));
    }

    public function scopeParExamen($query, $examenId)
    {
        return $query->where('examen_id', $examenId);
    }

    // Accesseur pour compatibilité
    public function getNotesAttribute()
    {
        return $this->commentaires;
    }

    public function setNotesAttribute($value)
    {
        $this->attributes['commentaires'] = $value;
    }
}
