<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SessionCours;
use App\Models\Cours;
use Carbon\Carbon;

class SessionsCoursSeeder extends Seeder
{
    /**
     * Sessions de cours de test pour les prochaines semaines
     */
    public function run(): void
    {
        $cours = Cours::all();

        if ($cours->isEmpty()) {
            $this->command->error('❌ Aucun cours trouvé. Veuillez exécuter le CoursSeeder d\'abord.');
            return;
        }

        $sessions = [];
        $aujourdHui = Carbon::now();

        // Créer des sessions pour les 4 prochaines semaines
        for ($semaine = 0; $semaine < 4; $semaine++) {
            foreach ($cours as $cour) {
                // Calculer la date de la session
                $dateSession = $this->calculerDateSession($cour, $semaine);

                if ($dateSession) {
                    $sessions[] = [
                        'cours_id' => $cour->id,
                        'date_heure_debut' => $dateSession->format('Y-m-d') . ' ' . $cour->heure_debut,
                        'date_heure_fin' => $dateSession->format('Y-m-d') . ' ' . $cour->heure_fin,
                        'statut' => $dateSession->isPast() ? 'terminee' : 'planifiee',
                        'capacite_actuelle' => rand(5, $cour->capacite_max),
                        'salle' => $cour->salle,
                        'notes' => null,
                    ];
                }
            }
        }

        foreach ($sessions as $session) {
            SessionCours::create($session);
        }

        $this->command->info('✅ ' . count($sessions) . ' sessions de cours créées pour les 4 prochaines semaines !');
    }

    /**
     * Calcule la date de la session en fonction du jour de la semaine du cours
     */
    private function calculerDateSession(Cours $cour, int $semaineOffset): ?Carbon
    {
        $aujourdHui = Carbon::now();

        // Mapping des jours de la semaine
        $joursMapping = [
            'lundi' => Carbon::MONDAY,
            'mardi' => Carbon::TUESDAY,
            'mercredi' => Carbon::WEDNESDAY,
            'jeudi' => Carbon::THURSDAY,
            'vendredi' => Carbon::FRIDAY,
            'samedi' => Carbon::SATURDAY,
            'dimanche' => Carbon::SUNDAY,
        ];

        if (!isset($joursMapping[$cour->jour_semaine])) {
            return null;
        }

        $jourCible = $joursMapping[$cour->jour_semaine];

        // Trouver la prochaine occurrence de ce jour
        $dateSession = $aujourdHui->copy()->next($jourCible);

        // Ajouter les semaines d'offset
        if ($semaineOffset > 0) {
            $dateSession->addWeeks($semaineOffset);
        }

        return $dateSession;
    }
}
