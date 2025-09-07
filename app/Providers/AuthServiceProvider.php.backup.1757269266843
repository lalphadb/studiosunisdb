<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Membre;
use App\Models\Cours;
use App\Models\Presence;
use App\Models\Paiement;
use App\Models\Ceinture;
use App\Models\User;
use App\Policies\MembrePolicy;
use App\Policies\CoursPolicy;
use App\Policies\PresencePolicy;
use App\Policies\PaiementPolicy;
use App\Policies\CeinturePolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Membre::class   => MembrePolicy::class,
        Cours::class    => CoursPolicy::class,
        Presence::class => PresencePolicy::class,
        Paiement::class => PaiementPolicy::class,
        Ceinture::class => CeinturePolicy::class,
        User::class     => UserPolicy::class,
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
