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
        // Indique à Laravel d'utiliser le manifest généré par Vite 6 dans le sous-dossier .vite
        // C'est la méthode la plus propre pour résoudre le chemin du manifest sans hacks.
        Vite::useManifestFilename('.vite/manifest.json');

        // Optionnel: préchargement des assets pour améliorer la performance perçue.
        Vite::prefetch(concurrency: 3);
    }
}
