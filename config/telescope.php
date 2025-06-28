<?php

return [
    'enabled' => env('TELESCOPE_ENABLED', false),
    
    'domain' => env('TELESCOPE_DOMAIN'),
    
    'path' => env('TELESCOPE_PATH', 'telescope'),
    
    'driver' => env('TELESCOPE_DRIVER', 'database'),
    
    'storage' => [
        'database' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'chunk' => 1000,
        ],
    ],
    
    'queue' => [
        'connection' => env('TELESCOPE_QUEUE_CONNECTION'),
        'queue' => env('TELESCOPE_QUEUE'),
    ],
    
    'middleware' => [
        'web',
        \App\Http\Middleware\TelescopeAccess::class,
    ],
    
    'only_paths' => [],
    'ignore_paths' => [
        'telescope*',
        'vendor/telescope*',
    ],
    'ignore_commands' => [],
    
    'watchers' => [
        \Laravel\Telescope\Watchers\CacheWatcher::class => [
            'enabled' => env('TELESCOPE_CACHE_WATCHER', true),
        ],
        \Laravel\Telescope\Watchers\CommandWatcher::class => [
            'enabled' => env('TELESCOPE_COMMAND_WATCHER', true),
        ],
        \Laravel\Telescope\Watchers\DumpWatcher::class => [
            'enabled' => env('TELESCOPE_DUMP_WATCHER', true),
        ],
        \Laravel\Telescope\Watchers\EventWatcher::class => [
            'enabled' => env('TELESCOPE_EVENT_WATCHER', true),
        ],
        \Laravel\Telescope\Watchers\ExceptionWatcher::class => [
            'enabled' => env('TELESCOPE_EXCEPTION_WATCHER', true),
        ],
        \Laravel\Telescope\Watchers\JobWatcher::class => [
            'enabled' => env('TELESCOPE_JOB_WATCHER', true),
        ],
        \Laravel\Telescope\Watchers\LogWatcher::class => [
            'enabled' => env('TELESCOPE_LOG_WATCHER', true),
        ],
        \Laravel\Telescope\Watchers\MailWatcher::class => [
            'enabled' => env('TELESCOPE_MAIL_WATCHER', true),
        ],
        \Laravel\Telescope\Watchers\ModelWatcher::class => [
            'enabled' => env('TELESCOPE_MODEL_WATCHER', true),
        ],
        \Laravel\Telescope\Watchers\NotificationWatcher::class => [
            'enabled' => env('TELESCOPE_NOTIFICATION_WATCHER', true),
        ],
        \Laravel\Telescope\Watchers\QueryWatcher::class => [
            'enabled' => env('TELESCOPE_QUERY_WATCHER', true),
            'slow' => 100,
        ],
        \Laravel\Telescope\Watchers\RedisWatcher::class => [
            'enabled' => env('TELESCOPE_REDIS_WATCHER', true),
        ],
        \Laravel\Telescope\Watchers\RequestWatcher::class => [
            'enabled' => env('TELESCOPE_REQUEST_WATCHER', true),
        ],
        \Laravel\Telescope\Watchers\ScheduleWatcher::class => [
            'enabled' => env('TELESCOPE_SCHEDULE_WATCHER', true),
        ],
    ],
];
