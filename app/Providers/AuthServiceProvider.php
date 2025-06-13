<?php

namespace App\Providers;

use App\Models\Ecole;
use App\Models\Membre;
use App\Models\Seminaire;
use App\Policies\SeminairePolicy;
use App\Policies\EcolePolicy;
use App\Policies\MembrePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Seminaire::class => SeminairePolicy::class,
        Ecole::class => EcolePolicy::class,
        Membre::class => MembrePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
