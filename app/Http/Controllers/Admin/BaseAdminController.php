<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class BaseAdminController extends Controller
{
    use AuthorizesRequests;

    // Pas de middleware dans le constructeur pour Laravel 12.19
    // Les middlewares sont gérés dans routes/admin.php

    /**
     * Vérifier si l'utilisateur est superadmin
     */
    protected function isSuperAdmin(): bool
    {
        return auth()->user()?->hasRole('superadmin') ?? false;
    }

    /**
     * Obtenir l'ID de l'école de l'utilisateur connecté
     */
    protected function getUserEcoleId(): ?int
    {
        return auth()->user()?->ecole_id;
    }

    /**
     * Obtenir l'utilisateur connecté
     */
    protected function getAuthUser()
    {
        return auth()->user();
    }

    /**
     * Retourner une vue admin avec les données communes
     */
    protected function adminView(string $view, array $data = []): View
    {
        return view($view, $data);
    }

    /**
     * Redirection avec message de succès
     */
    protected function redirectWithSuccess(string $message, string $route = null): RedirectResponse
    {
        $redirect = $route ? redirect()->route($route) : back();
        return $redirect->with('success', $message);
    }

    /**
     * Réponse d'erreur JSON
     */
    protected function errorResponse(string $message, int $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }

    /**
     * Réponse de succès JSON
     */
    protected function successResponse(array $data = [], string $message = 'Opération réussie')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * Gestion des exceptions
     */
    protected function handleException(\Exception $e, string $context = ''): RedirectResponse
    {
        Log::error("Erreur {$context}: " . $e->getMessage(), [
            'user_id' => auth()->id(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
    }

    /**
     * Log des actions administratives
     */
    protected function logAdminAction(string $action, string $module, int $resourceId = null, array $extra = []): void
    {
        Log::info("Action admin: {$action}", [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()?->email,
            'module' => $module,
            'resource_id' => $resourceId,
            'ecole_id' => $this->getUserEcoleId(),
            'extra' => $extra,
            'ip' => request()->ip(),
            'timestamp' => now()
        ]);
    }

    /**
     * Pagination avec recherche
     */
    protected function paginate(Builder $query, Request $request, int $perPage = 15)
    {
        // Appliquer recherche si fournie
        if ($request->filled('search')) {
            $query = $this->applySearchFilter($query, $request->search);
        }

        // Appliquer scope multi-tenant automatiquement
        if (!$this->isSuperAdmin() && method_exists($query->getModel(), 'scopePourEcole')) {
            $query->pourEcole($this->getUserEcoleId());
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Appliquer filtre de recherche (à surcharger dans les contrôleurs enfants)
     */
    protected function applySearchFilter(Builder $query, string $search): Builder
    {
        return $query; // Implémentation par défaut vide
    }

    /**
     * Valider l'accès école pour multi-tenant
     */
    protected function validateEcoleAccess($model): void
    {
        if (!$this->isSuperAdmin() && isset($model->ecole_id) && $model->ecole_id !== $this->getUserEcoleId()) {
            abort(403, 'Accès non autorisé à cette ressource');
        }
    }

    /**
     * Obtenir les écoles selon les permissions
     */
    protected function getEcolesForUser()
    {
        if ($this->isSuperAdmin()) {
            return \App\Models\Ecole::where('active', 1)->orderBy('nom')->get();
        }
        
        return \App\Models\Ecole::where('id', $this->getUserEcoleId())->get();
    }
}
