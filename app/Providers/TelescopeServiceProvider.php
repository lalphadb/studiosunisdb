<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Telescope::night(); // Mode sombre (optionnel)

        $this->hideSensitiveRequestDetails();

        // Filtrer les entrées selon l'environnement
        Telescope::filter(function (IncomingEntry $entry) {
            if ($this->app->environment('local')) {
                return true; // Tout enregistrer en local
            }

            // En production, seulement les erreurs importantes
            return $entry->isReportableException() ||
                   $entry->isFailedRequest() ||
                   $entry->isFailedCommand() ||
                   $entry->isScheduledTask() ||
                   $entry->hasMonitoredTag();
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', function ($user) {
            // SEULEMENT les SuperAdmin peuvent accéder à Telescope
            return $user && $user->hasRole('superadmin');
        });
    }
}
