<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name','email','password','ecole_id','statut','last_login_at'];
    protected $hidden = ['password','remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function ecole(): BelongsTo { return $this->belongsTo(School::class, 'ecole_id'); }

    /** Scope de cloisonnement par école */
    public function scopeForSchool($query, $ecoleId) { return $query->where('ecole_id', $ecoleId); }

    /** Accessor badge statut */
    protected function statutLabel(): Attribute {
        return Attribute::get(fn() => match($this->statut){
            'actif' => 'Actif', 'suspendu' => 'Suspendu', default => 'Inactif'
        });
    }
}
