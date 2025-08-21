<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, ['louis@4lb.ca']);
        });
    }

    public function register()
    {
        parent::register();

        if ($this->app->environment('production')) {
            Telescope::filter(function () {
                return false;
            });
        }
    }
}
