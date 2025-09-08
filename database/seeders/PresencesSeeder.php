<?php

namespace Database\Seeders;

use App\Models\Cours;
use App\Models\Membre;
use App\Models\Presence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PresencesSeeder extends Seeder
{
    /**
     * Présences de test pour StudiosDB
     */
    public function run(): void
    {
        // Récupérer les cours et membres avec des requêtes SQL directes
        $cours = \DB::select('SELECT * FROM cours WHERE actif = 1');
        $membres = \DB::select('SELECT * FROM membres');
        $instructeur = User::first(); // Utiliser le premier utilisateur comme instructeur

        if (empty($cours) || empty($membres)) {
            $this->command->error('❌ Aucun cours ou membre trouvé. Veuillez exécuter les seeders CoursSeeder et MembresSeeder d\'abord.');

            return;
        }

        $presences = [];
        $aujourdHui = Carbon::now();

        // Créer des présences pour les 2 dernières semaines
        for ($jour = 0; $jour < 14; $jour++) {
            $dateCours = $aujourdHui->copy()->subDays($jour);

            foreach ($cours as $cour) {
                // Vérifier si le cours a lieu ce jour-là
                if ($this->coursALieuCeJour($cour, $dateCours)) {
                    // Pour chaque membre, créer une présence aléatoire
                    foreach ($membres as $membre) {
                        $statut = $this->genererStatutPresenceAleatoire();
                        $heureArrivee = null;

                        if ($statut === 'present') {
                            // Générer une heure d'arrivée légèrement aléatoire
                            $heureArrivee = Carbon::createFromTimeString($cour->heure_debut)
                                ->addMinutes(rand(-5, 15))
                                ->format('H:i:s');
                        } elseif ($statut === 'retard') {
                            $heureArrivee = Carbon::createFromTimeString($cour->heure_debut)
                                ->addMinutes(rand(10, 30))
                                ->format('H:i:s');
                        }

                        $presences[] = [
                            'cours_id' => $cour->id,
                            'membre_id' => $membre->id,
                            'instructeur_id' => $instructeur->id,
                            'date_cours' => $dateCours->format('Y-m-d'),
                            'statut' => $statut,
                            'heure_arrivee' => $heureArrivee,
                            'notes' => $this->genererNoteAleatoire($statut),
                            'validation_parent' => strtotime($membre->date_naissance) > strtotime($aujourdHui->copy()->subYears(18)->format('Y-m-d')) ? rand(0, 1) : 1,
                        ];
                    }
                }
            }
        }

        foreach ($presences as $presence) {
            Presence::create($presence);
        }

        $this->command->info('✅ '.count($presences).' présences de test créées pour les 2 dernières semaines !');
    }

    /**
     * Vérifie si un cours a lieu un jour donné
     */
    private function coursALieuCeJour($cour, Carbon $date): bool
    {
        $jourSemaine = strtolower($date->format('l'));

        // Mapping français des jours
        $mappingJours = [
            'monday' => 'lundi',
            'tuesday' => 'mardi',
            'wednesday' => 'mercredi',
            'thursday' => 'jeudi',
            'friday' => 'vendredi',
            'saturday' => 'samedi',
            'sunday' => 'dimanche',
        ];

        return $cour->jour_semaine === ($mappingJours[$jourSemaine] ?? '');
    }

    /**
     * Génère un statut de présence aléatoire
     */
    private function genererStatutPresenceAleatoire(): string
    {
        $statuts = ['present', 'present', 'present', 'present', 'retard', 'absent', 'excuse'];

        return $statuts[array_rand($statuts)];
    }

    /**
     * Génère une note aléatoire selon le statut
     */
    private function genererNoteAleatoire(string $statut): ?string
    {
        $notes = [
            'present' => [
                'Très bonne participation',
                'Bon travail technique',
                'Progrès constants',
                'Excellent comportement',
                null, // Parfois pas de note
            ],
            'retard' => [
                'Arrivée en retard justifiée',
                'Retard de '.rand(5, 20).' minutes',
                'Problème de transport',
            ],
            'absent' => [
                'Absence justifiée - maladie',
                'Absence non justifiée',
                'Problème familial',
            ],
            'excuse' => [
                'Rendez-vous médical',
                'Compétition sportive',
                'Voyage familial',
            ],
        ];

        $notesPossibles = $notes[$statut] ?? [null];

        return $notesPossibles[array_rand($notesPossibles)];
    }
}
