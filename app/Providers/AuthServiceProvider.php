<?php

namespace App\Providers;

use App\Models\Cours;
use App\Models\Ecole;
use App\Models\Membre;
use App\Models\Presence;
use App\Policies\CoursPolicy;
use App\Policies\EcolePolicy;
use App\Policies\MembrePolicy;
use App\Policies\PresencePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Session::class => \App\Policies\SessionPolicy::class,
        \App\Models\Cours::class => \App\Policies\CoursPolicy::class,
        \App\Models\InscriptionCours::class => \App\Policies\InscriptionCoursPolicy::class,
        Ecole::class => EcolePolicy::class,
        Membre::class => MembrePolicy::class,
        Cours::class => CoursPolicy::class,
        Presence::class => PresencePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gates supplémentaires pour permissions granulaires StudiosUnisDB
        Gate::define('access-all-ecoles', function ($user) {
            return $user->hasRole('superadmin');
        });

        Gate::define('manage-own-ecole', function ($user) {
            return $user->hasAnyRole(['admin', 'instructeur']) && $user->ecole_id;
        });
    }
}
