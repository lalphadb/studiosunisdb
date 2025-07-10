<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\AdminDataService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

/**
 * Middleware pour l'injection des données administratives
 * Architecture enterprise avec gestion d'erreurs
 */
class AdminDataMiddleware
{
    public function __construct(
        private AdminDataService $adminDataService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Injecter les données admin dans toutes les vues admin
            $this->injectAdminData();
        } catch (\Exception $e) {
            Log::error('Erreur AdminDataMiddleware', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'url' => $request->url()
            ]);
            
            // En cas d'erreur, injecter des données par défaut
            $this->injectDefaultData();
        }

        return $next($request);
    }

    /**
     * Injecter les données admin
     */
    private function injectAdminData(): void
    {
        if (!auth()->check()) {
            $this->injectDefaultData();
            return;
        }

        $navData = $this->adminDataService->getNavigationData();

        // Partager avec toutes les vues
        View::share([
            'navData' => $navData,
            'user' => $navData['user'],
            'ecole' => $navData['ecole'],
            'adminPermissions' => $navData['permissions'],
            'systemInfo' => $navData['system_info']
        ]);
    }

    /**
     * Injecter des données par défaut en cas d'erreur
     */
    private function injectDefaultData(): void
    {
        View::share([
            'navData' => [
                'user_role' => 'guest',
                'user' => null,
                'ecole' => null,
                'permissions' => [],
                'modules' => [],
                'menu_items' => []
            ],
            'user' => null,
            'ecole' => null,
            'adminPermissions' => [],
            'systemInfo' => [
                'version' => '4.1.10.2',
                'environment' => 'production'
            ]
        ]);
    }
}
