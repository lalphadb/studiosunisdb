<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Ecole::class => \App\Policies\EcolePolicy::class,
        \App\Models\Cours::class => \App\Policies\CoursPolicy::class,
        \App\Models\Ceinture::class => \App\Policies\CeinturePolicy::class,
        \App\Models\Seminaire::class => \App\Policies\SeminairePolicy::class,
        \App\Models\Paiement::class => \App\Policies\PaiementPolicy::class,
        \App\Models\Presence::class => \App\Policies\PresencePolicy::class,
        \App\Models\SessionCours::class => \App\Policies\SessionCoursPolicy::class,
        \App\Models\CoursHoraire::class => \App\Policies\CoursHorairePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        
        // ============================================
        // GATES SÉCURISÉS POUR DASHBOARDS
        // ============================================
        
        /**
         * Gate principal pour accès dashboard
         * Vérifications sécuritaires strictes :
         * 1. Rôle autorisé
         * 2. École assignée (multi-tenant)
         * 3. Permission explicite
         */
        Gate::define('view-dashboard', function (User $user) {
            return $user->hasAnyRole(['super_admin', 'admin_ecole', 'instructeur']) 
                   && $user->ecole_id !== null
                   && $user->hasPermissionTo('view-dashboard');
        });
        
        /**
         * Gate Super Admin Dashboard
         * Accès complet toutes écoles
         */
        Gate::define('view-super-admin-dashboard', function (User $user) {
            return $user->hasRole('super_admin');
        });
        
        /**
         * Gate Admin École Dashboard
         * Accès restreint à son école uniquement
         */
        Gate::define('view-admin-ecole-dashboard', function (User $user) {
            return $user->hasRole('admin_ecole') 
                   && $user->ecole_id !== null
                   && $user->hasPermissionTo('view-dashboard');
        });
        
        /**
         * Gate Instructeur Dashboard
         * Accès limité aux fonctions d'instruction
         */
        Gate::define('view-instructeur-dashboard', function (User $user) {
            return $user->hasRole('instructeur') 
                   && $user->ecole_id !== null
                   && $user->hasPermissionTo('view-dashboard');
        });
        
        // ============================================
        // GATES MULTI-TENANT SÉCURISÉS
        // ============================================
        
        /**
         * Vérifier que l'utilisateur peut accéder aux données de son école
         */
        Gate::define('access-school-data', function (User $user, $ecoleId = null) {
            // Super admin peut accéder à toutes les écoles
            if ($user->hasRole('super_admin')) {
                return true;
            }
            
            // Admin école et instructeur : seulement leur école
            return $user->ecole_id !== null && 
                   ($ecoleId === null || $user->ecole_id === $ecoleId);
        });
    }
}
