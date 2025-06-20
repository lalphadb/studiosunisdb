<?php
// Script pour export Telescope via web

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Export en JSON pour analyse
$data = [
    'timestamp' => now()->toISOString(),
    'exceptions' => DB::table('telescope_entries')
        ->where('type', 'exception')
        ->where('created_at', '>=', now()->subDay())
        ->orderBy('created_at', 'desc')
        ->get()
        ->toArray(),
    'logs' => DB::table('telescope_entries')
        ->where('type', 'log')
        ->whereRaw("JSON_EXTRACT(content, '$.level') IN ('error', 'critical', 'emergency')")
        ->where('created_at', '>=', now()->subDay())
        ->orderBy('created_at', 'desc')
        ->get()
        ->toArray(),
    'slow_queries' => DB::table('telescope_entries')
        ->where('type', 'query')
        ->whereRaw("CAST(JSON_EXTRACT(content, '$.time') AS DECIMAL) > 100")
        ->where('created_at', '>=', now()->subDay())
        ->orderByRaw("CAST(JSON_EXTRACT(content, '$.time') AS DECIMAL) DESC")
        ->get()
        ->toArray(),
];

file_put_contents('telescope_export_' . date('Ymd_His') . '.json', json_encode($data, JSON_PRETTY_PRINT));
echo "✅ Export JSON créé !\n";
