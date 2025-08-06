<?php

use Illuminate\Support\Facades\Route;

Route::get('/vite-debug', function () {
    $manifestPath = public_path('build/.vite/manifest.json');
    $hotPath = public_path('hot');
    
    return response()->json([
        'app_url' => config('app.url'),
        'manifest_exists' => file_exists($manifestPath),
        'manifest_path' => $manifestPath,
        'manifest_readable' => is_readable($manifestPath),
        'hot_exists' => file_exists($hotPath),
        'hot_path' => $hotPath,
        'public_path' => public_path(),
        'build_path' => public_path('build'),
        'vite_manifest' => file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : null,
        'server_info' => [
            'hostname' => $_SERVER['HTTP_HOST'] ?? 'unknown',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'unknown'
        ]
    ], 200, [], JSON_PRETTY_PRINT);
});
