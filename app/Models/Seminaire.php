<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seminaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',                    // ✅ Champ réel DB
        'intervenant',           // ✅ Champ réel DB
        'type_seminaire',        // ✅ Champ réel DB
        'niveau_cible',          // ✅ Champ réel DB
        'pre_requis',            // ✅ Champ réel DB
        'ouvert_toutes_ecoles',  // ✅ Champ réel DB
        'materiel_requis',       // ✅ Champ réel DB
        'description',           // ✅ Champ réel DB
        'date_debut',            // ✅ Champ réel DB
        'date_fin',              // ✅ Champ réel DB
        'lieu',                  // ✅ Champ réel DB
        'prix',                  // ✅ Champ réel DB
        'capacite_max',          // ✅ Champ réel DB
        'statut',                 // ✅ Champ réel DB
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'ouvert_toutes_ecoles' => 'boolean',
        'prix' => 'decimal:2',
    ];

    /**
     * 🔄 ACCESSEURS pour compatibilité avec les vues
     */
    public function getTitreAttribute()
    {
        return $this->nom;
    }

    public function getInstructeurAttribute()
    {
        return $this->intervenant;
    }

    public function getTypeAttribute()
    {
        return $this->type_seminaire;
    }

    public function getCoutAttribute()
    {
        return $this->prix;
    }

    public function getMaxParticipantsAttribute()
    {
        return $this->capacite_max;
    }

    public function getCertificatAttribute()
    {
        return false; // Valeur par défaut
    }

    public function getDureeAttribute()
    {
        return 120; // Valeur par défaut 2h
    }

    public function getObjectifsAttribute()
    {
        return null;
    }

    public function getPrerequisAttribute()
    {
        return $this->pre_requis;
    }

    /**
     * 🏫 Relation avec École (simulée - ouvert à toutes)
     */
    public function ecole()
    {
        // Retourner une école par défaut si ouvert à toutes
        if ($this->ouvert_toutes_ecoles) {
            return $this->belongsTo(Ecole::class, 'id', 'id')->withDefault([
                'nom' => 'Toutes les écoles StudiosUnisDB',
            ]);
        }

        return $this->belongsTo(Ecole::class);
    }

    /**
     * 👥 Relation avec Inscriptions
     */
    public function inscriptions()
    {
        return $this->hasMany(InscriptionSeminaire::class);
    }
}
