<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEcoleScope;

class Seminaire extends Model
{
    use HasFactory, HasEcoleScope;

    protected $fillable = [
        'ecole_id',
        'nom',
        'description',
        'date_debut',
        'date_fin',
        'lieu',
        'instructeur_principal_id',
        'prix',
        'capacite_max',
        'statut',
        'ouvert_externe',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'prix' => 'decimal:2',
        'ouvert_externe' => 'boolean',
    ];

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function instructeurPrincipal()
    {
        return $this->belongsTo(User::class, 'instructeur_principal_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(InscriptionSeminaire::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'inscriptions_seminaires')
            ->withPivot('statut', 'date_inscription', 'paiement_id', 'notes')
            ->withTimestamps();
    }
}
