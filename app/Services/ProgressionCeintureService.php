<?php

namespace App\Services;

use App\Models\Ceinture;
use App\Models\Membre;
use App\Models\ProgressionCeinture;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgressionCeintureService
{
    /**
     * Faire progresser un membre vers une nouvelle ceinture
     */
    public function progresserMembre(
        Membre $membre,
        Ceinture $nouvelleCeinture,
        ?string $notes = null,
        string $typeProgression = 'attribution_manuelle'
    ): bool {

        // Vérifications de sécurité
        $this->validerProgression($membre, $nouvelleCeinture);

        return DB::transaction(function () use ($membre, $nouvelleCeinture, $notes, $typeProgression) {
            // Créer l'entrée de progression dans la table examens/progressions existante
            // Pour l'instant on utilise la table progression_ceintures existante
            $progression = ProgressionCeinture::create([
                'membre_id' => $membre->id,
                'ecole_id' => $membre->ecole_id,
                'ceinture_actuelle_id' => $membre->ceinture_actuelle_id ?? 1, // Ceinture précédente
                'ceinture_cible_id' => $nouvelleCeinture->id,
                'instructeur_id' => Auth::id(),
                'statut' => 'certifie', // Progression manuelle = directement certifiée
                'date_eligibilite' => now()->toDateString(),
                'date_examen' => now()->toDateString(),
                'notes_instructeur' => $notes,
                'note_finale' => 100, // Attribution manuelle = réussite
            ]);

            // Mettre à jour la ceinture actuelle du membre
            $membre->update([
                'ceinture_actuelle_id' => $nouvelleCeinture->id,
            ]);

            // Log d'audit
            if (function_exists('activity')) {
                activity('progression_ceintures')
                    ->performedOn($progression)
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'membre_id' => $membre->id,
                        'membre_nom' => $membre->nom_complet,
                        'ancienne_ceinture' => $membre->ceintureActuelle?->nom ?? 'Aucune',
                        'nouvelle_ceinture' => $nouvelleCeinture->nom,
                        'type_progression' => $typeProgression,
                        'notes' => $notes,
                    ])
                    ->log('progression_ceinture.created');
            }

            return true;
        });
    }

    /**
     * Vérifier si une progression est valide
     */
    public function peutProgresser(Membre $membre, Ceinture $nouvelleCeinture): array
    {
        $validations = [
            'peut_progresser' => true,
            'raisons_blocage' => [],
            'criteres_requis' => [],
            'criteres_atteints' => [],
        ];

        // Vérifier si la ceinture suivante est logique
        if ($membre->ceinture_actuelle_id) {
            $ceintureActuelle = $membre->ceintureActuelle;
            if ($nouvelleCeinture->order <= $ceintureActuelle->order) {
                $validations['peut_progresser'] = false;
                $validations['raisons_blocage'][] = 'La nouvelle ceinture doit être supérieure à la ceinture actuelle';
            }

            // Vérifier si on ne "saute" pas de ceintures (optionnel en mode manuel)
            $prochaineCeinture = $ceintureActuelle->suivante();
            if ($prochaineCeinture && $nouvelleCeinture->id !== $prochaineCeinture->id) {
                $validations['raisons_blocage'][] = "Progression séquentielle recommandée. Prochaine ceinture: {$prochaineCeinture->nom}";
                // Note: on n'empêche pas la progression, juste un avertissement
            }
        }

        // Vérifier les critères minimum de temps
        $derniereProgression = $this->getDerniereProgression($membre);
        if ($derniereProgression) {
            $tempsEcoule = Carbon::parse($derniereProgression->date_examen)->diffInMonths(now());
            $tempsRequis = $nouvelleCeinture->minimum_duration_months;

            $validations['criteres_requis']['temps_minimum'] = "{$tempsRequis} mois";
            $validations['criteres_atteints']['temps_ecoule'] = "{$tempsEcoule} mois";

            if ($tempsEcoule < $tempsRequis) {
                $validations['raisons_blocage'][] = "Temps minimum recommandé non atteint ({$tempsEcoule}/{$tempsRequis} mois)";
                // Note: en mode manuel, on peut forcer
            }
        }

        // Vérifier les présences (approximatif si pas de système de présences détaillé)
        $presencesRequises = $nouvelleCeinture->minimum_attendances;
        $presencesActuelles = $membre->presences()->count(); // À ajuster selon système réel

        $validations['criteres_requis']['presences_minimum'] = $presencesRequises;
        $validations['criteres_atteints']['presences_actuelles'] = $presencesActuelles;

        if ($presencesActuelles < $presencesRequises) {
            $validations['raisons_blocage'][] = "Présences recommandées insuffisantes ({$presencesActuelles}/{$presencesRequises})";
        }

        return $validations;
    }

    /**
     * Obtenir l'historique des progressions d'un membre
     */
    public function getHistoriqueProgression(Membre $membre): \Illuminate\Database\Eloquent\Collection
    {
        return ProgressionCeinture::where('membre_id', $membre->id)
            ->with(['ceintureActuelle', 'ceintureCible', 'instructeur'])
            ->orderBy('created_at', 'desc') // Utiliser created_at au lieu de date_obtention
            ->get();
    }

    /**
     * Obtenir la dernière progression d'un membre
     */
    public function getDerniereProgression(Membre $membre): ?ProgressionCeinture
    {
        return ProgressionCeinture::where('membre_id', $membre->id)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Obtenir les progressions récentes (pour le dashboard)
     */
    public function getProgressionsRecentes(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return ProgressionCeinture::with(['membre', 'ceintureCible', 'instructeur'])
            ->where('statut', 'certifie')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les statistiques de progression
     */
    public function getStatistiquesProgression(): array
    {
        $stats = [
            'progressions_mois' => ProgressionCeinture::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('statut', 'certifie')
                ->count(),
            'progressions_annee' => ProgressionCeinture::whereYear('created_at', now()->year)
                ->where('statut', 'certifie')
                ->count(),
            'total_progressions' => ProgressionCeinture::where('statut', 'certifie')->count(),
        ];

        // Répartition par ceinture
        $stats['repartition_ceintures'] = Ceinture::withCount('membres')->get()
            ->map(function ($ceinture) {
                return [
                    'ceinture' => $ceinture->nom,
                    'couleur' => $ceinture->couleur_hex,
                    'nombre_membres' => $ceinture->membres_count,
                ];
            });

        return $stats;
    }

    /**
     * Suggestions de progression automatique
     */
    public function getSuggestionsProgression(): \Illuminate\Database\Eloquent\Collection
    {
        return Membre::with(['ceintureActuelle'])
            ->whereHas('ceintureActuelle')
            ->get()
            ->filter(function ($membre) {
                if (! $membre->ceintureActuelle) {
                    return false;
                }

                $prochaineCeinture = $membre->ceintureActuelle->suivante();
                if (! $prochaineCeinture) {
                    return false;
                }

                $validation = $this->peutProgresser($membre, $prochaineCeinture);

                // Suggérer même si pas parfait (mode manuel)
                return count($validation['raisons_blocage']) <= 2; // Tolérance
            })
            ->values();
    }

    /**
     * Valider une progression (méthode privée)
     */
    private function validerProgression(Membre $membre, Ceinture $nouvelleCeinture): void
    {
        if (! $membre->exists) {
            throw new \InvalidArgumentException('Le membre doit exister en base de données');
        }

        if (! $nouvelleCeinture->active) {
            throw new \InvalidArgumentException('La ceinture cible doit être active');
        }

        // Les validations métier peuvent être optionnelles en mode manuel
        // mais on garde les validations de sécurité de base
    }
}
