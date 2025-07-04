<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\UserCeinture::class => \App\Policies\UserCeinturePolicy::class,
        \App\Models\Ecole::class => \App\Policies\EcolePolicy::class,
        \App\Models\Cours::class => \App\Policies\CoursPolicy::class,
        \App\Models\Paiement::class => \App\Policies\PaiementPolicy::class,
        \App\Models\Presence::class => \App\Policies\PresencePolicy::class,
        \App\Models\SessionCours::class => \App\Policies\SessionCoursPolicy::class,
        \App\Models\Seminaire::class => \App\Policies\SeminairePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
