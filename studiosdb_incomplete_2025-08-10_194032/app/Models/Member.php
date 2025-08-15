<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected $fillable = [
        'ecole_id','user_id','numero','prenom','nom','date_naissance','courriel','telephone','adresse',
        'date_inscription','statut','ceinture_actuelle_id','photo_path','remarques'
    ];
    protected $casts = [
        'date_naissance'=>'date','date_inscription'=>'date'
    ];

    public function ecole(): BelongsTo { return $this->belongsTo(School::class, 'ecole_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function ceintureActuelle(): BelongsTo { return $this->belongsTo(Belt::class, 'ceinture_actuelle_id'); }
    public function progressions(): HasMany { return $this->hasMany(MemberBeltProgression::class); }
    public function liensFamiliaux(): HasMany { return $this->hasMany(FamilyLink::class, 'member_id'); }

    public function scopeForSchool($q, $ecoleId){ return $q->where('ecole_id',$ecoleId); }

    public function getNomCompletAttribute(): string { return trim("{$this->prenom} {$this->nom}"); }
}
