<?php

use Laravel\Telescope\Http\Middleware\Authorize;
use Laravel\Telescope\Watchers;

return [

    /*
    |--------------------------------------------------------------------------
    | Telescope Master Switch
    |--------------------------------------------------------------------------
    */

    'enabled' => env('TELESCOPE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Telescope Domain
    |--------------------------------------------------------------------------
    */

    'domain' => env('TELESCOPE_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Telescope Path
    |--------------------------------------------------------------------------
    */

    'path' => env('TELESCOPE_PATH', 'telescope'),

    /*
    |--------------------------------------------------------------------------
    | Telescope Storage Driver
    |--------------------------------------------------------------------------
    */

    'driver' => env('TELESCOPE_DRIVER', 'database'),

    'storage' => [
        'database' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'chunk' => 1000,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Telescope Queue
    |--------------------------------------------------------------------------
    */

    'queue' => [
        'connection' => env('TELESCOPE_QUEUE_CONNECTION', null),
        'queue' => env('TELESCOPE_QUEUE', null),
        'delay' => env('TELESCOPE_QUEUE_DELAY', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Telescope Route Middleware
    |--------------------------------------------------------------------------
    */

    'middleware' => [
        'web',
        Authorize::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed / Ignored Paths & Commands
    |--------------------------------------------------------------------------
    */

    'only_paths' => [
        // 'api/*'
    ],

    'ignore_paths' => [
        'livewire*',
        'nova-api*',
        'pulse*',
        'telescope/telescope-api*',
    ],

    'ignore_commands' => [
        'telescope:prune',
    ],

    /*
    |--------------------------------------------------------------------------
    | Telescope Watchers
    |--------------------------------------------------------------------------
    */

    'watchers' => [
        Watchers\BatchWatcher::class => env('TELESCOPE_BATCH_WATCHER', true),
        
        Watchers\CacheWatcher::class => env('TELESCOPE_CACHE_WATCHER', true),
        
        Watchers\CommandWatcher::class => [
            'enabled' => env('TELESCOPE_COMMAND_WATCHER', true),
            'ignore' => [
                'key:generate',
                'package:discover',
                'telescope:prune',
            ],
        ],

        Watchers\DumpWatcher::class => env('TELESCOPE_DUMP_WATCHER', true),
        
        Watchers\EventWatcher::class => env('TELESCOPE_EVENT_WATCHER', true),
        
        Watchers\ExceptionWatcher::class => env('TELESCOPE_EXCEPTION_WATCHER', true),
        
        Watchers\JobWatcher::class => env('TELESCOPE_JOB_WATCHER', true),
        
        Watchers\LogWatcher::class => env('TELESCOPE_LOG_WATCHER', true),
        
        Watchers\MailWatcher::class => env('TELESCOPE_MAIL_WATCHER', true),

        Watchers\ModelWatcher::class => [
            'enabled' => env('TELESCOPE_MODEL_WATCHER', true),
            'hydrations' => true,
        ],

        Watchers\NotificationWatcher::class => env('TELESCOPE_NOTIFICATION_WATCHER', true),

        Watchers\QueryWatcher::class => [
            'enabled' => env('TELESCOPE_QUERY_WATCHER', true),
            'slow' => 100, // Requêtes > 100ms marquées comme lentes
        ],

        Watchers\RedisWatcher::class => env('TELESCOPE_REDIS_WATCHER', true),

        Watchers\RequestWatcher::class => [
            'enabled' => env('TELESCOPE_REQUEST_WATCHER', true),
            'size_limit' => env('TELESCOPE_RESPONSE_SIZE_LIMIT', 64),
        ],

        Watchers\ScheduleWatcher::class => env('TELESCOPE_SCHEDULE_WATCHER', true),
        
        Watchers\ViewWatcher::class => env('TELESCOPE_VIEW_WATCHER', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Telescope Pruning
    |--------------------------------------------------------------------------
    */

    'prune' => [
        'hours' => env('TELESCOPE_PRUNE_HOURS', 48),
    ],

];
