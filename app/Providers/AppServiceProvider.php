<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        
        // Force Vite configuration for StudiosDB v5 Pro
        if (app()->environment('local')) {
            Vite::useManifestFilename('.vite/manifest.json');
            Vite::useBuildDirectory('build');
        }
    }
}
