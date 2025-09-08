<?php

namespace App\Providers;

use App\Models\Ceinture;
use App\Models\Cours;
use App\Models\Paiement;
use App\Models\Presence;
use App\Models\User;
use App\Policies\CeinturePolicy;
use App\Policies\CoursPolicy;
use App\Policies\PaiementPolicy;
use App\Policies\PresencePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // SUPPRIMÉ: Membre::class => MembrePolicy::class (fusionné dans User)
        User::class => UserPolicy::class,
        Cours::class => CoursPolicy::class,
        Presence::class => PresencePolicy::class,
        Paiement::class => PaiementPolicy::class,
        Ceinture::class => CeinturePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if ($user->hasRole('superadmin')) {
                return true;
            }

            return null;
        });

        Gate::define('admin-panel', function ($user) {
            return $user->hasAnyRole(['superadmin', 'admin_ecole']);
        });
    }
}
