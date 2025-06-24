<?php

namespace App\Providers;

use App\Models\User as UserModel;
use App\Models\Ecole;
use App\Models\Cours;
use App\Models\Ceinture;
use App\Models\Seminaire;
use App\Models\Paiement;
use App\Models\Presence;
use App\Models\InscriptionSeminaire;
use App\Policies\UserPolicy;
use App\Policies\EcolePolicy;
use App\Policies\CoursPolicy;
use App\Policies\CeinturePolicy;
use App\Policies\SeminairePolicy;
use App\Policies\PaiementPolicy;
use App\Policies\PresencePolicy;
use App\Policies\InscriptionSeminairePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        UserModel::class => UserPolicy::class,
        Ecole::class => EcolePolicy::class,
        Cours::class => CoursPolicy::class,
        Ceinture::class => CeinturePolicy::class,
        Seminaire::class => SeminairePolicy::class,
        Paiement::class => PaiementPolicy::class,
        Presence::class => PresencePolicy::class,
        InscriptionSeminaire::class => InscriptionSeminairePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
        
        // Pas de Gate::before car les Policies gèrent déjà super-admin
    }
}
