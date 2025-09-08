<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LienFamilial extends Model
{
    use HasFactory;

    protected $table = 'liens_familiaux';

    protected $fillable = [
        'membre_principal_id',
        'membre_lie_id',
        'type_relation',
        'famille_id',
        'notes',
    ];

    /**
     * Types de relations familiaux autorisés
     */
    const TYPES_RELATIONS = [
        'parent' => 'Parent',
        'enfant' => 'Enfant',
        'conjoint' => 'Conjoint/Conjointe',
        'frere_soeur' => 'Frère/Sœur',
        'grand_parent' => 'Grand-parent',
        'petit_enfant' => 'Petit-enfant',
        'oncle_tante' => 'Oncle/Tante',
        'neveu_niece' => 'Neveu/Nièce',
        'cousin' => 'Cousin(e)',
        'autre' => 'Autre',
    ];

    /**
     * Relation vers le membre principal (User)
     */
    public function membrePrincipal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'membre_principal_id');
    }

    /**
     * Relation vers le membre lié (User)
     */
    public function membreLie(): BelongsTo
    {
        return $this->belongsTo(User::class, 'membre_lie_id');
    }

    /**
     * Scope pour filtrer par famille
     */
    public function scopeParFamille($query, $familleId)
    {
        return $query->where('famille_id', $familleId);
    }

    /**
     * Scope pour filtrer par type de relation
     */
    public function scopeParTypeRelation($query, $type)
    {
        return $query->where('type_relation', $type);
    }

    /**
     * Obtenir tous les membres d'une même famille
     */
    public static function getMembresParFamille($familleId)
    {
        return self::where('famille_id', $familleId)
            ->with(['membrePrincipal', 'membreLie'])
            ->get();
    }

    /**
     * Créer un lien bidirectionnel entre deux membres
     */
    public static function creerLienBidirectionnel($membrePrincipalId, $membreLieId, $typeRelation, $familleId, $notes = null)
    {
        // Types de relations inverses
        $relationsInverses = [
            'parent' => 'enfant',
            'enfant' => 'parent',
            'conjoint' => 'conjoint',
            'frere_soeur' => 'frere_soeur',
            'grand_parent' => 'petit_enfant',
            'petit_enfant' => 'grand_parent',
            'oncle_tante' => 'neveu_niece',
            'neveu_niece' => 'oncle_tante',
            'cousin' => 'cousin',
            'autre' => 'autre',
        ];

        // Créer le lien principal
        $lienPrincipal = self::create([
            'membre_principal_id' => $membrePrincipalId,
            'membre_lie_id' => $membreLieId,
            'type_relation' => $typeRelation,
            'famille_id' => $familleId,
            'notes' => $notes,
        ]);

        // Créer le lien inverse si différent
        if ($membrePrincipalId !== $membreLieId) {
            $typeRelationInverse = $relationsInverses[$typeRelation] ?? $typeRelation;

            self::create([
                'membre_principal_id' => $membreLieId,
                'membre_lie_id' => $membrePrincipalId,
                'type_relation' => $typeRelationInverse,
                'famille_id' => $familleId,
                'notes' => $notes,
            ]);
        }

        return $lienPrincipal;
    }

    /**
     * Obtenir le libellé du type de relation
     */
    public function getTypeRelationLibelleAttribute()
    {
        return self::TYPES_RELATIONS[$this->type_relation] ?? $this->type_relation;
    }
}
