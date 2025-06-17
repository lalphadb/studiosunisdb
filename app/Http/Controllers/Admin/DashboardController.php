<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Ecole;
use App\Models\Membre;
use App\Models\Presence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // Préparer les données selon le rôle de l'utilisateur
        $data = $this->prepareDataForRole($user);

        return view('admin.dashboard', $data);
    }

    /**
     * Préparer les données selon le rôle de l'utilisateur
     */
    private function prepareDataForRole($user)
    {
        $baseData = [
            'user' => $user,
            'user_role' => $this->getUserPrimaryRole($user),
            'activite_recente' => $this->getActiviteRecente($user),
        ];

        if ($user->hasRole('superadmin')) {
            return array_merge($baseData, $this->getSuperadminData($user));
        } elseif ($user->hasRole('admin')) {
            return array_merge($baseData, $this->getAdminEcoleData($user));
        } elseif ($user->hasRole('instructeur')) {
            return array_merge($baseData, $this->getInstructeurData($user));
        } else {
            return array_merge($baseData, $this->getMembreData($user));
        }
    }

    /**
     * Données pour SuperAdmin - Vue globale
     */
    private function getSuperadminData($user)
    {
        $ecoles_actives = $this->compterEcolesActives();
        $membres_actifs = $this->compterMembresActifs();
        $cours_actifs = $this->compterCoursActifs();

        $stats = [
            'total_ecoles' => Ecole::count(),
            'ecoles_actives' => $ecoles_actives,
            'total_membres' => Membre::count(),
            'membres_actifs' => $membres_actifs,
            'nouveaux_membres_mois' => $this->compterNouveauxMembresMois(),
            'total_cours' => Cours::count(),
            'cours_actifs' => $cours_actifs,
            'presences_semaine' => $this->compterPresencesSemaine(),
            'taux_presence_global' => $this->calculerTauxPresenceGlobal(),
            'revenus_estimation' => $membres_actifs * 75, // 75$ par membre
        ];

        // Top 5 écoles par nombre de membres
        $top_ecoles = Ecole::withCount('membres')
            ->orderBy('membres_count', 'desc')
            ->take(5)
            ->get();

        // Écoles avec le plus de cours
        $ecoles_cours = Ecole::withCount('cours')
            ->having('cours_count', '>', 0)
            ->orderBy('cours_count', 'desc')
            ->take(5)
            ->get();

        // Revenus par école (simulation)
        $revenus_ecoles = Ecole::withCount('membres')
            ->get()
            ->map(function ($ecole) {
                return [
                    'nom' => $ecole->nom,
                    'ville' => $ecole->ville,
                    'revenus' => $ecole->membres_count * 75,
                    'membres' => $ecole->membres_count,
                ];
            })
            ->sortByDesc('revenus')
            ->take(5);

        // Utilisateurs par rôle
        $stats_utilisateurs = [
            'superadmins' => User::role('superadmin')->count(),
            'admins' => User::role('admin')->count(),
            'instructeurs' => User::role('instructeur')->count(),
            'membres_users' => User::role('membre')->count(),
        ];

        return [
            'stats' => $stats,
            'stats_utilisateurs' => $stats_utilisateurs,
            'top_ecoles' => $top_ecoles,
            'ecoles_cours' => $ecoles_cours,
            'revenus_ecoles' => $revenus_ecoles,
        ];
    }

    /**
     * Données pour Admin École - Vue spécifique à son école
     */
    private function getAdminEcoleData($user)
    {
        $ecole = $user->ecole;

        if (! $ecole) {
            $stats = [
                'error' => 'Aucune école assignée à cet administrateur.',
                'ecole_nom' => 'École non assignée',
            ];

            return ['stats' => $stats, 'ecole' => null];
        }

        $membres_actifs = $this->compterMembresActifsEcole($ecole->id);
        $cours_actifs = $this->compterCoursActifsEcole($ecole->id);

        $stats = [
            'ecole_nom' => $ecole->nom,
            'ecole_id' => $ecole->id,
            'ecole_ville' => $ecole->ville,
            'total_membres' => $ecole->membres()->count(),
            'membres_actifs' => $membres_actifs,
            'nouveaux_membres_mois' => $this->compterNouveauxMembresMoisEcole($ecole->id),
            'total_cours' => $ecole->cours()->count(),
            'cours_actifs' => $cours_actifs,
            'presences_semaine' => $this->compterPresencesSemaineEcole($ecole->id),
            'taux_presence' => $this->calculerTauxPresenceEcole($ecole->id),
            'revenus_mois' => $membres_actifs * 75,
            'capacite_max' => $ecole->capacite_max ?? 100,
            'capacite_utilisee' => ($ecole->capacite_max ?? 100) > 0 ?
                round(($ecole->membres()->count() / ($ecole->capacite_max ?? 100)) * 100, 1) : 0,
        ];

        // Membres récents de cette école
        $membres_recents = $ecole->membres()
            ->orderBy('date_inscription', 'desc')
            ->take(5)
            ->get();

        // Cours de cette école
        $cours_populaires = $ecole->cours()
            ->withCount('inscriptions')
            ->orderBy('inscriptions_count', 'desc')
            ->take(5)
            ->get();

        // Instructeurs de cette école
        $instructeurs = User::whereHas('roles', function ($query) {
            $query->where('name', 'instructeur');
        })
            ->where('ecole_id', $ecole->id)
            ->withCount('coursInstructeur')
            ->get();

        return [
            'stats' => $stats,
            'ecole' => $ecole,
            'membres_recents' => $membres_recents,
            'cours_populaires' => $cours_populaires,
            'instructeurs' => $instructeurs,
        ];
    }

    /**
     * Données pour Instructeur
     */
    private function getInstructeurData($user)
    {
        $ecole = $user->ecole;
        $mesCours = $user->coursInstructeur ?? collect();

        $stats = [
            'ecole_nom' => $ecole->nom ?? 'Aucune école',
            'ecole_ville' => $ecole->ville ?? '',
            'mes_cours' => $mesCours->count(),
            'cours_actifs' => $mesCours->count(), // Tous considérés comme actifs pour l'instant
            'total_eleves' => $mesCours->sum(function ($cours) {
                return $cours->inscriptions ? $cours->inscriptions->count() : 0;
            }),
            'presences_aujourd_hui' => $this->compterPresencesAujourdhuiInstructeur($user->id),
            'presences_semaine' => $this->compterPresencesSemaineInstructeur($user->id),
        ];

        // Mes cours avec statistiques
        $mes_cours_stats = $mesCours->map(function ($cours) {
            return [
                'nom' => $cours->nom,
                'statut' => 'actif', // Par défaut
                'inscriptions' => $cours->inscriptions ? $cours->inscriptions->count() : 0,
                'capacite_max' => $cours->capacite_max ?? 20,
                'taux_remplissage' => ($cours->capacite_max ?? 20) > 0 ?
                    round((($cours->inscriptions ? $cours->inscriptions->count() : 0) / ($cours->capacite_max ?? 20)) * 100, 1) : 0,
            ];
        });

        return [
            'stats' => $stats,
            'ecole' => $ecole,
            'mes_cours' => $mesCours,
            'mes_cours_stats' => $mes_cours_stats,
        ];
    }

    /**
     * Données pour Membre
     */
    private function getMembreData($user)
    {
        $stats = [
            'nom_complet' => $user->name,
            'email' => $user->email,
            'ecole_nom' => $user->ecole->nom ?? 'Aucune école',
        ];

        return [
            'stats' => $stats,
        ];
    }

    // ===== MÉTHODES HELPER CORRIGÉES =====

    private function compterEcolesActives()
    {
        try {
            return Ecole::where('statut', 'actif')->count();
        } catch (\Exception $e) {
            return Ecole::count();
        }
    }

    private function compterMembresActifs()
    {
        try {
            return Membre::where('statut', 'actif')->count();
        } catch (\Exception $e) {
            return Membre::count();
        }
    }

    private function compterCoursActifs()
    {
        // CORRECTION: utiliser 'status' au lieu de 'statut'
        try {
            return Cours::where('status', 'actif')->count();
        } catch (\Exception $e) {
            return Cours::count();
        }
    }

    private function compterMembresActifsEcole($ecoleId)
    {
        try {
            return Membre::where('ecole_id', $ecoleId)->where('statut', 'actif')->count();
        } catch (\Exception $e) {
            return Membre::where('ecole_id', $ecoleId)->count();
        }
    }

    private function compterCoursActifsEcole($ecoleId)
    {
        // CORRECTION: utiliser 'status' au lieu de 'statut'
        try {
            return Cours::where('ecole_id', $ecoleId)->where('status', 'actif')->count();
        } catch (\Exception $e) {
            return Cours::where('ecole_id', $ecoleId)->count();
        }
    }

    private function compterNouveauxMembresMois()
    {
        try {
            return Membre::where('date_inscription', '>=', Carbon::now()->startOfMonth())->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function compterNouveauxMembresMoisEcole($ecoleId)
    {
        try {
            return Membre::where('ecole_id', $ecoleId)
                ->where('date_inscription', '>=', Carbon::now()->startOfMonth())
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function compterPresencesSemaine()
    {
        try {
            return Presence::where('date_presence', '>=', Carbon::now()->startOfWeek())->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function compterPresencesSemaineEcole($ecoleId)
    {
        try {
            return Presence::whereHas('cours', function ($query) use ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            })
                ->where('date_presence', '>=', Carbon::now()->startOfWeek())
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function compterPresencesAujourdhuiInstructeur($instructeurId)
    {
        try {
            return Presence::whereHas('cours', function ($query) use ($instructeurId) {
                $query->where('instructeur_id', $instructeurId);
            })
                ->where('date_presence', Carbon::today())
                ->where('statut', 'present')
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function compterPresencesSemaineInstructeur($instructeurId)
    {
        try {
            return Presence::whereHas('cours', function ($query) use ($instructeurId) {
                $query->where('instructeur_id', $instructeurId);
            })
                ->where('date_presence', '>=', Carbon::now()->startOfWeek())
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtenir le rôle principal de l'utilisateur
     */
    private function getUserPrimaryRole($user)
    {
        if ($user->hasRole('superadmin')) {
            return 'superadmin';
        }
        if ($user->hasRole('admin')) {
            return 'admin';
        }
        if ($user->hasRole('instructeur')) {
            return 'instructeur';
        }

        return 'membre';
    }

    /**
     * Obtenir l'activité récente selon le rôle
     */
    private function getActiviteRecente($user)
    {
        if ($user->hasRole('superadmin')) {
            return [
                [
                    'type' => 'ecole',
                    'titre' => 'École activée',
                    'description' => 'Studios Unis Montréal - Nouvelle école activée',
                    'date' => 'Il y a 1 heure',
                    'icon' => '🏫',
                    'color' => 'bg-blue-100 text-blue-800',
                ],
                [
                    'type' => 'membre',
                    'titre' => '12 nouveaux membres',
                    'description' => 'Inscriptions dans 5 écoles différentes',
                    'date' => 'Il y a 3 heures',
                    'icon' => '👥',
                    'color' => 'bg-green-100 text-green-800',
                ],
                [
                    'type' => 'cours',
                    'titre' => '3 nouveaux cours',
                    'description' => 'Karaté Avancé, Kata Perfectionnement, Self-Défense',
                    'date' => 'Il y a 5 heures',
                    'icon' => '🥋',
                    'color' => 'bg-yellow-100 text-yellow-800',
                ],
                [
                    'type' => 'system',
                    'titre' => 'Rapport mensuel généré',
                    'description' => 'Statistiques de performance globales',
                    'date' => 'Il y a 1 jour',
                    'icon' => '📊',
                    'color' => 'bg-purple-100 text-purple-800',
                ],
            ];
        } elseif ($user->hasRole('admin')) {
            return [
                [
                    'type' => 'membre',
                    'titre' => 'Nouveau membre inscrit',
                    'description' => 'Marie Dubois - Ceinture blanche',
                    'date' => 'Il y a 2 heures',
                    'icon' => '👤',
                    'color' => 'bg-green-100 text-green-800',
                ],
                [
                    'type' => 'presence',
                    'titre' => 'Cours terminé',
                    'description' => 'Karaté Débutant - 15 présents / 18 inscrits',
                    'date' => 'Il y a 4 heures',
                    'icon' => '✅',
                    'color' => 'bg-blue-100 text-blue-800',
                ],
                [
                    'type' => 'cours',
                    'titre' => 'Horaire modifié',
                    'description' => 'Karaté Intermédiaire - Nouveau créneau ajouté',
                    'date' => 'Il y a 1 jour',
                    'icon' => '📝',
                    'color' => 'bg-yellow-100 text-yellow-800',
                ],
                [
                    'type' => 'paiement',
                    'titre' => 'Paiements reçus',
                    'description' => '8 paiements mensuels confirmés',
                    'date' => 'Il y a 2 jours',
                    'icon' => '💰',
                    'color' => 'bg-green-100 text-green-800',
                ],
            ];
        } elseif ($user->hasRole('instructeur')) {
            return [
                [
                    'type' => 'cours',
                    'titre' => 'Cours planifié',
                    'description' => 'Karaté Avancé - Demain 19h00',
                    'date' => 'Il y a 1 heure',
                    'icon' => '📅',
                    'color' => 'bg-orange-100 text-orange-800',
                ],
                [
                    'type' => 'presence',
                    'titre' => 'Présences enregistrées',
                    'description' => 'Cours de ce matin - 12 présents',
                    'date' => 'Il y a 6 heures',
                    'icon' => '✅',
                    'color' => 'bg-green-100 text-green-800',
                ],
                [
                    'type' => 'membre',
                    'titre' => 'Nouvel élève assigné',
                    'description' => 'Paul Martin - Karaté Débutant',
                    'date' => 'Il y a 1 jour',
                    'icon' => '👤',
                    'color' => 'bg-blue-100 text-blue-800',
                ],
            ];
        } else {
            return [
                [
                    'type' => 'cours',
                    'titre' => 'Prochain cours',
                    'description' => 'Karaté Débutant - Mercredi 18h30',
                    'date' => 'Dans 2 jours',
                    'icon' => '🥋',
                    'color' => 'bg-blue-100 text-blue-800',
                ],
                [
                    'type' => 'presence',
                    'titre' => 'Cours suivi',
                    'description' => 'Karaté Débutant - Présent',
                    'date' => 'Il y a 2 jours',
                    'icon' => '✅',
                    'color' => 'bg-green-100 text-green-800',
                ],
            ];
        }
    }

    /**
     * Calculer le taux de présence global
     */
    private function calculerTauxPresenceGlobal()
    {
        try {
            $totalPresences = Presence::where('date_presence', '>=', Carbon::now()->startOfMonth())->count();
            $presentsCount = Presence::where('date_presence', '>=', Carbon::now()->startOfMonth())
                ->where('statut', 'present')
                ->count();

            return $totalPresences > 0 ? round(($presentsCount / $totalPresences) * 100, 1) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calculer le taux de présence pour une école
     */
    private function calculerTauxPresenceEcole($ecoleId)
    {
        try {
            $totalPresences = Presence::whereHas('cours', function ($query) use ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            })
                ->where('date_presence', '>=', Carbon::now()->startOfMonth())
                ->count();

            $presentsCount = Presence::whereHas('cours', function ($query) use ($ecoleId) {
                $query->where('ecole_id', $ecoleId);
            })
                ->where('date_presence', '>=', Carbon::now()->startOfMonth())
                ->where('statut', 'present')
                ->count();

            return $totalPresences > 0 ? round(($presentsCount / $totalPresences) * 100, 1) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
