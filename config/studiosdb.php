<?php

return [
    /*
    |--------------------------------------------------------------------------
    | StudiosDB Enterprise Configuration
    |--------------------------------------------------------------------------
    */
    
    'version' => '4.1.10.2',
    
    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'security' => [
        // 2FA Enforcement
        'enforce_2fa_superadmin' => env('ENFORCE_2FA_SUPERADMIN', true),
        'enforce_2fa_admin' => env('ENFORCE_2FA_ADMIN', true),
        
        // Session Security
        'session_lifetime_admin' => env('SESSION_LIFETIME_ADMIN', 120),
        'force_https' => env('FORCE_HTTPS', true),
        'session_encrypt' => env('SESSION_ENCRYPT', true),
        
        // Password Policy
        'password_min_length' => 12,
        'password_require_uppercase' => true,
        'password_require_numbers' => true,
        'password_require_symbols' => true,
        'password_expires_days' => 90,
        
        // Login Security
        'max_login_attempts' => env('MAX_LOGIN_ATTEMPTS', 5),
        'lockout_duration' => env('LOCKOUT_DURATION', 15), // minutes
        
        // IP Restrictions
        'ip_whitelist_enabled' => env('IP_WHITELIST_ENABLED', false),
        'allowed_ips' => env('ALLOWED_IPS', ''),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Multi-tenant Settings
    |--------------------------------------------------------------------------
    */
    'multi_tenant' => [
        'strict_isolation' => true,
        'cross_ecole_sharing' => false,
        'global_search_enabled' => false,
        'cache_prefix_by_ecole' => true,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Monitoring & Alerts
    |--------------------------------------------------------------------------
    */
    'monitoring' => [
        'log_superadmin_actions' => true,
        'alert_email_critical' => env('ALERT_EMAIL_CRITICAL', 'admin@studiosdb.com'),
        'alert_email_superadmin' => env('ALERT_EMAIL_SUPERADMIN', 'security@studiosdb.com'),
        
        // Unusual Activity Detection
        'detect_unusual_hours' => true,
        'unusual_hour_start' => 22,
        'unusual_hour_end' => 6,
        'max_actions_per_hour' => 50,
        
        // Notifications
        'notify_new_superadmin' => true,
        'notify_failed_logins' => true,
        'notify_data_exports' => true,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Limits & Quotas
    |--------------------------------------------------------------------------
    */
    'limits' => [
        'max_users_per_ecole' => env('MAX_USERS_PER_ECOLE', 1000),
        'max_file_upload_size' => env('MAX_FILE_UPLOAD_SIZE', 10), // MB
        'max_export_rows' => env('MAX_EXPORT_ROWS', 10000),
        'api_rate_limit' => env('API_RATE_LIMIT', 60), // per minute
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Audit & Compliance
    |--------------------------------------------------------------------------
    */
    'audit' => [
        'log_all_actions' => true,
        'retention_days' => 365,
        'encrypt_logs' => false,
        'compliance_mode' => env('COMPLIANCE_MODE', 'standard'), // standard, strict, hipaa
    ],
];
