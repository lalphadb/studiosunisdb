<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\AdminDataService;
use App\Http\Middleware\AdminDataMiddleware;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

/**
 * Service Provider dédié à l'administration StudiosDB
 * Architecture enterprise avec injection de dépendances
 */
class StudiosDBAdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Enregistrer le service AdminDataService comme singleton
        $this->app->singleton(AdminDataService::class, function ($app) {
            return new AdminDataService();
        });

        // Enregistrer le middleware
        $this->app->singleton(AdminDataMiddleware::class, function ($app) {
            return new AdminDataMiddleware($app->make(AdminDataService::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Appliquer le middleware aux routes admin
        Route::pushMiddlewareToGroup('admin', AdminDataMiddleware::class);
        
        // Configuration supplémentaire si nécessaire
        $this->configureAdminRoutes();
        $this->configureAdminViews();
    }

    /**
     * Configurer les routes admin
     */
    private function configureAdminRoutes(): void
    {
        // Configuration spécifique aux routes admin si nécessaire
        Route::macro('adminResource', function (string $name, string $controller, array $options = []) {
            return Route::resource($name, $controller, array_merge([
                'middleware' => ['auth', 'verified'],
                'names' => [
                    'index' => "admin.{$name}.index",
                    'create' => "admin.{$name}.create",
                    'store' => "admin.{$name}.store",
                    'show' => "admin.{$name}.show",
                    'edit' => "admin.{$name}.edit",
                    'update' => "admin.{$name}.update",
                    'destroy' => "admin.{$name}.destroy",
                ]
            ], $options));
        });
    }

    /**
     * Configurer les vues admin
     */
    private function configureAdminViews(): void
    {
        // Configuration des directives Blade personnalisées si nécessaire
        $this->configureBladeDirectives();
    }

    /**
     * Directives Blade personnalisées
     */
    private function configureBladeDirectives(): void
    {
        // Directive pour vérifier les permissions admin
        \Blade::if('adminCan', function (string $permission) {
            return auth()->check() && (
                auth()->user()->can($permission) || 
                auth()->user()->hasRole('superadmin')
            );
        });

        // Directive pour vérifier le rôle
        \Blade::if('hasRole', function (string $role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        // Directive pour SuperAdmin uniquement
        \Blade::if('superAdmin', function () {
            return auth()->check() && auth()->user()->hasRole('superadmin');
        });
    }
}
