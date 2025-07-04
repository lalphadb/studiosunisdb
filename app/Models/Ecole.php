<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Ecole extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'adresse', 
        'ville',
        'province',
        'code_postal',
        'telephone',
        'email',
        'site_web',
        'description',
        'logo',
        'actif',
        'date_creation',
        'contact_principal',
        'numero_affiliation',
        'type_ecole'
    ];

    protected $casts = [
        'actif' => 'boolean',
        'date_creation' => 'date'
    ];

    protected static function booted()
    {
        parent::booted();
        
        // Isolation multi-tenant : utilisateurs ne voient que leur école
        static::addGlobalScope('school_isolation', function ($builder) {
            if (Auth::check()) {
                $user = Auth::user();
                // Superadmin voit tout, autres utilisateurs seulement leur école
                if (!$user->hasRole('superadmin')) {
                    $builder->where('id', $user->ecole_id);
                }
            }
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function cours()
    {
        return $this->hasMany(Cours::class);
    }

    public function sessions()
    {
        return $this->hasMany(SessionCours::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function seminaires()
    {
        return $this->hasMany(Seminaire::class);
    }
}
