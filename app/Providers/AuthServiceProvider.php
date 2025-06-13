<?php

namespace App\Providers;

use App\Models\Cours;
use App\Models\Membre;
use App\Models\Ecole;
use App\Policies\CoursPolicy;
use App\Policies\MembrePolicy;
use App\Policies\EcolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Cours::class => CoursPolicy::class,
        Membre::class => MembrePolicy::class,
        Ecole::class => EcolePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Gates supplémentaires pour permissions granulaires
        Gate::define('access-all-ecoles', function ($user) {
            return $user->hasRole('superadmin');
        });

        Gate::define('manage-own-ecole', function ($user) {
            return $user->hasAnyRole(['admin', 'instructeur']) && $user->ecole_id;
        });
    }
}
