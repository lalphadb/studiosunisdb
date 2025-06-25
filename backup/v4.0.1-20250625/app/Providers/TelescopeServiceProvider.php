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
        // Telescope en mode sombre
        Telescope::night();

        $this->hideSensitiveRequestDetails();

        // Filtrer les entrées Telescope - PLUS PERMISSIF
        Telescope::filter(function (IncomingEntry $entry) {
            // En développement, tout passer
            if (app()->environment('local')) {
                return true;
            }

            // En production, filtrer mais permettre l'accès aux SuperAdmin
            if (auth()->check() && auth()->user()->hasRole('superadmin')) {
                return true;
            }

            // Sinon, seulement les erreurs importantes
            return $entry->isReportableException() ||
                   $entry->isFailedRequest() ||
                   $entry->isFailedJob();
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
        Gate::define('viewTelescope', function ($user = null) {
            // PERMETTRE L'ACCÈS AUX SUPERADMIN
            return $user && $user->hasRole('superadmin');
        });
    }
}
