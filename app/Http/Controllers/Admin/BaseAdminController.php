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
     * Constructeur - Initialise les propriétés communes
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->currentEcole = $this->user ? $this->user->ecole : null;
            
            // Partager avec toutes les vues
            view()->share('currentUser', $this->user);
            view()->share('currentEcole', $this->currentEcole);
            
            return $next($request);
        });
    }

    // =====================================================================
    // 1. MÉTHODES DE PAGINATION ET FILTRAGE
    // =====================================================================

    /**
     * Paginer avec application automatique du scope école
     */
    protected function paginate($query, Request $request, int $perPage = null)
    {
        $perPage = $perPage ?? $this->perPage;

        // Appliquer automatiquement le scope école si non superadmin
        if (!$this->user->isSuperAdmin() && method_exists($query->getModel(), 'scopeForEcole')) {
            $query->forEcole($this->currentEcole->id);
        }

        // Appliquer les filtres
        $query = $this->applyFilters($query, $request);

        // Appliquer le tri
        $query = $this->applySorting($query, $request);

        // Appliquer la recherche
        if ($request->filled('search')) {
            $query = $this->applySearch($query, $request->search);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Appliquer les filtres de requête
     */
    protected function applyFilters(Builder $query, Request $request): Builder
    {
        // À surcharger dans les contrôleurs enfants
        return $query;
    }

    /**
     * Appliquer le tri
     */
    protected function applySorting(Builder $query, Request $request): Builder
    {
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Validation basique
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        return $query->orderBy($sortField, $sortDirection);
    }

    /**
     * Appliquer la recherche
     */
    protected function applySearch(Builder $query, string $search): Builder
    {
        // À surcharger dans les contrôleurs enfants
        return $query;
    }

    // =====================================================================
    // 2. MÉTHODES DE RÉPONSE STANDARDISÉES
    // =====================================================================

    /**
     * Retourner une réponse de succès
     */
    protected function successResponse($message = 'Opération réussie', $route = null, $data = [])
    {
        session()->flash('success', $message);

        if ($route) {
            return redirect()->route($route, $data);
        }

        return back();
    }

    /**
     * Retourner une réponse d'erreur
     */
    protected function errorResponse($message = 'Une erreur est survenue', $route = null, $data = [])
    {
        session()->flash('error', $message);

        if ($route) {
            return redirect()->route($route, $data);
        }

        return back()->withInput();
    }

    /**
     * Réponse JSON standardisée
     */
    protected function jsonResponse($data = [], $message = '', $status = 200)
    {
        return response()->json([
            'success' => $status >= 200 && $status < 300,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toIso8601String()
        ], $status);
    }

    // =====================================================================
    // 3. MÉTHODES DE VALIDATION
    // =====================================================================

    /**
     * Vérifier les permissions
     */
    protected function checkPermission($permission)
    {
        if (!$this->user->can($permission)) {
            abort(403, 'Accès non autorisé');
        }
    }

    /**
     * Vérifier l'accès à une ressource
     */
    protected function checkResourceAccess($resource, $permission = null)
    {
        // Vérifier l'appartenance à l'école
        if (!$this->user->isSuperAdmin() && 
            method_exists($resource, 'ecole') && 
            $resource->ecole_id !== $this->currentEcole->id) {
            abort(403, 'Accès non autorisé à cette ressource');
        }

        // Vérifier la permission si spécifiée
        if ($permission) {
            $this->checkPermission($permission);
        }
    }

    // =====================================================================
    // 4. MÉTHODES DE LOGGING ET AUDIT
    // =====================================================================

    /**
     * Logger une action
     */
    protected function logAction($action, $model = null, $properties = [])
    {
        $description = "{$this->user->nom_complet} a effectué l'action: {$action}";

        if ($model) {
            activity()
                ->performedOn($model)
                ->causedBy($this->user)
                ->withProperties(array_merge($properties, [
                    'ecole_id' => $this->currentEcole->id,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]))
                ->log($description);
        } else {
            activity()
                ->causedBy($this->user)
                ->withProperties(array_merge($properties, [
                    'ecole_id' => $this->currentEcole->id,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]))
                ->log($description);
        }
    }

    /**
     * Logger une erreur
     */
    protected function logError($message, \Exception $exception = null, $context = [])
    {
        $errorData = [
            'user_id' => $this->user->id,
            'ecole_id' => $this->currentEcole->id,
            'message' => $message,
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip' => request()->ip()
        ];

        if ($exception) {
            $errorData['exception'] = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ];
        }

        Log::error($message, array_merge($errorData, $context));
    }

    // =====================================================================
    // 5. MÉTHODES D'IMPORT/EXPORT
    // =====================================================================

    /**
     * Exporter en Excel
     */
    protected function exportToExcel($exportClass, $filename = 'export.xlsx')
    {
        $this->logAction('export_excel', null, ['filename' => $filename]);
        
        return Excel::download($exportClass, $filename);
    }

    /**
     * Exporter en PDF
     */
    protected function exportToPdf($view, $data, $filename = 'document.pdf', $options = [])
    {
        $this->logAction('export_pdf', null, ['filename' => $filename]);

        $defaultOptions = [
            'orientation' => 'portrait',
            'paper' => 'a4'
        ];

        $options = array_merge($defaultOptions, $options);

        $pdf = Pdf::loadView($view, $data)
            ->setPaper($options['paper'], $options['orientation']);

        return $pdf->download($filename);
    }

    /**
     * Importer depuis Excel
     */
    protected function importFromExcel($importClass, Request $request, $fileField = 'file')
    {
        $request->validate([
            $fileField => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            Excel::import($importClass, $request->file($fileField));
            
            $this->logAction('import_excel', null, [
                'filename' => $request->file($fileField)->getClientOriginalName()
            ]);

            return $this->successResponse('Import réussi');
        } catch (\Exception $e) {
            $this->logError('Erreur lors de l\'import', $e);
            return $this->errorResponse('Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    // =====================================================================
    // 6. MÉTHODES DE GESTION DES FICHIERS
    // =====================================================================

    /**
     * Uploader un fichier
     */
    protected function uploadFile(Request $request, $field, $path = 'uploads', $disk = 'public')
    {
        if (!$request->hasFile($field)) {
            return null;
        }

        $file = $request->file($field);
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        $storedPath = $file->storeAs($path, $filename, $disk);
        
        $this->logAction('file_upload', null, [
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $filename,
            'path' => $storedPath,
            'size' => $file->getSize()
        ]);

        return $storedPath;
    }

    /**
     * Supprimer un fichier
     */
    protected function deleteFile($path, $disk = 'public')
    {
        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
            
            $this->logAction('file_delete', null, [
                'path' => $path,
                'disk' => $disk
            ]);
            
            return true;
        }

        return false;
    }

    // =====================================================================
    // 7. MÉTHODES DE NOTIFICATIONS
    // =====================================================================

    /**
     * Envoyer une notification
     */
    protected function sendNotification($users, $notificationClass, $data = [])
    {
        if (!is_iterable($users)) {
            $users = [$users];
        }

        foreach ($users as $user) {
            $user->notify(new $notificationClass($data));
        }

        $this->logAction('notification_sent', null, [
            'notification' => class_basename($notificationClass),
            'recipients_count' => count($users)
        ]);
    }

    // =====================================================================
    // 8. MÉTHODES DE CACHE
    // =====================================================================

    /**
     * Obtenir ou mettre en cache
     */
    protected function cacheRemember($key, $ttl, \Closure $callback)
    {
        $fullKey = "ecole_{$this->currentEcole->id}_{$key}";
        
        return cache()->remember($fullKey, $ttl, $callback);
    }

    /**
     * Effacer le cache
     */
    protected function cacheForget($key)
    {
        $fullKey = "ecole_{$this->currentEcole->id}_{$key}";
        
        cache()->forget($fullKey);
    }

    // =====================================================================
    // 9. MÉTHODES UTILITAIRES
    // =====================================================================

    /**
     * Générer un code unique
     */
    protected function generateUniqueCode($prefix = '', $length = 8)
    {
        return $prefix . strtoupper(Str::random($length));
    }

    /**
     * Formater une date
     */
    protected function formatDate($date, $format = 'd/m/Y')
    {
        if (!$date) return null;
        
        return \Carbon\Carbon::parse($date)->format($format);
    }

    /**
     * Formater un montant
     */
    protected function formatMoney($amount, $symbol = '$')
    {
        return $symbol . ' ' . number_format($amount, 2, ',', ' ');
    }

    // =====================================================================
    // 10. MÉTHODES DE BATCH
    // =====================================================================

    /**
     * Traiter par batch
     */
    protected function processBatch($model, \Closure $callback, $chunkSize = 100)
    {
        $processed = 0;

        $model::chunk($chunkSize, function ($items) use ($callback, &$processed) {
            foreach ($items as $item) {
                $callback($item);
                $processed++;
            }
        });

        $this->logAction('batch_process', null, [
            'model' => class_basename($model),
            'processed' => $processed
        ]);

        return $processed;
    }

    /**
     * Actions en masse
     */
    protected function massAction(Request $request, $model, $action, $field = 'ids')
    {
        $ids = $request->input($field, []);
        
        if (empty($ids)) {
            return $this->errorResponse('Aucun élément sélectionné');
        }

        $count = 0;
        $errors = [];

        foreach ($ids as $id) {
            try {
                $item = $model::find($id);
                
                if ($item) {
                    $this->checkResourceAccess($item);
                    $action($item);
                    $count++;
                }
            } catch (\Exception $e) {
                $errors[] = "Erreur ID {$id}: " . $e->getMessage();
            }
        }

        if ($count > 0) {
            $message = "{$count} élément(s) traité(s) avec succès";
            
            if (!empty($errors)) {
                $message .= ". " . count($errors) . " erreur(s) rencontrée(s)";
                session()->flash('warning', implode('<br>', $errors));
            }
            
            return $this->successResponse($message);
        }

        return $this->errorResponse('Aucun élément traité');
    }

    // =====================================================================
    // 11. MÉTHODES DE STATISTIQUES
    // =====================================================================

    /**
     * Obtenir les statistiques d'un modèle
     */
    protected function getModelStats($model, $dateField = 'created_at')
    {
        $query = $model::query();

        // Appliquer le scope école si nécessaire
        if (!$this->user->isSuperAdmin() && method_exists($model, 'scopeForEcole')) {
            $query->forEcole($this->currentEcole->id);
        }

        return [
            'total' => $query->count(),
            'today' => $query->whereDate($dateField, today())->count(),
            'week' => $query->whereBetween($dateField, [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month' => $query->whereMonth($dateField, now()->month)->whereYear($dateField, now()->year)->count(),
            'year' => $query->whereYear($dateField, now()->year)->count()
        ];
    }

    /**
     * Obtenir les données pour graphiques
     */
    protected function getChartData($model, $dateField = 'created_at', $period = 'month')
    {
        $query = $model::query();

        // Appliquer le scope école si nécessaire
        if (!$this->user->isSuperAdmin() && method_exists($model, 'scopeForEcole')) {
            $query->forEcole($this->currentEcole->id);
        }

        switch ($period) {
            case 'week':
                $start = now()->subWeeks(12);
                $groupBy = "WEEK({$dateField})";
                break;
            case 'month':
                $start = now()->subMonths(12);
                $groupBy = "MONTH({$dateField})";
                break;
            case 'year':
                $start = now()->subYears(5);
                $groupBy = "YEAR({$dateField})";
                break;
            default:
                $start = now()->subDays(30);
                $groupBy = "DATE({$dateField})";
        }

        return $query->where($dateField, '>=', $start)
            ->selectRaw("COUNT(*) as count, {$groupBy} as period")
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    // =====================================================================
    // 12. MÉTHODES DE CONFIGURATION
    // =====================================================================

    /**
     * Obtenir la configuration de l'école
     */
    protected function getEcoleConfig($key, $default = null)
    {
        return $this->currentEcole->getConfig($key, $default);
    }

    /**
     * Définir la configuration de l'école
     */
    protected function setEcoleConfig($key, $value)
    {
        return $this->currentEcole->setConfig($key, $value);
    }

    // =====================================================================
    // 13. MÉTHODES DE SÉCURITÉ
    // =====================================================================

    /**
     * Valider l'origine de la requête
     */
    protected function validateRequestOrigin(Request $request)
    {
        $validOrigins = [
            config('app.url'),
            'http://localhost:8000',
            'http://127.0.0.1:8000'
        ];

        $origin = $request->headers->get('origin');
        
        if (!in_array($origin, $validOrigins)) {
            abort(403, 'Origine non autorisée');
        }
    }

    /**
     * Limiter le taux de requêtes
     */
    protected function rateLimit($key, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = "rate_limit_{$this->user->id}_{$key}";
        
        if (cache()->has($key) && cache()->get($key) >= $maxAttempts) {
            abort(429, 'Trop de requêtes. Veuillez réessayer plus tard.');
        }

        cache()->increment($key);
        cache()->expire($key, $decayMinutes * 60);
    }

    // =====================================================================
    // 14. MÉTHODES DE RECHERCHE AVANCÉE
    // =====================================================================

    /**
     * Recherche globale multi-modèles
     */
    protected function globalSearch($query, array $models)
    {
        $results = [];

        foreach ($models as $modelClass => $searchFields) {
            $modelQuery = $modelClass::query();

            // Appliquer le scope école
            if (!$this->user->isSuperAdmin() && method_exists($modelClass, 'scopeForEcole')) {
                $modelQuery->forEcole($this->currentEcole->id);
            }

            // Construire la requête de recherche
            $modelQuery->where(function ($q) use ($searchFields, $query) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'LIKE', "%{$query}%");
                }
            });

            $results[class_basename($modelClass)] = $modelQuery->limit(10)->get();
        }

        return $results;
    }

    // =====================================================================
    // 15. MÉTHODES DE GÉNÉRATION DE RAPPORTS
    // =====================================================================

    /**
     * Générer un rapport
     */
    protected function generateReport($title, $data, $view, $format = 'pdf')
    {
        $reportData = [
            'title' => $title,
            'generated_at' => now(),
            'generated_by' => $this->user->nom_complet,
            'ecole' => $this->currentEcole,
            'data' => $data
        ];

        $this->logAction('report_generated', null, [
            'title' => $title,
            'format' => $format
        ]);

        switch ($format) {
            case 'excel':
                return $this->exportToExcel(
                    new \App\Exports\GenericExport($reportData),
                    Str::slug($title) . '.xlsx'
                );
                
            case 'pdf':
            default:
                return $this->exportToPdf(
                    $view,
                    $reportData,
                    Str::slug($title) . '.pdf'
                );
        }
    }

    // =====================================================================
    // 16. MÉTHODES DE VALIDATION AVANCÉE
    // =====================================================================

    /**
     * Valider avec messages personnalisés
     */
    protected function validateWithMessages(Request $request, array $rules, array $messages = [])
    {
        $defaultMessages = [
            'required' => 'Le champ :attribute est obligatoire.',
            'email' => 'Le champ :attribute doit être une adresse email valide.',
            'unique' => 'Cette valeur est déjà utilisée.',
            'min' => 'Le champ :attribute doit contenir au moins :min caractères.',
            'max' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'date' => 'Le champ :attribute doit être une date valide.',
            'exists' => 'La valeur sélectionnée est invalide.',
            'in' => 'La valeur sélectionnée est invalide.',
            'file' => 'Le champ :attribute doit être un fichier.',
            'mimes' => 'Le fichier doit être de type : :values.',
            'max.file' => 'Le fichier ne peut pas dépasser :max Ko.'
        ];

        return $request->validate($rules, array_merge($defaultMessages, $messages));
    }

    // =====================================================================
    // 17. MÉTHODES DE FORMATAGE DES DONNÉES
    // =====================================================================

    /**
     * Formater pour DataTable
     */
    protected function formatForDataTable($query, Request $request)
    {
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search', ['value' => '']);
        $order = $request->get('order', []);
        $columns = $request->get('columns', []);

        // Appliquer la recherche
        if (!empty($search['value'])) {
            $query = $this->applySearch($query, $search['value']);
        }

        // Compter le total avant pagination
        $recordsTotal = $query->count();

        // Appliquer le tri
        if (!empty($order)) {
            foreach ($order as $orderItem) {
                $columnIndex = $orderItem['column'];
                $columnName = $columns[$columnIndex]['data'] ?? 'id';
                $direction = $orderItem['dir'] ?? 'asc';
                
                $query->orderBy($columnName, $direction);
            }
        }

        // Appliquer la pagination
        $data = $query->skip($start)->take($length)->get();

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ]);
    }

    // =====================================================================
    // 18. MÉTHODES D'API
    // =====================================================================

    /**
     * Réponse API paginée
     */
    protected function paginatedApiResponse($query, Request $request, $transformer = null)
    {
        $perPage = $request->get('per_page', 15);
        $page = $request->get('page', 1);

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        $data = $transformer 
            ? $paginator->through($transformer)
            : $paginator;

        return response()->json([
            'success' => true,
            'data' => $data->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem()
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl()
            ]
        ]);
    }
}
