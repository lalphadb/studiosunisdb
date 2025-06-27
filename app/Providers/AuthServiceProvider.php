<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Ecole;
use App\Models\Cours;
use App\Models\Seminaire;
use App\Policies\UserPolicy;
use App\Policies\EcolePolicy;
use App\Policies\CoursPolicy;
use App\Policies\SeminairePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Ecole::class => EcolePolicy::class,
        Cours::class => CoursPolicy::class,
        Seminaire::class => SeminairePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
