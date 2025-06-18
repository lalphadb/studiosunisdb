<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'membre_id',
        'ecole_id', 
        'user_id',
        'reference_interne',
        'type_paiement',
        'motif',
        'description',
        'montant',
        'frais',
        'montant_net',
        'email_expediteur',
        'nom_expediteur', 
        'reference_interac',
        'message_interac',
        'statut',
        'date_facture',
        'date_echeance',
        'date_reception',
        'date_validation',
        'periode_facturation',
        'annee_fiscale',
        'recu_fiscal_emis',
        'metadonnees',
        'notes_admin'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'frais' => 'decimal:2', 
        'montant_net' => 'decimal:2',
        'date_facture' => 'datetime',
        'date_echeance' => 'datetime',
        'date_reception' => 'datetime',
        'date_validation' => 'datetime',
        'recu_fiscal_emis' => 'boolean',
        'metadonnees' => 'array'
    ];

    // Relations
    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function validateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes pour filtrage par école
    public function scopeForEcole(Builder $query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    public function scopeStatut(Builder $query, $statut)
    {
        return $query->where('statut', $statut);
    }

    public function scopeEnAttente(Builder $query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAValider(Builder $query)
    {
        return $query->where('statut', 'recu');
    }

    // Méthodes utilitaires
    public function genererReference()
    {
        $prefix = 'SU-' . strtoupper($this->ecole->code) . '-' . date('Y');
        $numero = str_pad($this->id, 3, '0', STR_PAD_LEFT);
        
        $this->reference_interne = $prefix . '-' . $numero;
        $this->save();
        
        return $this->reference_interne;
    }

    public function calculerMontantNet()
    {
        $this->montant_net = $this->montant - $this->frais;
        $this->save();
        
        return $this->montant_net;
    }

    public function marquerCommeRecu()
    {
        $this->statut = 'recu';
        $this->date_reception = now();
        $this->save();
        
        // Log activity
        activity()
            ->performedOn($this)
            ->withProperties(['statut_precedent' => 'en_attente'])
            ->log('Paiement marqué comme reçu');
    }

    public function valider(User $validateur)
    {
        $this->statut = 'valide';
        $this->date_validation = now();
        $this->user_id = $validateur->id;
        $this->save();
        
        // Log activity
        activity()
            ->performedOn($this)
            ->causedBy($validateur)
            ->withProperties(['statut_precedent' => 'recu'])
            ->log('Paiement validé');
    }

    // Accesseurs
    public function getStatutBadgeAttribute()
    {
        $badges = [
            'en_attente' => 'bg-yellow-100 text-yellow-800',
            'recu' => 'bg-blue-100 text-blue-800', 
            'valide' => 'bg-green-100 text-green-800',
            'rejete' => 'bg-red-100 text-red-800',
            'rembourse' => 'bg-gray-100 text-gray-800'
        ];
        
        return $badges[$this->statut] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatutTextAttribute()
    {
        $textes = [
            'en_attente' => 'En attente',
            'recu' => 'Reçu - À valider',
            'valide' => 'Validé', 
            'rejete' => 'Rejeté',
            'rembourse' => 'Remboursé'
        ];
        
        return $textes[$this->statut] ?? 'Inconnu';
    }

    // Méthodes spécifiques aux sessions trimestrielles Studios Unis
    public function getSessionTextAttribute()
    {
        $sessions = [
            'session_automne' => 'Session Automne (Sep-Oct-Nov)',
            'session_hiver' => 'Session Hiver (Déc-Jan-Fév)',
            'session_printemps' => 'Session Printemps (Mar-Avr-Mai)',
            'session_ete' => 'Session Été (Jun-Jul-Aoû)', 
            'seminaire' => 'Séminaire',
            'examen_ceinture' => 'Examen de ceinture',
            'equipement' => 'Équipement',
            'autre' => 'Autre'
        ];
        
        return $sessions[$this->motif] ?? 'Inconnu';
    }

    public function getSessionCourante()
    {
        $mois = now()->month;
        
        // Septembre, Octobre, Novembre = Automne
        if ($mois >= 9 && $mois <= 11) {
            return 'session_automne';
        }
        // Décembre, Janvier, Février = Hiver  
        elseif ($mois == 12 || $mois >= 1 && $mois <= 2) {
            return 'session_hiver';
        }
        // Mars, Avril, Mai = Printemps
        elseif ($mois >= 3 && $mois <= 5) {
            return 'session_printemps';
        }
        // Juin, Juillet, Août = Été
        else {
            return 'session_ete';
        }
    }

    public function genererPeriodeFacturation()
    {
        $year = now()->year;
        $sessions = [
            'session_automne' => $year . '-Q1', // Sep-Nov
            'session_hiver' => ($year + 1) . '-Q2', // Déc-Fév (traverse l'année)
            'session_printemps' => $year . '-Q3', // Mar-Mai
            'session_ete' => $year . '-Q4' // Jun-Aoû
        ];
        
        return $sessions[$this->motif] ?? now()->format('Y-m');
    }

    public function getSessionCouleurAttribute()
    {
        $couleurs = [
            'session_automne' => 'bg-orange-100 text-orange-800',
            'session_hiver' => 'bg-blue-100 text-blue-800',
            'session_printemps' => 'bg-green-100 text-green-800',
            'session_ete' => 'bg-yellow-100 text-yellow-800',
            'seminaire' => 'bg-purple-100 text-purple-800',
            'examen_ceinture' => 'bg-red-100 text-red-800',
            'equipement' => 'bg-gray-100 text-gray-800',
            'autre' => 'bg-slate-100 text-slate-800'
        ];
        
        return $couleurs[$this->motif] ?? 'bg-gray-100 text-gray-800';
    }
}
