<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\UserCeinture;
use App\Policies\UserCeinturePolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Ecole;
use App\Models\Cours;
use App\Models\Seminaire;
use App\Models\Presence;
use App\Models\Paiement;
use App\Models\Ceinture;
use App\Policies\UserPolicy;
use App\Policies\EcolePolicy;
use App\Policies\CoursPolicy;
use App\Policies\SeminairePolicy;
use App\Policies\PresencePolicy;
use App\Policies\PaiementPolicy;
use App\Policies\CeinturePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        UserCeinture::class => UserCeinturePolicy::class,
        User::class => UserPolicy::class,
        Ecole::class => EcolePolicy::class,
        Cours::class => CoursPolicy::class,
        Seminaire::class => SeminairePolicy::class,
        Presence::class => PresencePolicy::class,
        Paiement::class => PaiementPolicy::class,
        Ceinture::class => CeinturePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user()->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('superadmin') ? true : null;
        });
    }
}
