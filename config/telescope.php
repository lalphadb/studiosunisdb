<?php

return [
    'enabled' => env('TELESCOPE_ENABLED', true),
    'domain' => env('TELESCOPE_DOMAIN', null),
    'path' => env('TELESCOPE_PATH', 'telescope'),
    'driver' => env('TELESCOPE_DRIVER', 'database'),
    'storage' => [
        'database' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'chunk' => 1000,
        ],
    ],
    'queue' => [
        'connection' => env('TELESCOPE_QUEUE_CONNECTION', 'sync'),
        'queue' => env('TELESCOPE_QUEUE', 'default'),
    ],
    'middleware' => [
        'web',
        'auth',
        Illuminate\Auth\Middleware\Authorize::class.':viewTelescope',
    ],
    'only_paths' => [],
    'ignore_paths' => [
        'nova-api*',
    ],
    'ignore_commands' => [
        'telescope:*',
    ],
    'watchers' => [
        Laravel\Telescope\Watchers\BatchWatcher::class => env('TELESCOPE_BATCH_WATCHER', true),
        Laravel\Telescope\Watchers\CacheWatcher::class => [
            'enabled' => env('TELESCOPE_CACHE_WATCHER', true),
            'hidden' => [],
        ],
        Laravel\Telescope\Watchers\CommandWatcher::class => [
            'enabled' => env('TELESCOPE_COMMAND_WATCHER', true),
            'ignore' => ['telescope:*'],
        ],
        Laravel\Telescope\Watchers\DumpWatcher::class => [
            'enabled' => env('TELESCOPE_DUMP_WATCHER', true),
            'always' => env('TELESCOPE_DUMP_WATCHER_ALWAYS', false),
        ],
        Laravel\Telescope\Watchers\EventWatcher::class => [
            'enabled' => env('TELESCOPE_EVENT_WATCHER', true),
            'ignore' => [],
        ],
        Laravel\Telescope\Watchers\ExceptionWatcher::class => env('TELESCOPE_EXCEPTION_WATCHER', true),
        Laravel\Telescope\Watchers\JobWatcher::class => env('TELESCOPE_JOB_WATCHER', true),
        Laravel\Telescope\Watchers\LogWatcher::class => env('TELESCOPE_LOG_WATCHER', true),
        Laravel\Telescope\Watchers\MailWatcher::class => env('TELESCOPE_MAIL_WATCHER', true),
        Laravel\Telescope\Watchers\ModelWatcher::class => [
            'enabled' => env('TELESCOPE_MODEL_WATCHER', true),
            'hydrations' => true,
        ],
        Laravel\Telescope\Watchers\NotificationWatcher::class => env('TELESCOPE_NOTIFICATION_WATCHER', true),
        Laravel\Telescope\Watchers\QueryWatcher::class => [
            'enabled' => env('TELESCOPE_QUERY_WATCHER', true),
            'ignore_packages' => true,
            'ignore_paths' => [],
            'slow' => 100,
        ],
        Laravel\Telescope\Watchers\RedisWatcher::class => env('TELESCOPE_REDIS_WATCHER', true),
        Laravel\Telescope\Watchers\RequestWatcher::class => [
            'enabled' => env('TELESCOPE_REQUEST_WATCHER', true),
            'size_limit' => env('TELESCOPE_RESPONSE_SIZE_LIMIT', 64),
        ],
        Laravel\Telescope\Watchers\GateWatcher::class => [
            'enabled' => env('TELESCOPE_GATE_WATCHER', true),
            'ignore_abilities' => [],
            'ignore_packages' => true,
            'ignore_paths' => [],
        ],
        Laravel\Telescope\Watchers\ScheduleWatcher::class => env('TELESCOPE_SCHEDULE_WATCHER', true),
        Laravel\Telescope\Watchers\ViewWatcher::class => [
            'enabled' => env('TELESCOPE_VIEW_WATCHER', true),
            'ignore_paths' => [],
        ],
    ],
];
