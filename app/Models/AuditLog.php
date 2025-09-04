<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEcole;

class AuditLog extends Model
{
    use HasFactory, BelongsToEcole;
    
    const UPDATED_AT = null; // Pas de updated_at, seulement created_at
    
    protected $fillable = [
        'user_id',
        'ecole_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'session_id',
        'request_id',
        'severity',
        'is_sensitive',
    ];
    
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'is_sensitive' => 'boolean',
        'created_at' => 'datetime',
    ];
    
    // Actions communes
    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';
    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';
    const ACTION_EXPORT = 'export';
    const ACTION_VIEW = 'view';
    const ACTION_SEARCH = 'search';
    const ACTION_PERMISSION_CHANGE = 'permission_change';
    
    // Niveaux de sévérité
    const SEVERITY_INFO = 'info';
    const SEVERITY_WARNING = 'warning';
    const SEVERITY_ERROR = 'error';
    const SEVERITY_CRITICAL = 'critical';
    
    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }
    
    /**
     * Obtenir le modèle associé
     */
    public function auditable()
    {
        if ($this->model_type && $this->model_id) {
            return $this->model_type::find($this->model_id);
        }
        return null;
    }
    
    /**
     * Scopes
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    public function scopeForModel($query, $modelType, $modelId = null)
    {
        $query->where('model_type', $modelType);
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        return $query;
    }
    
    public function scopeForAction($query, $action)
    {
        return $query->where('action', $action);
    }
    
    public function scopeSensitive($query)
    {
        return $query->where('is_sensitive', true);
    }
    
    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }
    
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
    
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
    
    /**
     * Méthodes Helper
     */
    public static function log(
        string $action,
        string $description,
        $model = null,
        array $oldValues = null,
        array $newValues = null,
        string $severity = self::SEVERITY_INFO,
        bool $isSensitive = false
    ) {
        $data = [
            'user_id' => auth()->id(),
            'ecole_id' => auth()->user()?->ecole_id,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
            'request_id' => request()->header('X-Request-ID', uniqid('req_')),
            'severity' => $severity,
            'is_sensitive' => $isSensitive,
        ];
        
        if ($model) {
            $data['model_type'] = get_class($model);
            $data['model_id'] = $model->id;
        }
        
        if ($oldValues) {
            $data['old_values'] = $oldValues;
        }
        
        if ($newValues) {
            $data['new_values'] = $newValues;
        }
        
        return static::create($data);
    }
    
    /**
     * Logger une connexion
     */
    public static function logLogin(User $user)
    {
        return static::log(
            self::ACTION_LOGIN,
            "Connexion de l'utilisateur {$user->name}",
            $user
        );
    }
    
    /**
     * Logger une déconnexion
     */
    public static function logLogout(User $user)
    {
        return static::log(
            self::ACTION_LOGOUT,
            "Déconnexion de l'utilisateur {$user->name}",
            $user
        );
    }
    
    /**
     * Logger un export
     */
    public static function logExport(string $type, int $count, array $filters = [])
    {
        return static::log(
            self::ACTION_EXPORT,
            "Export de {$count} {$type}",
            null,
            null,
            ['filters' => $filters, 'count' => $count],
            self::SEVERITY_INFO,
            true // Les exports sont considérés sensibles
        );
    }
    
    /**
     * Obtenir les changements formatés
     */
    public function getFormattedChanges(): array
    {
        $changes = [];
        
        if ($this->old_values && $this->new_values) {
            foreach ($this->new_values as $key => $newValue) {
                if (isset($this->old_values[$key]) && $this->old_values[$key] != $newValue) {
                    $changes[$key] = [
                        'old' => $this->old_values[$key],
                        'new' => $newValue,
                    ];
                }
            }
        }
        
        return $changes;
    }
}
