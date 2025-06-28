<?php

return [
    'name' => env('APP_NAME', 'StudiosUnisDB'),
    'version' => 'v4.1.9-STABLE',
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'asset_url' => env('ASSET_URL'),
    'timezone' => 'America/Montreal',
    'locale' => 'fr',
    'fallback_locale' => 'en',
    'faker_locale' => 'fr_CA',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
    
    // ... le reste de votre config existante
];
