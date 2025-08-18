<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Member;
use App\Policies\MemberPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Member::class => MemberPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Définir les gates si nécessaire
        Gate::define('create_members', function ($user) {
            return $user->hasAnyRole(['admin', 'gestionnaire']);
        });

        Gate::define('update_members', function ($user) {
            return $user->hasAnyRole(['admin', 'gestionnaire']);
        });

        Gate::define('delete_members', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('export_members', function ($user) {
            return $user->hasAnyRole(['admin', 'gestionnaire']);
        });
    }
}

\App\Policies\MemberPolicy::class => \App\Models\Member::class,

