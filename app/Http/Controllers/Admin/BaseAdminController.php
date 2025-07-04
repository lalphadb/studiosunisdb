<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Base Controller pour l'administration - Version Professionnelle Avancée
 * 
 * Implémente le standard Laravel Admin Controllers v2.0:
 * - Gestion centralisée des exceptions
 * - Logging métier uniforme
 * - Helpers d'export CSV/Excel
 * - Pagination standardisée
 * - Gestion sécurisée des uploads
 * - Flash messages avancés
 * - Helpers pour notifications, jobs, i18n, API
 */
abstract class BaseAdminController extends Controller
{
    /**
     * Configuration par défaut de pagination
     */
    protected int $defaultPaginationSize = 25;
    protected array $allowedPaginationSizes = [10, 15, 25, 50, 100];

    /**
     * Configuration des uploads sécurisés
     */
    protected array $allowedImageTypes = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
    protected array $allowedDocumentTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
    protected int $maxUploadSize = 10240; // 10MB en KB

    /**
     * Initialise le contrôleur avec les middlewares requis
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!auth()->user()) {
                return redirect()->route('login');
            }
            
            // Permettre l'accès aux superadmins et admin_ecole
            if (auth()->user()->hasAnyRole(['superadmin', 'admin_ecole'])) {
                return $next($request);
            }
            
            // Log des tentatives d'accès non autorisées
            $this->logBusinessAction('Tentative accès admin non autorisé', 'warning', [
                'attempted_url' => $request->fullUrl(),
                'user_roles' => auth()->user()->getRoleNames()->toArray()
            ]);
            
            abort(403, 'Accès non autorisé à l\'administration.');
        });
    }

    // =====================================================================
    // GESTION CENTRALISÉE DES EXCEPTIONS
    // =====================================================================

    /**
     * Gère les exceptions de manière centralisée avec logging automatique
     */
    protected function handleException(\Exception $e, string $action, array $context = []): RedirectResponse
    {
        $errorId = Str::uuid();
        
        $logContext = array_merge([
            'error_id' => $errorId,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()?->email,
            'ecole_id' => auth()->user()?->ecole_id,
            'action' => $action,
            'url' => request()->fullUrl(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'error_message' => $e->getMessage(),
            'error_file' => $e->getFile(),
            'error_line' => $e->getLine(),
        ], $context);

        Log::error("Erreur {$action}", $logContext);

        // En développement, afficher plus de détails
        if (app()->environment('local')) {
            $message = "Erreur {$action}: {$e->getMessage()} (ID: {$errorId})";
        } else {
            $message = __('admin.errors.generic_with_id', ['id' => $errorId]);
        }

        return $this->redirectWithError(
            $this->getDefaultRedirectRoute(),
            $message
        );
    }

    /**
     * Wrapper pour exécuter du code avec gestion d'exception automatique
     */
    protected function executeWithExceptionHandling(callable $callback, string $action, array $context = [])
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return $this->handleException($e, $action, $context);
        }
    }

    // =====================================================================
    // LOGGING MÉTIER UNIFORME
    // =====================================================================

    /**
     * Log une action métier avec contexte standardisé
     */
    protected function logBusinessAction(string $action, string $level = 'info', array $context = []): void
    {
        $standardContext = [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()?->email,
            'user_role' => auth()->user()?->getRoleNames()->first(),
            'ecole_id' => auth()->user()?->ecole_id,
            'ecole_nom' => auth()->user()?->ecole?->nom,
            'timestamp' => now()->toISOString(),
            'session_id' => session()->getId(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ];

        Log::log($level, $action, array_merge($standardContext, $context));
    }

    /**
     * Log spécialisés pour les actions CRUD
     */
    protected function logCreate(string $entity, int $entityId, array $data = []): void
    {
        $this->logBusinessAction("Création {$entity}", 'info', [
            'entity_type' => $entity,
            'entity_id' => $entityId,
            'entity_data' => $data,
            'action_type' => 'CREATE'
        ]);
    }

    protected function logUpdate(string $entity, int $entityId, array $oldData = [], array $newData = []): void
    {
        $this->logBusinessAction("Modification {$entity}", 'info', [
            'entity_type' => $entity,
            'entity_id' => $entityId,
            'old_data' => $oldData,
            'new_data' => $newData,
            'action_type' => 'UPDATE'
        ]);
    }

    protected function logDelete(string $entity, int $entityId, array $data = []): void
    {
        $this->logBusinessAction("Suppression {$entity}", 'warning', [
            'entity_type' => $entity,
            'entity_id' => $entityId,
            'entity_data' => $data,
            'action_type' => 'DELETE'
        ]);
    }

    protected function logSensitiveAction(string $action, array $context = []): void
    {
        $this->logBusinessAction($action, 'warning', array_merge($context, [
            'action_type' => 'SENSITIVE',
            'requires_audit' => true
        ]));
    }

    // =====================================================================
    // HELPERS D'EXPORT CSV/EXCEL
    // =====================================================================

    /**
     * Génère un export CSV avec streaming pour de gros volumes
     */
    protected function exportToCsv(
        \Illuminate\Database\Eloquent\Builder $query,
        array $headers,
        callable $rowMapper,
        string $filename = null
    ): StreamedResponse {
        $filename = $filename ?: 'export_' . date('Y-m-d_H-i-s') . '.csv';

        $response = new StreamedResponse();
        $response->setCallback(function() use ($query, $headers, $rowMapper) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM pour Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // En-têtes
            fputcsv($handle, $headers, ';');
            
            // Données par chunks pour économiser la mémoire
            $query->chunk(1000, function($items) use ($handle, $rowMapper) {
                foreach ($items as $item) {
                    fputcsv($handle, $rowMapper($item), ';');
                }
            });
            
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");

        $this->logBusinessAction('Export CSV', 'info', [
            'filename' => $filename,
            'query' => $query->toSql(),
            'headers' => $headers
        ]);

        return $response;
    }

    /**
     * Génère un export Excel (nécessite maatwebsite/excel)
     */
    protected function exportToExcel(
        \Illuminate\Database\Eloquent\Builder $query,
        array $headers,
        callable $rowMapper,
        string $filename = null,
        string $sheetName = 'Export'
    ): StreamedResponse {
        $filename = $filename ?: 'export_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Si maatwebsite/excel est installé
        if (class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\GenericExport($query, $headers, $rowMapper, $sheetName),
                $filename
            );
        }

        // Fallback vers CSV si Excel n'est pas disponible
        return $this->exportToCsv($query, $headers, $rowMapper, str_replace('.xlsx', '.csv', $filename));
    }

    // =====================================================================
    // PAGINATION STANDARDISÉE
    // =====================================================================

    /**
     * Retourne la taille de pagination demandée ou par défaut
     */
    protected function getPaginationSize(Request $request): int
    {
        $size = (int) $request->get('per_page', $this->defaultPaginationSize);
        
        return in_array($size, $this->allowedPaginationSizes) 
            ? $size 
            : $this->defaultPaginationSize;
    }

    /**
     * Applique la pagination avec conservation des paramètres de recherche
     */
    protected function paginateWithParams(\Illuminate\Database\Eloquent\Builder $query, Request $request)
    {
        return $query->paginate(
            $this->getPaginationSize($request),
            ['*'],
            'page',
            $request->get('page', 1)
        )->withQueryString();
    }

    // =====================================================================
    // GESTION SÉCURISÉE DES UPLOADS
    // =====================================================================

    /**
     * Upload sécurisé d'image avec validation et optimisation
     */
    protected function uploadImage(
        UploadedFile $file, 
        string $path = 'uploads/images',
        array $allowedTypes = null,
        int $maxWidth = 1920,
        int $maxHeight = 1080,
        int $quality = 85
    ): string {
        $allowedTypes = $allowedTypes ?: $this->allowedImageTypes;
        
        // Validation sécurisée
        $this->validateUpload($file, $allowedTypes);
        
        // Génération nom sécurisé
        $filename = $this->generateSecureFilename($file);
        $fullPath = "{$path}/{$filename}";
        
        // Stockage
        $storedPath = $file->storeAs($path, $filename, 'public');
        
        // Optimisation image (si intervention/image est installé)
        if (class_exists(\Intervention\Image\ImageManagerStatic::class)) {
            $this->optimizeImage(storage_path("app/public/{$storedPath}"), $maxWidth, $maxHeight, $quality);
        }
        
        $this->logBusinessAction('Upload image', 'info', [
            'original_name' => $file->getClientOriginalName(),
            'stored_path' => $storedPath,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ]);
        
        return $storedPath;
    }

    /**
     * Upload sécurisé de document
     */
    protected function uploadDocument(
        UploadedFile $file,
        string $path = 'uploads/documents',
        array $allowedTypes = null
    ): string {
        $allowedTypes = $allowedTypes ?: $this->allowedDocumentTypes;
        
        $this->validateUpload($file, $allowedTypes);
        
        $filename = $this->generateSecureFilename($file);
        $storedPath = $file->storeAs($path, $filename, 'public');
        
        $this->logBusinessAction('Upload document', 'info', [
            'original_name' => $file->getClientOriginalName(),
            'stored_path' => $storedPath,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ]);
        
        return $storedPath;
    }

    /**
     * Validation sécurisée des uploads
     */
    private function validateUpload(UploadedFile $file, array $allowedTypes): void
    {
        // Vérification taille
        if ($file->getSize() > $this->maxUploadSize * 1024) {
            throw new \InvalidArgumentException(__('admin.errors.file_too_large', [
                'max' => $this->maxUploadSize
            ]));
        }
        
        // Vérification extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedTypes)) {
            throw new \InvalidArgumentException(__('admin.errors.invalid_file_type', [
                'allowed' => implode(', ', $allowedTypes)
            ]));
        }
        
        // Vérification MIME type pour sécurité supplémentaire
        $mimeType = $file->getMimeType();
        if (!$this->isValidMimeType($mimeType, $extension)) {
            throw new \InvalidArgumentException(__('admin.errors.invalid_mime_type'));
        }
    }

    /**
     * Génère un nom de fichier sécurisé
     */
    private function generateSecureFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::slug($originalName);
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);
        
        return "{$timestamp}_{$random}_{$safeName}.{$extension}";
    }

    /**
     * Optimise une image (nécessite intervention/image)
     */
    private function optimizeImage(string $path, int $maxWidth, int $maxHeight, int $quality): void
    {
        if (!class_exists(\Intervention\Image\ImageManagerStatic::class)) {
            return;
        }
        
        $image = \Intervention\Image\ImageManagerStatic::make($path);
        $image->resize($maxWidth, $maxHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save($path, $quality);
    }

    /**
     * Validation MIME type pour sécurité
     */
    private function isValidMimeType(string $mimeType, string $extension): bool
    {
        $validMimes = [
            'jpg' => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png' => ['image/png'],
            'gif' => ['image/gif'],
            'webp' => ['image/webp'],
            'pdf' => ['application/pdf'],
            'doc' => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'xls' => ['application/vnd.ms-excel'],
            'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        ];
        
        return isset($validMimes[$extension]) && in_array($mimeType, $validMimes[$extension]);
    }

    /**
     * Supprime un fichier de manière sécurisée
     */
    protected function deleteFile(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            $deleted = Storage::disk('public')->delete($path);
            
            if ($deleted) {
                $this->logBusinessAction('Suppression fichier', 'info', [
                    'file_path' => $path
                ]);
            }
            
            return $deleted;
        }
        
        return false;
    }

    // =====================================================================
    // FLASH MESSAGES AVANCÉS
    // =====================================================================

    /**
     * Flash message de succès avec options avancées
     */
    protected function flashSuccess(
        string $message,
        string $title = null,
        array $options = []
    ): void {
        session()->flash('flash_message', [
            'type' => 'success',
            'title' => $title ?: __('admin.messages.success'),
            'message' => $message,
            'options' => array_merge([
                'timeout' => 5000,
                'dismissible' => true,
                'icon' => 'check-circle'
            ], $options)
        ]);
    }

    /**
     * Flash message d'erreur avec options avancées
     */
    protected function flashError(
        string $message,
        string $title = null,
        array $options = []
    ): void {
        session()->flash('flash_message', [
            'type' => 'error',
            'title' => $title ?: __('admin.messages.error'),
            'message' => $message,
            'options' => array_merge([
                'timeout' => 0, // Pas de timeout pour les erreurs
                'dismissible' => true,
                'icon' => 'exclamation-circle'
            ], $options)
        ]);
    }

    /**
     * Flash message d'information
     */
    protected function flashInfo(string $message, string $title = null, array $options = []): void
    {
        session()->flash('flash_message', [
            'type' => 'info',
            'title' => $title ?: __('admin.messages.info'),
            'message' => $message,
            'options' => array_merge([
                'timeout' => 8000,
                'dismissible' => true,
                'icon' => 'info-circle'
            ], $options)
        ]);
    }

    /**
     * Flash message d'avertissement
     */
    protected function flashWarning(string $message, string $title = null, array $options = []): void
    {
        session()->flash('flash_message', [
            'type' => 'warning',
            'title' => $title ?: __('admin.messages.warning'),
            'message' => $message,
            'options' => array_merge([
                'timeout' => 10000,
                'dismissible' => true,
                'icon' => 'exclamation-triangle'
            ], $options)
        ]);
    }

    // =====================================================================
    // HELPERS POUR NOTIFICATIONS
    // =====================================================================

    /**
     * Envoie une notification à un utilisateur
     */
    protected function notifyUser($user, $notification): void
    {
        try {
            $user->notify($notification);
            
            $this->logBusinessAction('Notification envoyée', 'info', [
                'notification_type' => get_class($notification),
                'recipient_id' => $user->id,
                'recipient_email' => $user->email
            ]);
        } catch (\Exception $e) {
            $this->logBusinessAction('Erreur notification', 'error', [
                'notification_type' => get_class($notification),
                'recipient_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Envoie une notification à plusieurs utilisateurs
     */
    protected function notifyUsers($users, $notification): void
    {
        try {
            Notification::send($users, $notification);
            
            $this->logBusinessAction('Notifications multiples envoyées', 'info', [
                'notification_type' => get_class($notification),
                'recipients_count' => is_countable($users) ? count($users) : 0
            ]);
        } catch (\Exception $e) {
            $this->logBusinessAction('Erreur notifications multiples', 'error', [
                'notification_type' => get_class($notification),
                'error' => $e->getMessage()
            ]);
        }
    }

    // =====================================================================
    // HELPERS POUR JOBS
    // =====================================================================

    /**
     * Dispatch un job avec logging automatique
     */
    protected function dispatchJob($job, string $queue = null): void
    {
        try {
            if ($queue) {
                $dispatched = Bus::dispatch($job->onQueue($queue));
            } else {
                $dispatched = Bus::dispatch($job);
            }
            
            $this->logBusinessAction('Job dispatché', 'info', [
                'job_type' => get_class($job),
                'queue' => $queue,
                'job_id' => method_exists($dispatched, 'getId') ? $dispatched->getId() : null
            ]);
        } catch (\Exception $e) {
            $this->logBusinessAction('Erreur dispatch job', 'error', [
                'job_type' => get_class($job),
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    // =====================================================================
    // HELPERS I18N
    // =====================================================================

    /**
     * Traduit avec fallback et logging des clés manquantes
     */
    protected function trans(string $key, array $replace = [], string $locale = null): string
    {
        $translation = __($key, $replace, $locale);
        
        // Log des clés de traduction manquantes
        if ($translation === $key && !app()->environment('production')) {
            Log::warning('Clé de traduction manquante', [
                'key' => $key,
                'locale' => $locale ?: app()->getLocale(),
                'replace' => $replace
            ]);
        }
        
        return $translation;
    }

    // =====================================================================
    // HELPERS API
    // =====================================================================

    /**
     * Réponse API standardisée avec métadonnées
     */
    protected function apiResponse(
        $data = null,
        string $message = null,
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        $response = [
            'success' => $status >= 200 && $status < 300,
            'message' => $message,
            'data' => $data,
            'meta' => array_merge([
                'timestamp' => now()->toISOString(),
                'version' => config('app.version', '1.0.0'),
                'user_id' => auth()->id()
            ], $meta)
        ];
        
        return response()->json($response, $status);
    }

    /**
     * Réponse d'erreur API avec trace en développement
     */
    protected function apiError(
        string $message,
        int $status = 400,
        array $errors = [],
        \Exception $exception = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'meta' => [
                'timestamp' => now()->toISOString(),
                'status_code' => $status
            ]
        ];
        
        // En développement, ajouter la trace
        if ($exception && app()->environment('local')) {
            $response['debug'] = [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ];
        }
        
        return response()->json($response, $status);
    }

    // =====================================================================
    // MÉTHODES EXISTANTES AMÉLIORÉES
    // =====================================================================

    /**
     * Retourne une réponse JSON standardisée pour les succès
     */
    protected function successResponse(string $message, array $data = [], int $status = 200): JsonResponse
    {
        return $this->apiResponse($data, $message, $status);
    }

    /**
     * Retourne une réponse JSON standardisée pour les erreurs
     */
    protected function errorResponse(string $message, array $errors = [], int $status = 400): JsonResponse
    {
        return $this->apiError($message, $status, $errors);
    }

    /**
     * Retourne une redirection avec message de succès et logging
     */
    protected function redirectWithSuccess(string $route, string $message, array $parameters = []): RedirectResponse
    {
        $this->flashSuccess($message);
        return redirect()->route($route, $parameters);
    }

    /**
     * Retourne une redirection avec message d'erreur et logging
     */
    protected function redirectWithError(string $route, string $message, array $parameters = []): RedirectResponse
    {
        $this->flashError($message);
        return redirect()->route($route, $parameters);
    }

    /**
     * Retourne une redirection vers la page précédente avec message de succès
     */
    protected function backWithSuccess(string $message): RedirectResponse
    {
        $this->flashSuccess($message);
        return back();
    }

    /**
     * Retourne une redirection vers la page précédente avec message d'erreur
     */
    protected function backWithError(string $message): RedirectResponse
    {
        $this->flashError($message);
        return back();
    }

    // =====================================================================
    // MÉTHODES UTILITAIRES
    // =====================================================================

    /**
     * Retourne la route par défaut pour les redirections d'erreur
     */
    protected function getDefaultRedirectRoute(): string
    {
        return 'admin.dashboard';
    }

    /**
     * Vérifie si l'utilisateur actuel peut accéder à une école spécifique
     */
    protected function canAccessEcole(int $ecoleId): bool
    {
        if (auth()->user()->hasRole('superadmin')) {
            return true;
        }
        
        return auth()->user()->ecole_id === $ecoleId;
    }

    /**
     * Retourne l'ID de l'école pour l'utilisateur actuel ou une école spécifique
     */
    protected function getAccessibleEcoleId(int $requestedEcoleId = null): int
    {
        if (auth()->user()->hasRole('superadmin') && $requestedEcoleId) {
            return $requestedEcoleId;
        }
        
        return auth()->user()->ecole_id;
    }
}
