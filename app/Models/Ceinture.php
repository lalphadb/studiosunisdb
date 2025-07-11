<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEcoleScope;

class Ceinture extends Model
{
    use HasFactory, HasEcoleScope;

    protected $fillable = [
        'ecole_id',
        'nom',
        'nom_anglais',
        'couleur_principale',
        'couleur_secondaire',
        'ordre',
        'description',
        'mois_minimum',
        'cours_minimum',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer',
        'mois_minimum' => 'integer',
        'cours_minimum' => 'integer',
    ];

    /**
     * École relationship
     */
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    /**
     * Users qui ont cette ceinture
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_ceintures')
            ->withPivot('date_obtention', 'numero_certificat', 'evaluateur_id', 'notes')
            ->withTimestamps();
    }

    /**
     * User ceintures
     */
    public function userCeintures()
    {
        return $this->hasMany(UserCeinture::class);
    }

    /**
     * Scope pour ordre
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre');
    }
}
