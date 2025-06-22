<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

// --- Modèles ---
use App\Models\User;
use App\Models\Ecole;
use App\Models\Cours;
use App\Models\MembreCeinture;
use App\Models\Paiement;
use App\Models\Presence;
use App\Models\Seminaire;

// --- Policies ---
use App\Policies\UserPolicy;
use App\Policies\EcolePolicy;
use App\Policies\CoursPolicy;
use App\Policies\MembreCeinturePolicy;
use App\Policies\PaiementPolicy;
use App\Policies\PresencePolicy;
use App\Policies\SeminairePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Règle générale pour l'application (correction Membre -> User)
        User::class => UserPolicy::class,
        Ecole::class => EcolePolicy::class,
        
        // Règle corrigée pour l'attribution des ceintures
        MembreCeinture::class => MembreCeinturePolicy::class,

        // Policies pour les autres modules
        Cours::class => CoursPolicy::class,
        Paiement::class => PaiementPolicy::class,
        Presence::class => PresencePolicy::class,
        Seminaire::class => SeminairePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
