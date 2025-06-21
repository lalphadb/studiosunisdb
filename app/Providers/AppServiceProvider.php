<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\Telescope;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Telescope authorization
        if (class_exists(Telescope::class)) {
            Telescope::auth(function ($request) {
                return auth()->check() && 
                       auth()->user()->hasRole('superadmin');
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
