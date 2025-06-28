<?php

namespace App\Providers;

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
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Ecole::class => \App\Policies\EcolePolicy::class,
        \App\Models\Cours::class => \App\Policies\CoursPolicy::class,
        \App\Models\Ceinture::class => \App\Policies\CeinturePolicy::class,
        \App\Models\Presence::class => \App\Policies\PresencePolicy::class,
        \App\Models\Paiement::class => \App\Policies\PaiementPolicy::class,
        \App\Models\Seminaire::class => \App\Policies\SeminairePolicy::class,
        
        // Nouvelles policies pour modules Dashboard et Log
        'App\Policies\DashboardPolicy' => \App\Policies\DashboardPolicy::class,
        'App\Policies\LogPolicy' => \App\Policies\LogPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
