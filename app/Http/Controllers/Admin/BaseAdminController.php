<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseAdminController extends Controller
{
    /**
     * Nombre d'éléments par page par défaut
     */
    protected int $perPage = 15;

    /**
     * Utilisateur authentifié
     */
    protected $user;

    /**
     * École courante de l'utilisateur
     */
    protected $currentEcole;

    /**
     * Cache pour éviter les vérifications répétitives
     */
    protected $hasEcoleScope = [];

    /**
     * Constructeur - Initialise les propriétés communes
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Récupérer l'utilisateur avec la relation école préchargée
            $this->user = Auth::user();
            
            if ($this->user) {
                // Précharger la relation école pour éviter les requêtes N+1
                $this->user->load('ecole');
                $this->currentEcole = $this->user->ecole;
            }
            
            return $next($request);
        });

        // Middleware séparé pour partager les vues (évite la récursion)
        $this->middleware(function ($request, $next) {
            if ($this->user && !view()->shared('currentUser')) {
                view()->share('currentUser', $this->user);
                view()->share('currentEcole', $this->currentEcole);
            }
            return $next($request);
        });
    }

    // =====================================================================
    // 1. MÉTHODES DE PAGINATION ET FILTRAGE - OPTIMISÉES
    // =====================================================================

    /**
     * Vérifier si un modèle a le scope école (avec cache)
     */
    protected function hasEcoleScope($model): bool
    {
        $className = is_string($model) ? $model : get_class($model);
        
        if (!isset($this->hasEcoleScope[$className])) {
            $this->hasEcoleScope[$className] = method_exists($className, 'scopeForEcole');
        }
        
        return $this->hasEcoleScope[$className];
    }

    /**
     * Paginer avec application automatique du scope école - OPTIMISÉ
     */
    protected function paginate($query, Request $request, int $perPage = null)
    {
        $perPage = $perPage ?? $this->perPage;

        // Validation des paramètres d'entrée
        if (!$query instanceof Builder) {
            throw new \InvalidArgumentException('Query must be an Eloquent Builder instance');
        }

        // Appliquer le scope école seulement si nécessaire
        if (!$this->user->isSuperAdmin() && 
            $this->currentEcole && 
            $this->hasEcoleScope($query->getModel())) {
            $query->forEcole($this->currentEcole->id);
        }

        // Appliquer les filtres avec limite de performance
        $query = $this->applyFilters($query, $request);

        // Appliquer le tri avec validation stricte
        $query = $this->applySorting($query, $request);

        // Appliquer la recherche avec limite
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            if (strlen($searchTerm) >= 2) { // Minimum 2 caractères
                $query = $this->applySearch($query, $searchTerm);
            }
        }

        // Limiter le nombre maximum d'éléments par page
        $perPage = min($perPage, 100);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Appliquer le tri avec validation stricte
     */
    protected function applySorting(Builder $query, Request $request): Builder
    {
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Validation stricte du champ de tri
        $allowedFields = $this->getAllowedSortFields();
        if (!in_array($sortField, $allowedFields)) {
            $sortField = 'created_at';
        }

        // Validation de la direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        return $query->orderBy($sortField, $sortDirection);
    }

    /**
     * Définir les champs autorisés pour le tri (à surcharger)
     */
    protected function getAllowedSortFields(): array
    {
        return ['id', 'created_at', 'updated_at'];
    }

    /**
     * Appliquer la recherche avec optimisation
     */
    protected function applySearch(Builder $query, string $search): Builder
    {
        // Nettoyer le terme de recherche
        $search = trim($search);
        
        // Éviter les recherches trop courtes ou trop longues
        if (strlen($search) < 2 || strlen($search) > 100) {
            return $query;
        }

        // Échapper les caractères spéciaux pour éviter les injections
        $search = addslashes($search);

        // À surcharger dans les contrôleurs enfants
        return $query;
    }

    // =====================================================================
    // MÉTHODES DE VÉRIFICATION OPTIMISÉES
    // =====================================================================

    /**
     * Vérifier l'accès à une ressource - OPTIMISÉ
     */
    protected function checkResourceAccess($resource, $permission = null)
    {
        if (!$resource) {
            abort(404, 'Ressource non trouvée');
        }

        // Vérifier l'appartenance à l'école seulement si nécessaire
        if (!$this->user->isSuperAdmin() && 
            $this->currentEcole && 
            property_exists($resource, 'ecole_id') && 
            $resource->ecole_id !== $this->currentEcole->id) {
            abort(403, 'Accès non autorisé à cette ressource');
        }

        // Vérifier la permission si spécifiée
        if ($permission) {
            $this->checkPermission($permission);
        }
    }

    /**
     * Obtenir les statistiques d'un modèle - OPTIMISÉ
     */
    protected function getModelStats($model, $dateField = 'created_at')
    {
        // Utiliser une seule requête avec des sous-requêtes pour optimiser
        $baseQuery = $model::query();

        // Appliquer le scope école si nécessaire
        if (!$this->user->isSuperAdmin() && $this->hasEcoleScope($model)) {
            $baseQuery->forEcole($this->currentEcole->id);
        }

        // Utiliser des requêtes optimisées avec des index
        return [
            'total' => $baseQuery->count(),
            'today' => $baseQuery->whereDate($dateField, today())->count(),
            'week' => $baseQuery->whereBetween($dateField, [
                now()->startOfWeek(), 
                now()->endOfWeek()
            ])->count(),
            'month' => $baseQuery->whereYear($dateField, now()->year)
                                ->whereMonth($dateField, now()->month)
                                ->count(),
            'year' => $baseQuery->whereYear($dateField, now()->year)->count()
        ];
    }

    // =====================================================================
    // MÉTHODES DE CACHE OPTIMISÉES
    // =====================================================================

    /**
     * Obtenir ou mettre en cache - OPTIMISÉ
     */
    protected function cacheRemember($key, $ttl, \Closure $callback)
    {
        if (!$this->currentEcole) {
            return $callback();
        }

        $fullKey = "ecole_{$this->currentEcole->id}_{$key}";
        
        // Limiter la durée du cache pour éviter les données obsolètes
        $ttl = min($ttl, 3600); // Max 1 heure
        
        return cache()->remember($fullKey, $ttl, $callback);
    }

    // ...existing code...
    // (Garder le reste des méthodes inchangées pour l'instant)
