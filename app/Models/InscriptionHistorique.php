<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InscriptionHistorique extends Model
{
    use HasFactory;

    protected $table = 'inscriptions_historique';

    protected $fillable = [
        'user_id',
        'ecole_id', 
        'session_id',
        'cours_choisis',
        'montant_total',
        'statut',
        'date_inscription'
    ];

    protected $casts = [
        'cours_choisis' => 'array',
        'montant_total' => 'decimal:2',
        'date_inscription' => 'datetime'
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(SessionCours::class, 'session_id');
    }

    /**
     * Accesseurs
     */
    public function getCoursNomssAttribute(): array
    {
        $coursIds = collect($this->cours_choisis)->pluck('cours_id')->unique();
        return Cours::whereIn('id', $coursIds)->pluck('nom', 'id')->toArray();
    }

    public function getTotalHorairesAttribute(): int
    {
        return collect($this->cours_choisis)
            ->sum(fn($cours) => count($cours['horaires_ids'] ?? []));
    }

    /**
     * Méthodes métier
     */
    public function suggererEquivalentsPour(SessionCours $nouvelleSession): array
    {
        $suggestions = [];
        
        foreach ($this->cours_choisis as $ancienChoix) {
            $cours = Cours::find($ancienChoix['cours_id']);
            if (!$cours) continue;

            // Chercher horaires équivalents dans nouvelle session
            $horairesEquivalents = CoursHoraire::where('session_id', $nouvelleSession->id)
                ->where('cours_id', $cours->id)
                ->where('ecole_id', $this->ecole_id)
                ->get();

            $suggestions[] = [
                'cours' => $cours,
                'ancien_choix' => $ancienChoix,
                'horaires_disponibles' => $horairesEquivalents,
                'suggestion_auto' => $this->detecterMeilleurMatch($ancienChoix, $horairesEquivalents)
            ];
        }

        return $suggestions;
    }

    private function detecterMeilleurMatch(array $ancienChoix, $horairesDisponibles): array
    {
        $matches = [];
        $anciensHoraires = CoursHoraire::whereIn('id', $ancienChoix['horaires_ids'] ?? [])->get();

        foreach ($anciensHoraires as $ancienHoraire) {
            $match = $horairesDisponibles->first(function ($nouveau) use ($ancienHoraire) {
                return $nouveau->jour_semaine === $ancienHoraire->jour_semaine &&
                       $nouveau->heure_debut === $ancienHoraire->heure_debut;
            });

            if ($match) {
                $matches[] = [
                    'horaire_id' => $match->id,
                    'type_match' => 'identique',
                    'confiance' => 100
                ];
            }
        }

        return $matches;
    }
}
