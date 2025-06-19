<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Ecole;
use App\Models\Presence;
use App\Models\Membre;
use App\Models\Cours;
use App\Models\Ceinture;
use App\Models\Seminaire;
use App\Models\Paiement;
use App\Policies\EcolePolicy;
use App\Policies\PresencePolicy;
use App\Policies\MembrePolicy;
use App\Policies\CoursPolicy;
use App\Policies\CeinturePolicy;
use App\Policies\SeminairePolicy;
use App\Policies\PaiementPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     */
    protected $policies = [
        Ecole::class => EcolePolicy::class,
        Presence::class => PresencePolicy::class,
        Membre::class => MembrePolicy::class,
        Cours::class => CoursPolicy::class,
        Ceinture::class => CeinturePolicy::class,
        Seminaire::class => SeminairePolicy::class,
        Paiement::class => PaiementPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
