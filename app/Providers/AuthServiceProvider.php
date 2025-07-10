<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\{User, Cours, Ceinture, Presence, Paiement, Seminaire, SessionCours, Ecole};
use App\Policies\{UserPolicy, CoursPolicy, CeinturePolicy, PresencePolicy, PaiementPolicy, SeminairePolicy, SessionCoursPolicy, EcolePolicy};

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Cours::class => CoursPolicy::class,
        Ceinture::class => CeinturePolicy::class,
        Presence::class => PresencePolicy::class,
        Paiement::class => PaiementPolicy::class,
        Seminaire::class => SeminairePolicy::class,
        SessionCours::class => SessionCoursPolicy::class,
        Ecole::class => EcolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gates personnalisées pour actions spéciales
        Gate::define('access-telescope', function (User $user) {
            return $user->hasRole('superadmin');
        });

        Gate::define('access-admin-panel', function (User $user) {
            return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin', 'instructeur']);
        });

        Gate::define('manage-global-settings', function (User $user) {
            return $user->hasRole('superadmin');
        });

        Gate::define('export-data', function (User $user) {
            return $user->hasAnyRole(['superadmin', 'admin_ecole', 'admin']);
        });

        Gate::define('view-logs', function (User $user) {
            return $user->hasRole('superadmin');
        });

        // Gate pour validation multi-tenant
        Gate::define('access-ecole-data', function (User $user, int $ecoleId) {
            if ($user->hasRole('superadmin')) return true;
            
            return $user->ecole_id === $ecoleId;
        });

        // SuperAdmin peut tout faire
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('superadmin')) {
                return true;
            }
        });
    }
}
